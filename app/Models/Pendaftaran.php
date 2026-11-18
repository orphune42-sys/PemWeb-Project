<?php
class Pendaftaran {
    private PDO $pdo;
    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    // Mengambil ID mahasiswa berdasarkan akun login
    public function getMahasiswaId(int $userId): ?int {
        $stmt = $this->pdo->prepare(
            "SELECT id FROM mahasiswa
             WHERE user_id = :user_id LIMIT 1"
        );

        $stmt->execute(['user_id' => $userId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ? (int) $result['id'] : null;
    }

    // Mengambil informasi program
    public function getProgram(int $programId): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM program
             WHERE id = :id LIMIT 1"
        );

        $stmt->execute(['id' => $programId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Mengambil dokumen persyaratan program
    public function getDocuments(int $programId): array {
        $stmt = $this->pdo->prepare(
            "SELECT id, isi FROM program_detail
             WHERE program_id = :id
             AND jenis = 'dokumen'
             ORDER BY id ASC"
        );

        $stmt->execute(['id' => $programId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengecek pendaftaran mahasiswa pada suatu program
    public function getExistingRegistration(
        int $mahasiswaId,
        int $programId
    ): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT id, kode_pendaftaran
             FROM pendaftaran
             WHERE mahasiswa_id = :mahasiswa_id
             AND program_id = :program_id
             LIMIT 1"
        );

        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId,
            'program_id' => $programId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Mengambil riwayat pendaftaran mahasiswa
    public function getAllByMahasiswa(int $mahasiswaId): array {
        $stmt = $this->pdo->prepare(
            "SELECT
                p.id,
                p.kode_pendaftaran,
                p.program_id,
                p.nama,
                p.status,
                p.tanggal_daftar,
                pr.judul AS nama_program,
                pr.kategori
             FROM pendaftaran p
             INNER JOIN program pr
                ON p.program_id = pr.id
             WHERE p.mahasiswa_id = :mahasiswa_id
             ORDER BY p.tanggal_daftar DESC"
        );

        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId
        ]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Mengambil detail pendaftaran milik mahasiswa
    public function getByIdForMahasiswa(
        int $pendaftaranId,
        int $mahasiswaId
    ): ?array {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM pendaftaran
             WHERE id = :id
             AND mahasiswa_id = :mahasiswa_id
             LIMIT 1"
        );

        $stmt->execute([
            'id' => $pendaftaranId,
            'mahasiswa_id' => $mahasiswaId
        ]);

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Mengubah data pendaftaran
    public function update(
        int $pendaftaranId,
        int $mahasiswaId,
        array $data
    ): bool {
        $stmt = $this->pdo->prepare(
            "UPDATE pendaftaran
            SET
                telepon = :telepon,
                universitas = :universitas,
                prodi = :prodi,
                semester = :semester,
                alasan = :alasan,
                tanggal_diubah = CURRENT_TIMESTAMP
            WHERE id = :id
            AND mahasiswa_id = :mahasiswa_id
            AND status = 'Menunggu Verifikasi'"
        );

        $stmt->execute([
            'telepon' => $data['telepon'],
            'universitas' => $data['universitas'],
            'prodi' => $data['prodi'],
            'semester' => $data['semester'],
            'alasan' => $data['alasan'],
            'id' => $pendaftaranId,
            'mahasiswa_id' => $mahasiswaId
        ]);
        return $stmt->rowCount() > 0;
    }

    // Membatalkan pendaftaran dan menghapus data dokumen
    public function cancel(
        int $pendaftaranId,
        int $mahasiswaId
    ): ?array {
        try {
            $this->pdo->beginTransaction();

            // Mengunci dan memeriksa kepemilikan pendaftaran
            $stmt = $this->pdo->prepare(
                "SELECT id, status
                 FROM pendaftaran
                 WHERE id = :id
                 AND mahasiswa_id = :mahasiswa_id
                 FOR UPDATE"
            );

            $stmt->execute([
                'id' => $pendaftaranId,
                'mahasiswa_id' => $mahasiswaId
            ]);

            $pendaftaran = $stmt->fetch(PDO::FETCH_ASSOC);

            if (
                !$pendaftaran ||
                $pendaftaran['status'] !== 'Menunggu Verifikasi'
            ) {
                $this->pdo->rollBack();
                return null;
            }

            // Mengambil lokasi dokumen
            $stmtFiles = $this->pdo->prepare(
                "SELECT path_file
                 FROM pendaftaran_dokumen
                 WHERE pendaftaran_id = :pendaftaran_id"
            );

            $stmtFiles->execute([
                'pendaftaran_id' => $pendaftaranId
            ]);
            $files = $stmtFiles->fetchAll(PDO::FETCH_COLUMN);

            // Menghapus data dokumen
            $stmtDeleteDocuments = $this->pdo->prepare(
                "DELETE FROM pendaftaran_dokumen
                 WHERE pendaftaran_id = :pendaftaran_id"
            );

            $stmtDeleteDocuments->execute([
                'pendaftaran_id' => $pendaftaranId
            ]);

            // Menghapus data pendaftaran
            $stmtDeleteRegistration = $this->pdo->prepare(
                "DELETE FROM pendaftaran
                 WHERE id = :id
                 AND mahasiswa_id = :mahasiswa_id
                 AND status = 'Menunggu Verifikasi'"
            );

            $stmtDeleteRegistration->execute([
                'id' => $pendaftaranId,
                'mahasiswa_id' => $mahasiswaId
            ]);

            if ($stmtDeleteRegistration->rowCount() !== 1) {
                throw new RuntimeException(
                    'Pendaftaran gagal dibatalkan.'
                );
            }
            $this->pdo->commit();
            return $files;

        } catch (Throwable $error) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }

            throw $error;
        }
    }

    // Menyimpan pendaftaran dan dokumen
    public function create(array $data, array $documents): string {
        try {
            $this->pdo->beginTransaction();

            $kodePendaftaran = 'REG-' .
                strtoupper(bin2hex(random_bytes(6)));

            $stmt = $this->pdo->prepare(
                "INSERT INTO pendaftaran (
                    kode_pendaftaran,
                    program_id,
                    mahasiswa_id,
                    nama,
                    email,
                    telepon,
                    universitas,
                    prodi,
                    semester,
                    alasan,
                    status
                ) VALUES (
                    :kode,
                    :program_id,
                    :mahasiswa_id,
                    :nama,
                    :email,
                    :telepon,
                    :universitas,
                    :prodi,
                    :semester,
                    :alasan,
                    'Menunggu Verifikasi'
                )"
            );

            $stmt->execute([
                'kode' => $kodePendaftaran,
                'program_id' => $data['program_id'],
                'mahasiswa_id' => $data['mahasiswa_id'],
                'nama' => $data['nama'],
                'email' => $data['email'],
                'telepon' => $data['telepon'],
                'universitas' => $data['universitas'],
                'prodi' => $data['prodi'],
                'semester' => $data['semester'],
                'alasan' => $data['alasan']
            ]);

            $pendaftaranId = (int) $this->pdo->lastInsertId();
            $stmtDocument = $this->pdo->prepare(
                "INSERT INTO pendaftaran_dokumen (
                    pendaftaran_id,
                    program_detail_id,
                    nama_file,
                    path_file,
                    tipe_file,
                    ukuran_file
                ) VALUES (
                    :pendaftaran_id,
                    :program_detail_id,
                    :nama_file,
                    :path_file,
                    :tipe_file,
                    :ukuran_file
                )"
            );

            foreach ($documents as $document) {
                $stmtDocument->execute([
                    'pendaftaran_id' => $pendaftaranId,
                    'program_detail_id' => $document['doc_id'],
                    'nama_file' => $document['file_name'],
                    'path_file' => $document['path'],
                    'tipe_file' => $document['mime'],
                    'ukuran_file' => $document['size']
                ]);
            }

            $this->pdo->commit();
            return $kodePendaftaran;

        } catch (Throwable $error) {
            if ($this->pdo->inTransaction()) {
                $this->pdo->rollBack();
            }
            throw $error;
        }
    }
}