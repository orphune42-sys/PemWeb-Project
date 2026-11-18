<?php

class dashboardUserModel
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getUserById($id)
    {
        $sql = "SELECT
                    u.id AS user_id,
                    u.name,
                    u.username,
                    u.email,
                    u.role,
                    m.id AS mahasiswa_id,
                    m.nim,
                    m.phone,
                    m.address,
                    m.gender,
                    m.birth_date,
                    m.university,
                    m.faculty,
                    m.major,
                    m.year,
                    m.semester,
                    m.gpa,
                    m.avatar
                FROM user u
                LEFT JOIN mahasiswa m ON u.id = m.user_id
                WHERE u.id = :id
                AND u.role = 'mahasiswa'";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getPrograms()
    {
        $sql = "SELECT *
                FROM schedule_management
                ORDER BY tanggal_pelaksanaan ASC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getRegistrations($mahasiswaId)
    {
        $sql = "SELECT
                    r.*,
                    s.nama_program,
                    s.tanggal_pelaksanaan,
                    s.lokasi
                FROM manajemen_registrations r
                LEFT JOIN schedule_management s
                    ON r.program_id = s.id
                WHERE r.mahasiswa_id = :mahasiswa_id
                ORDER BY r.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getNotifications($mahasiswaId)
    {
        $sql = "SELECT
                    r.id,
                    r.status,
                    r.jenis_program,
                    r.created_at,
                    s.nama_program
                FROM manajemen_registrations r
                LEFT JOIN schedule_management s
                    ON r.program_id = s.id
                WHERE r.mahasiswa_id = :mahasiswa_id
                ORDER BY r.created_at DESC";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'mahasiswa_id' => $mahasiswaId
        ]);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getDashboardData($userId)
    {
        $user = $this->getUserById($userId);

        if (!$user) {
            return [
                'user' => null,
                'programs' => [],
                'registrations' => [],
                'notifications' => []
            ];
        }

        $mahasiswaId = $user['mahasiswa_id'];

        return [
            'user' => $user,
            'programs' => $this->getPrograms(),
            'registrations' => $this->getRegistrations($mahasiswaId),
            'notifications' => $this->getNotifications($mahasiswaId)
        ];
    }
}
