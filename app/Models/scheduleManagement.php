<?php

require_once __DIR__ . '/../../koneksi.php';

class Schedule {
    private PDO $pdo;

    public function __construct() {
       global $pdo;
       $this->pdo = $pdo;
    }

    public function getAll(): array {
    $query = "SELECT
                id,
                nama_program AS title,
                jenis_kategori AS category,
                status,
                tanggal_mulai_pendaftaran AS start_date,
                tanggal_selesai_pendaftaran AS end_date,
                tanggal_pelaksanaan AS event_date,
                lokasi AS location,
                deskripsi AS description
              FROM schedule_management
              ORDER BY id DESC";

    $stmt = $this->pdo->query($query);
    return $stmt->fetchAll();
}

    public function create(array $data): bool {
    try{
        $query = "INSERT INTO schedule_management (nama_program, jenis_kategori, status, tanggal_mulai_pendaftaran, tanggal_selesai_pendaftaran, tanggal_pelaksanaan, lokasi, deskripsi) VALUES (
        :title, :category, :status, :start_date, :end_date, :event_date, :location, :description )";

        $stmt = $this->pdo->prepare($query);

        return $stmt->execute([
            ':title'=>trim($data['title'] ?? ''),
            ':category'=>trim($data['category'] ?? 'Lomba'),
            ':status'=>trim($data['status'] ?? 'Aktif'),
            ':start_date'=>trim($data['start_date'] ?? ''),
            ':end_date'=>trim($data['end_date'] ?? ''),
            ':event_date'=>!empty($data['event_date']) ? trim($data['event_date']) : (trim($data['start_date'] ?? '') ?: date('Y-m-d')),
            ':location'=>trim($data['location'] ?? ''),
            ':description'=>trim($data['description'] ?? '')
        ]);

    } catch(PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
}

    public function update(string $id, array $data): bool {
    try{
        $query = "UPDATE schedule_management set
                nama_program = :title,
                jenis_kategori = :category,
                status = :status,
                tanggal_mulai_pendaftaran = :start_date,
                tanggal_selesai_pendaftaran = :end_date,
                tanggal_pelaksanaan = :event_date,
                lokasi = :location,
                deskripsi = :description
                where id = :id";

            $stmt = $this->pdo->prepare($query);

            return $stmt->execute ([
                ':id' => $id,
                ':title'=>trim($data['title'] ?? ''),
                ':category'=>trim($data['category'] ?? 'Lomba'),
                ':status'=>trim($data['status'] ?? 'Aktif'),
                ':start_date'=>trim($data['start_date'] ?? ''),
                ':end_date'=>trim($data['end_date'] ?? ''),
                ':event_date'=>!empty($data['event_date']) ? trim($data['event_date']) : (trim($data['start_date'] ?? '') ?: date('Y-m-d')),
                ':location'=>trim($data['location'] ?? ''),
                ':description'=>trim($data['description'] ?? '')
            ]);

        } catch(PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function delete(string $id): bool {
    try{
        $query = "DELETE FROM schedule_management where id = :id";
        $stmt = $this->pdo->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    } catch(PDOException $e) {
        error_log($e->getMessage());
        return false;
    }
    }

    public function formatTanggalIndo(?string $dateStr): string {
        if (empty($dateStr)) return '-';
        $timestamp = strtotime($dateStr);
        if (!$timestamp) return $dateStr;
        $bulanIndo = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr', 5 => 'Mei', 6 => 'Jun',
            7 => 'Jul', 8 => 'Agu', 9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];
        $d = date('j', $timestamp);
        $m = $bulanIndo[(int)date('n', $timestamp)] ?? date('M', $timestamp);
        $y = date('Y', $timestamp);
        return "$d $m $y";
    }

    public function formatPeriodeIndo(?string $start, ?string $end): string {
        if (empty($start) && empty($end)) return '-';
        if (!empty($start) && !empty($end)) {
            return $this->formatTanggalIndo($start) . ' - ' . $this->formatTanggalIndo($end);
        }
        return $this->formatTanggalIndo($start ?: $end);
    }
}