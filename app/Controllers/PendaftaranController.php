<?php
require_once __DIR__ . '/../Models/Pendaftaran.php';

class PendaftaranController {
    private Pendaftaran $pendaftaranModel;
    private string $baseUrl;
    private string $projectRoot;

    public function __construct(
        PDO $pdo,
        string $baseUrl,
        string $projectRoot
    ) {
        $this->pendaftaranModel = new Pendaftaran($pdo);
        $this->baseUrl = $baseUrl;
        $this->projectRoot = $projectRoot;
    }

    public function index(): void {
        // Memastikan pengguna sudah login
        if (!isset($_SESSION['user'])) {
            header(
                'Location: ' . $this->baseUrl .
                '/public/index.php?page=login'
            );
            exit;
        }

        // Memastikan pengguna adalah mahasiswa
        $role = strtolower(trim($_SESSION['user']['role'] ?? ''));

        if ($role !== 'mahasiswa') {
            http_response_code(403);
            exit('Akses ditolak. Halaman ini hanya untuk mahasiswa.');
        }

        // Mengambil identitas akun
        $userId = (int) ($_SESSION['user']['id'] ?? 0);
        $namaAkun = $_SESSION['user']['name'] ?? '';
        $emailAkun = $_SESSION['user']['email'] ?? '';

        $mahasiswaId = $this->pendaftaranModel
            ->getMahasiswaId($userId);

        if (!$mahasiswaId) {
            http_response_code(403);
            exit('Data mahasiswa tidak ditemukan. Silakan hubungi administrator.');
        }

        // Membuat token CSRF
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        // Memeriksa aksi Edit
        if (($_GET['aksi'] ?? '') === 'edit') {
            $this->edit($mahasiswaId);
            return;
        }

        // Memeriksa aksi Batalkan Pendaftaran
        if (
            $_SERVER['REQUEST_METHOD'] === 'POST' &&
            ($_POST['aksi'] ?? '') === 'cancel'
        ) {
            $this->cancel($mahasiswaId);
            return;
        }

        // Validasi CSRF untuk pendaftaran baru
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submittedToken = $_POST['csrf_token'] ?? '';
            $sessionToken = $_SESSION['csrf_token'] ?? '';

            if (
                !is_string($submittedToken) ||
                !is_string($sessionToken) ||
                $sessionToken === '' ||
                !hash_equals($sessionToken, $submittedToken)
            ) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }
        }

        // Mengambil ID program
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id) {
            $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        }

        if (!$id || $id < 1) {
            http_response_code(400);
            exit('ID program tidak valid.');
        }

        // Menyiapkan data View
        $baseUrl = $this->baseUrl;
        $program = $this->pendaftaranModel->getProgram($id);
        $documents = [];
        $message = '';
        $success = false;
        $registrationId = '';
        $existingRegistration = false;

        $fieldErrors = [];
        $documentErrorId = null;
        $documentErrorMessage = '';

        // Mengambil dokumen dan memeriksa pendaftaran sebelumnya
        if ($program) {
            $documents = $this->pendaftaranModel->getDocuments($id);

            $existingRegistration = $this->pendaftaranModel
                ->getExistingRegistration($mahasiswaId, $id);
        }

        // Memproses pendaftaran baru
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $program) {
            $telepon = trim($_POST['telepon'] ?? '');
            $universitas = trim($_POST['universitas'] ?? '');
            $prodi = trim($_POST['prodi'] ?? '');
            $semester = trim($_POST['semester'] ?? '');
            $alasan = trim($_POST['alasan'] ?? '');

            // Memeriksa ulang pendaftaran ganda
            $existingRegistration = $this->pendaftaranModel
                ->getExistingRegistration($mahasiswaId, $id);

            if (strtolower(trim($program['status'] ?? '')) !== 'dibuka') {
                $message = 'Maaf, pendaftaran program ini sedang ditutup.';

            } elseif ($existingRegistration) {
                $message = 'Kamu sudah pernah mendaftar program ini. Silakan cek riwayat pendaftaranmu.';

            } elseif (
                $namaAkun === '' ||
                $emailAkun === '' ||
                $telepon === '' ||
                $universitas === '' ||
                $prodi === '' ||
                $semester === '' ||
                $alasan === ''
            ) {
                $fieldErrors = [];

                if ($telepon === '') {
                    $fieldErrors['telepon'] = 'Nomor telepon wajib diisi.';
                }

                if ($universitas === '') {
                    $fieldErrors['universitas'] = 'Universitas wajib diisi.';
                }

                if ($prodi === '') {
                    $fieldErrors['prodi'] = 'Program studi wajib diisi.';
                }

                if ($semester === '') {
                    $fieldErrors['semester'] = 'Semester wajib diisi.';
                }

                if ($alasan === '') {
                    $fieldErrors['alasan'] = 'Alasan mengikuti program wajib diisi.';
                }

                $message = '';

            } elseif (!filter_var($emailAkun, FILTER_VALIDATE_EMAIL)) {
                $message = 'Format email tidak valid.';

            } elseif (
                !ctype_digit($semester) ||
                (int) $semester < 1 ||
                (int) $semester > 14
            ) {
                $message = 'Semester tidak valid.';

            } else {
                $maxSize = 100 * 1024 * 1024;

                $allowedFiles = [
                    'application/pdf' => ['pdf'],
                    'image/jpeg' => ['jpg', 'jpeg'],
                    'image/png' => ['png']
                ];

                $uploadedFiles = [];
                $uploadDirectory = $this->projectRoot .
                    '/uploads/pendaftaran/';

                $documentErrorId = null;
                $documentErrorMessage = '';

                try {
                    // Validasi seluruh dokumen
                    foreach ($documents as $doc) {
                        $docId = (int) $doc['id'];

                        if (
                            !isset($_FILES['dokumen']['error'][$docId]) ||
                            $_FILES['dokumen']['error'][$docId] === UPLOAD_ERR_NO_FILE
                        ) {
                            $documentErrorId = $docId;
                            throw new RuntimeException(
                                'Dokumen "' . $doc['isi'] . '" wajib diunggah.'
                            );
                        }

                        $fileError = $_FILES['dokumen']['error'][$docId];

                        if ($fileError !== UPLOAD_ERR_OK) {
                            throw new RuntimeException(
                                'Terjadi kesalahan saat mengunggah dokumen "' .
                                $doc['isi'] . '".'
                            );
                        }

                        $fileSize = (int) $_FILES['dokumen']['size'][$docId];
                        $tmpName = $_FILES['dokumen']['tmp_name'][$docId];

                        if ($fileSize <= 0 || $fileSize > $maxSize) {
                            throw new RuntimeException(
                                'Ukuran dokumen "' . $doc['isi'] .
                                '" harus lebih dari 0 dan maksimal 100 MB.'
                            );
                        }

                        if (!is_uploaded_file($tmpName)) {
                            throw new RuntimeException(
                                'File dokumen "' . $doc['isi'] .
                                '" tidak valid.'
                            );
                        }

                        $finfo = new finfo(FILEINFO_MIME_TYPE);
                        $mime = $finfo->file($tmpName);

                        $originalName = $_FILES['dokumen']['name'][$docId];

                        $originalExtension = strtolower(
                            pathinfo($originalName, PATHINFO_EXTENSION)
                        );

                        if (
                            !isset($allowedFiles[$mime]) ||
                            !in_array($originalExtension, $allowedFiles[$mime], true)
                        ) {
                            $documentErrorId = $docId;

                            throw new RuntimeException(
                                'Format tidak valid. Gunakan PDF, JPG, atau PNG.'
                            );
                        }

                        $extension = $allowedFiles[$mime][0];
                        $randomName = bin2hex(random_bytes(16)) .'.' . $extension;
                        $relativePath = 'uploads/pendaftaran/' . $randomName;

                        $uploadedFiles[] = [
                            'doc_id' => $docId,
                            'tmp_name' => $tmpName,
                            'file_name' => $randomName,
                            'path' => $relativePath,
                            'mime' => $mime,
                            'size' => $fileSize
                        ];
                    }

                    // Memastikan folder upload tersedia
                    if (!is_dir($uploadDirectory)) {
                        if (!mkdir($uploadDirectory, 0755, true)) {
                            throw new RuntimeException(
                                'Folder penyimpanan dokumen gagal dibuat.'
                            );
                        }
                    }

                    // Memindahkan file
                    foreach ($uploadedFiles as $index => $file) {
                        $destination = $uploadDirectory .
                            $file['file_name'];

                        if (!move_uploaded_file(
                            $file['tmp_name'],
                            $destination
                        )) {
                            throw new RuntimeException(
                                'Gagal menyimpan salah satu dokumen.'
                            );
                        }

                        $uploadedFiles[$index]['moved'] = true;
                    }

                    // Menyiapkan data pendaftaran
                    $registrationData = [
                        'program_id' => $id,
                        'mahasiswa_id' => $mahasiswaId,
                        'nama' => $namaAkun,
                        'email' => $emailAkun,
                        'telepon' => $telepon,
                        'universitas' => $universitas,
                        'prodi' => $prodi,
                        'semester' => $semester,
                        'alasan' => $alasan
                    ];

                    // Menyimpan melalui Model
                    $registrationId = $this->pendaftaranModel->create(
                        $registrationData,
                        $uploadedFiles
                    );

                    $success = true;

                } catch (Throwable $error) {
                    // Menghapus file jika penyimpanan gagal
                    foreach ($uploadedFiles as $file) {
                        if (!empty($file['moved'])) {
                            $savedPath = $uploadDirectory .
                                $file['file_name'];

                            if (is_file($savedPath)) {
                                unlink($savedPath);
                            }
                        }
                    }

                    if (
                        $error instanceof PDOException &&
                        isset($error->errorInfo[1]) &&
                        (int) $error->errorInfo[1] === 1062
                    ) {
                        $message = 'Kamu sudah pernah mendaftar program ini.';
                        $existingRegistration = true;

                    } elseif ($error instanceof PDOException) {
                        error_log('Pendaftaran gagal: ' . $error->getMessage());
                        $message = 'Pendaftaran gagal disimpan. Periksa kembali database.';

                    } else {
                        $message = $error->getMessage();

                        if ($documentErrorId !== null) {
                            $documentErrorMessage = $message;
                            $message = '';
                        }
                    }
                }
            }
        }

        // Memuat riwayat terbaru
        $riwayatPendaftaran = $this->pendaftaranModel
            ->getAllByMahasiswa($mahasiswaId);

        // Menampilkan View
        require $this->projectRoot .
            '/app/Views/pendaftaran/form.php';
    }

    // =========================================
    // EDIT PENDAFTARAN
    // =========================================

    private function edit(int $mahasiswaId): void
    {
        $baseUrl = $this->baseUrl;

        $pendaftaranId = filter_input(
            INPUT_GET,
            'pendaftaran_id',
            FILTER_VALIDATE_INT
        );

        if (!$pendaftaranId) {
            $pendaftaranId = filter_input(
                INPUT_POST,
                'pendaftaran_id',
                FILTER_VALIDATE_INT
            );
        }

        if (!$pendaftaranId || $pendaftaranId < 1) {
            http_response_code(400);
            exit('ID pendaftaran tidak valid.');
        }

        // Memastikan pendaftaran milik mahasiswa
        $pendaftaran = $this->pendaftaranModel
            ->getByIdForMahasiswa($pendaftaranId, $mahasiswaId);

        if (!$pendaftaran) {
            http_response_code(404);
            exit('Data pendaftaran tidak ditemukan.');
        }

        $program = $this->pendaftaranModel
            ->getProgram((int) $pendaftaran['program_id']);

        $message = '';
        $success = false;

        $canEdit = (
            $pendaftaran['status'] === 'Menunggu Verifikasi'
        );

        $csrfToken = $_SESSION['csrf_token'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $submittedToken = $_POST['csrf_token'] ?? '';

            if (
                !is_string($submittedToken) ||
                !hash_equals($csrfToken, $submittedToken)
            ) {
                http_response_code(403);
                exit('Permintaan tidak valid. Silakan muat ulang halaman.');
            }

            if (!$canEdit) {
                $message = 'Pendaftaran tidak dapat diedit karena sudah diproses.';

            } else {
                $data = [
                    'telepon' => trim($_POST['telepon'] ?? ''),
                    'universitas' => trim($_POST['universitas'] ?? ''),
                    'prodi' => trim($_POST['prodi'] ?? ''),
                    'semester' => trim($_POST['semester'] ?? ''),
                    'alasan' => trim($_POST['alasan'] ?? '')
                ];

                if (
                    $data['telepon'] === '' ||
                    $data['universitas'] === '' ||
                    $data['prodi'] === '' ||
                    $data['semester'] === '' ||
                    $data['alasan'] === ''
                ) {
                    $message = 'Semua kolom wajib diisi.';

                } elseif (
                    !ctype_digit($data['semester']) ||
                    (int) $data['semester'] < 1 ||
                    (int) $data['semester'] > 14
                ) {
                    $message = 'Semester harus berada antara 1 sampai 14.';

                } else {
                    $updated = $this->pendaftaranModel->update(
                        $pendaftaranId,
                        $mahasiswaId,
                        $data
                    );

                    $latest = $this->pendaftaranModel
                        ->getByIdForMahasiswa($pendaftaranId, $mahasiswaId);

                    $dataSama = $latest &&
                        $latest['telepon'] === $data['telepon'] &&
                        $latest['universitas'] === $data['universitas'] &&
                        $latest['prodi'] === $data['prodi'] &&
                        (string) $latest['semester'] === $data['semester'] &&
                        $latest['alasan'] === $data['alasan'];

                    if ($updated || $dataSama) {
                        $success = true;
                        $pendaftaran = $latest;
                        $message = 'Data pendaftaran berhasil diperbarui.';
                    } else {
                        $message = 'Data gagal diperbarui. Silakan coba lagi.';
                    }
                }

                if (!$success) {
                    $pendaftaran = array_merge($pendaftaran, $data);
                }
            }
        }

        require $this->projectRoot .
            '/app/Views/pendaftaran/edit.php';
    }

    // =========================================
    // BATALKAN PENDAFTARAN
    // =========================================

    private function cancel(int $mahasiswaId): void
    {
        $baseUrl = $this->baseUrl;

        $pendaftaranId = filter_input(
            INPUT_POST,
            'pendaftaran_id',
            FILTER_VALIDATE_INT
        );

        $programId = filter_input(
            INPUT_POST,
            'id',
            FILTER_VALIDATE_INT
        );

        if (
            !$pendaftaranId || $pendaftaranId < 1 ||
            !$programId || $programId < 1
        ) {
            http_response_code(400);
            exit('ID pendaftaran atau program tidak valid.');
        }

        // Validasi CSRF
        $submittedToken = $_POST['csrf_token'] ?? '';
        $sessionToken = $_SESSION['csrf_token'] ?? '';

        if (
            !is_string($submittedToken) ||
            !is_string($sessionToken) ||
            $sessionToken === '' ||
            !hash_equals($sessionToken, $submittedToken)
        ) {
            http_response_code(403);
            exit('Permintaan tidak valid. Silakan muat ulang halaman.');
        }

        $message = '';
        $cancelSuccess = false;

        try {
            $files = $this->pendaftaranModel->cancel(
                $pendaftaranId,
                $mahasiswaId
            );

            if ($files === null) {
                $message = 'Pendaftaran tidak ditemukan atau sudah tidak dapat dibatalkan.';
            } else {
                $uploadDirectory = $this->projectRoot .
                    '/uploads/pendaftaran/';

                foreach ($files as $filePath) {
                    $expectedPrefix = 'uploads/pendaftaran/';

                    if (strpos($filePath, $expectedPrefix) !== 0) {
                        continue;
                    }

                    $filename = basename($filePath);

                    if ($filename !== '') {
                        $fullPath = $uploadDirectory . $filename;

                        if (is_file($fullPath) && !unlink($fullPath)) {
                            error_log(
                                'Gagal menghapus file: ' . $fullPath
                            );
                        }
                    }
                }

                $cancelSuccess = true;
                $message = 'Pendaftaran berhasil dibatalkan.';
            }

        } catch (Throwable $error) {
            error_log(
                'Pembatalan pendaftaran gagal: ' . $error->getMessage()
            );

            $message = 'Pembatalan gagal diproses. Silakan coba lagi.';
        }

        // Memuat ulang data setelah pembatalan
        $id = $programId;
        $program = $this->pendaftaranModel->getProgram($id);

        $documents = $program
            ? $this->pendaftaranModel->getDocuments($id)
            : [];

        $namaAkun = $_SESSION['user']['name'] ?? '';
        $emailAkun = $_SESSION['user']['email'] ?? '';

        $success = false;
        $registrationId = '';

        $existingRegistration = $program
            ? $this->pendaftaranModel->getExistingRegistration(
                $mahasiswaId,
                $id
            )
            : false;

        $riwayatPendaftaran = $this->pendaftaranModel
            ->getAllByMahasiswa($mahasiswaId);

        require $this->projectRoot .
            '/app/Views/pendaftaran/form.php';
    }
}