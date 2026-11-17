<?php
class Program {
    private PDO $pdo;

    public function __construct(PDO $pdo) {
        $this->pdo = $pdo;
    }

    /**
     * Mengambil satu program berdasarkan ID
     * beserta persyaratan, benefit, dan dokumennya.
     */
    public function getById(int $id): ?array
    {
        // Mengambil data utama program
        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM program
             WHERE id = :id
             LIMIT 1"
        );

        $stmt->execute(['id' => $id]);
        $program = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$program) {
            return null;
        }

        // Menyiapkan kelompok detail
        $program['persyaratan'] = [];
        $program['benefit'] = [];
        $program['dokumen'] = [];

        // Mengambil detail program
        $stmtDetail = $this->pdo->prepare(
            "SELECT jenis, isi
             FROM program_detail
             WHERE program_id = :id
             ORDER BY id ASC"
        );

        $stmtDetail->execute(['id' => $id]);
        $details = $stmtDetail->fetchAll(PDO::FETCH_ASSOC);

        // Mengelompokkan detail berdasarkan jenis
        foreach ($details as $detail) {
            $jenis = strtolower(trim($detail['jenis']));
            if (array_key_exists($jenis, $program)) {
                $program[$jenis][] = $detail['isi'];
            }
        }
        return $program;
    }
}