<?php
require_once __DIR__ . '/../Models/MahasiswaProfile.php';

class MahasiswaProfileController {
    private MahasiswaProfile $profileModel;
    private string $baseUrl;
    private string $projectRoot;

    public function __construct(PDO $pdo, string $baseUrl, string $projectRoot) {
        $this->profileModel = new MahasiswaProfile($pdo);
        $this->baseUrl = $baseUrl;
        $this->projectRoot = $projectRoot;
    }

    public function index(): void {
        // Cek apakah pengguna sudah login
        if (empty($_SESSION['user'])) {
            header('Location: ' . $this->baseUrl . '/public/index.php?page=login');
            exit;
        }

        // Cek role pengguna
        if (($_SESSION['user']['role'] ?? '') !== 'mahasiswa') {
            http_response_code(403);
            echo "Akses hanya untuk mahasiswa.";
            return;
        }
        $userId = (int) $_SESSION['user']['id'];

        // Proses form yang dikirim
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            // UPDATE DATA DIRI
            if ($action === 'update_profile') {
                $name = trim($_POST['nama'] ?? '');
                $nim = trim($_POST['nim'] ?? '');
                $phone = trim($_POST['telepon'] ?? '');
                $address = trim($_POST['domisili'] ?? '');
                $gender = trim($_POST['gender'] ?? '');
                $birthDateInput = trim($_POST['lahir'] ?? '');

                $birthDate = $birthDateInput !== ''
                    ? $birthDateInput
                    : null;

                // Validasi tanggal lahir
                if ($birthDate !== null) {
                    $date = DateTime::createFromFormat('!Y-m-d', $birthDate);

                    if (!$date || $date->format('Y-m-d') !== $birthDate) {
                        $_SESSION['profile_message'] = 'Format tanggal lahir tidak valid.';
                        header('Location: ' . $this->baseUrl . '/public/index.php?page=profile-mahasiswa');
                        exit;
                    }
                }

                // Validasi nama dan jenis kelamin
                if ($name === '') {
                    $_SESSION['profile_message'] = 'Nama lengkap tidak boleh kosong.';
                } else if ($nim === '') {
                    $_SESSION['profile_message'] = 'NIM tidak boleh kosong.';
                } elseif (!in_array($gender, ['Perempuan', 'Laki-laki'], true)) {
                    $_SESSION['profile_message'] = 'Pilih jenis kelamin yang valid.';
                } else {
                    try {
                        $this->profileModel->updatePersonal(
                            $userId,
                            $name,
                            $nim,
                            $phone,
                            $address,
                            $gender,
                            $birthDate
                        );

                        $_SESSION['user']['name'] = $name;
                        $_SESSION['profile_message'] = 'Profil berhasil diperbarui.';

                    } catch (DomainException $e) {
                        $_SESSION['profile_message'] = $e->getMessage();

                    } catch (PDOException $e) {
                        error_log($e->getMessage());

                        $_SESSION['profile_message'] =
                            'NIM sudah digunakan atau terjadi kesalahan database.';

                    } catch (Throwable $e) {
                        error_log($e->getMessage());

                        $_SESSION['profile_message'] =
                            'Gagal memperbarui profil. Silakan coba lagi.';
                    }
                }
            }

            // UPDATE DATA AKADEMIK
            if ($action === 'update_academic') {
                $university = trim($_POST['universitas'] ?? '');
                $faculty = trim($_POST['fakultas'] ?? '');
                $major = trim($_POST['prodi'] ?? '');

                $year = filter_var(
                    $_POST['angkatan'] ?? '',
                    FILTER_VALIDATE_INT
                );

                $semester = filter_var(
                    $_POST['semester'] ?? '',
                    FILTER_VALIDATE_INT
                );

                $gpaInput = $_POST['ipk'] ?? '';

                // Validasi data akademik
                if (
                    $university === '' ||
                    $faculty === '' ||
                    $major === '' ||
                    $year === false ||
                    $year < 1900 ||
                    $semester === false ||
                    $semester < 1 ||
                    $semester > 14 ||
                    !is_numeric($gpaInput) ||
                    (float) $gpaInput < 0 ||
                    (float) $gpaInput > 4
                ) {
                    $_SESSION['profile_message'] =
                        'Data akademik tidak valid. Periksa kembali isianmu.';

                } else {
                    try {
                        $this->profileModel->updateAcademic(
                            $userId,
                            $university,
                            $faculty,
                            $major,
                            $year,
                            $semester,
                            (float) $gpaInput
                        );

                        $_SESSION['profile_message'] =
                            'Data akademik berhasil diperbarui.';

                    } catch (Throwable $e) {
                        error_log($e->getMessage());

                        $_SESSION['profile_message'] =
                            'Gagal memperbarui data akademik. Silakan coba lagi.';
                    }
                }
            }

            // Kembali ke halaman profil setelah proses POST
            header('Location: ' . $this->baseUrl . '/public/index.php?page=profile-mahasiswa');
            exit;
        }

        // AMBIL DATA PROFIL DARI DATABASE
        $profileData = $this->profileModel->getByUserId($userId);

        if (!$profileData) {
            http_response_code(404);
            echo "Data profil mahasiswa tidak ditemukan.";
            return;
        }

        // Siapkan data untuk View
        $profile = [
            'nama'        => $profileData['name'],
            'nim'         => $profileData['nim'],
            'email'       => $profileData['email'],
            'telepon'     => $profileData['phone'],
            'gender'      => $profileData['gender'],
            'lahir'       => $profileData['birth_date'],
            'domisili'    => $profileData['address'],
            'universitas' => $profileData['university'],
            'fakultas'    => $profileData['faculty'],
            'prodi'       => $profileData['major'],
            'angkatan'    => $profileData['year'],
            'semester'    => $profileData['semester'],
            'ipk'         => $profileData['gpa'],
            'avatar'      => $profileData['avatar'],
            'skills'      => []
        ];

        // Ambil pesan notifikasi
        $message = $_SESSION['profile_message'] ?? '';
        unset($_SESSION['profile_message']);

        // Siapkan variabel yang dibutuhkan View
        $baseUrl = $this->baseUrl;
        $projectRoot = $this->projectRoot;

        // Tampilkan halaman profil
        require $this->projectRoot . '/app/Views/profile-mahasiswa/profile_user.php';    }
}