<?php

class ManagementRegist
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM manajemen_registrations");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT r.*, 
                       COALESCE(u.name, r.nama_pendaftar) AS display_nama, 
                       COALESCE(u.email, r.email) AS display_email
                FROM manajemen_registrations r
                LEFT JOIN mahasiswa m ON r.mahasiswa_id = m.id
                LEFT JOIN user u ON m.user_id = u.id
                ORDER BY r.created_at DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nama, $email, $jenis)
    {
        $mahasiswaId = null;
        $cekMhs = $this->db->prepare("SELECT m.id FROM mahasiswa m JOIN user u ON m.user_id = u.id WHERE u.email = :email LIMIT 1");
        $cekMhs->execute([':email' => $email]);
        if ($rowMhs = $cekMhs->fetch(PDO::FETCH_ASSOC)) {
            $mahasiswaId = (int)$rowMhs['id'];
        }

        $sql = "INSERT INTO manajemen_registrations (mahasiswa_id, nama_pendaftar, email, jenis_program, status) 
                VALUES (:mahasiswa_id, :nama, :email, :jenis, 'Pending')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':mahasiswa_id' => $mahasiswaId,
            ':nama'         => $nama,
            ':email'        => $email,
            ':jenis'        => $jenis
        ]);
    }

    public function update($id, $nama, $email, $jenis)
    {
        $sql = "UPDATE manajemen_registrations 
                SET nama_pendaftar = :nama, email = :email, jenis_program = :jenis 
                WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':nama'  => $nama,
            ':email' => $email,
            ':jenis' => $jenis,
            ':id'    => (int)$id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM manajemen_registrations WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }
}