<?php

class ManagementRegist
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    // Hitung total pendaftar untuk pagination
    public function countAll()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM registrations");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    // Ambil daftar pendaftaran dengan pagination & JOIN ke tabel mahasiswa + user
    public function getPaginated($limit, $offset)
    {
        $sql = "SELECT r.*, 
                       COALESCE(u.name, r.nama_pendaftar) AS display_nama, 
                       COALESCE(u.email, r.email) AS display_email
                FROM registrations r
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

    // Tambah data pendaftaran baru
    public function create($nama, $email, $jenis)
    {
        // Cek otomatis apakah email sudah terdaftar di tabel user -> mahasiswa
        $mahasiswaId = null;
        $cekMhs = $this->db->prepare("SELECT m.id FROM mahasiswa m JOIN user u ON m.user_id = u.id WHERE u.email = :email LIMIT 1");
        $cekMhs->execute([':email' => $email]);
        if ($rowMhs = $cekMhs->fetch(PDO::FETCH_ASSOC)) {
            $mahasiswaId = (int)$rowMhs['id'];
        }

        $sql = "INSERT INTO registrations (mahasiswa_id, nama_pendaftar, email, jenis_program, status) 
                VALUES (:mahasiswa_id, :nama, :email, :jenis, 'Pending')";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            ':mahasiswa_id' => $mahasiswaId,
            ':nama'         => $nama,
            ':email'        => $email,
            ':jenis'        => $jenis
        ]);
    }

    // Perbarui data pendaftaran
    public function update($id, $nama, $email, $jenis)
    {
        $sql = "UPDATE registrations 
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

    // Hapus data pendaftaran
    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM registrations WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }
}