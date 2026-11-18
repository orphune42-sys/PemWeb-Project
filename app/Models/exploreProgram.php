<?php
class ExploreProgram
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function getAll(): array
    {
        $stmt = $this->pdo->query(
            "SELECT * FROM `program` ORDER BY id DESC"
        );
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Ambil semua data dari program_detail lalu kelompokkan per program_id
        $detailStmt = $this->pdo->query(
            "SELECT program_id, jenis, isi FROM `program_detail` ORDER BY id ASC"
        );
        $detailsByProgram = [];
        foreach ($detailStmt->fetchAll(PDO::FETCH_ASSOC) as $detail) {
            $pid = (int) $detail['program_id'];
            $jenis = strtolower(trim($detail['jenis']));
            $detailsByProgram[$pid][$jenis][] = $detail['isi'];
        }

        $programs = [];
        foreach ($rows as $row) {
            $id = (int) $row['id'];
            $rawDate = $row['deadline'] ?? $row['tanggal_mulai'] ?? null;
            $formattedDate = $rawDate ? date('d M Y', strtotime($rawDate)) : '-';

            $programs[] = [
                'id'            => $id,
                'type'          => $row['kategori'] ?? 'Program',
                'kategori'      => $row['kategori'] ?? 'Program',
                'title'         => $row['judul'] ?? '',
                'judul'         => $row['judul'] ?? '',
                'organizer'     => $row['penyelenggara'] ?? '',
                'penyelenggara' => $row['penyelenggara'] ?? '',
                'location'      => $row['lokasi'] ?? 'Online',
                'lokasi'        => $row['lokasi'] ?? 'Online',
                'date'          => $formattedDate,
                'tanggal_mulai' => $row['tanggal_mulai'] ?? null,
                'deadline'      => $row['deadline'] ?? null,
                'category'      => $row['kategori'] ?? 'Umum',
                'desc'          => $row['deskripsi'] ?? '',
                'deskripsi'     => $row['deskripsi'] ?? '',
                'hadiah'        => $row['hadiah'] ?? '',
                'status'        => $row['status'] ?? 'Dibuka',
                'image'         => !empty($row['gambar'])
                    ? $row['gambar']
                    : 'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?w=500&auto=format&fit=crop',
                'persyaratan'   => $detailsByProgram[$id]['persyaratan'] ?? [],
                'benefit'       => $detailsByProgram[$id]['benefit'] ?? [],
                'dokumen'       => $detailsByProgram[$id]['dokumen'] ?? [],
            ];
        }
        return $programs;
    }
}
