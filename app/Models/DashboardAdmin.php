<?php

class DashboardAdmin
{
    private $db;

    public function __construct($pdo)
    {
        $this->db = $pdo;
    }

    /**
     * Menghitung total program dari tabel `program` (jika sudah dibuat & terisi oleh tim),
     * dengan fallback otomatis jika tabel `program` belum siap.
     */
    public function getTotalPrograms()
    {
        try {
            $stmt = $this->db->query("SELECT COUNT(*) AS total FROM program");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        } catch (PDOException $e) {
            // Jika tabel `program` belum siap, hitung jenis unik dari manajemen_registrations
            $stmt = $this->db->query("SELECT COUNT(DISTINCT jenis_program) AS total FROM manajemen_registrations");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (int)($row['total'] ?? 0);
        }
    }

    /**
     * Menghitung persentase kenaikan total program dibanding bulan lalu
     */
    public function getProgramGrowthPercentage()
    {
        try {
            $stmt = $this->db->query("
                SELECT 
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS bulan_ini,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS bulan_lalu
                FROM program
            ");
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return $this->calculateGrowth((int)($row['bulan_ini'] ?? 0), (int)($row['bulan_lalu'] ?? 0));
        } catch (PDOException $e) {
            return 0;
        }
    }

    /**
     * Mengambil distribusi seluruh jenis program secara fleksibel (Beasiswa, Lomba, Magang, dll)
     * Diurutkan dari yang jumlahnya paling banyak.
     */
    public function getProgramDistributionByType()
    {
        $data = [];
        try {
            // Cek apakah tabel program punya kolom `jenis` dan ada datanya
            $stmt = $this->db->query("
                SELECT jenis AS label, COUNT(*) AS total,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS bulan_ini,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS bulan_lalu
                FROM program 
                WHERE jenis IS NOT NULL AND jenis != ''
                GROUP BY jenis 
                ORDER BY total DESC
            ");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            $data = [];
        }

        // Jika tabel `program` belum diisi temanmu, ambil distribusi dari `manajemen_registrations`
        if (empty($data)) {
            $stmt = $this->db->query("
                SELECT jenis_program AS label, COUNT(*) AS total,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS bulan_ini,
                    SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS bulan_lalu
                FROM manajemen_registrations 
                WHERE jenis_program IS NOT NULL AND jenis_program != ''
                GROUP BY jenis_program 
                ORDER BY total DESC
            ");
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $grandTotal = array_sum(array_column($data, 'total'));
        $colors = ['#4EACD4', '#60A5FA', '#34D399', '#FBBF24', '#A78BFA'];

        foreach ($data as $index => &$item) {
            $item['total']  = (int)$item['total'];
            $item['pct']    = $grandTotal > 0 ? round(($item['total'] / $grandTotal) * 100) : 0;
            $item['growth'] = $this->calculateGrowth((int)$item['bulan_ini'], (int)$item['bulan_lalu']);
            $item['color']  = $colors[$index % count($colors)];
        }

        return [
            'items'      => $data,
            'grandTotal' => $grandTotal
        ];
    }

    /**
     * Total Pendaftar dari tabel manajemen_registrations
     */
    public function getTotalRegistrations()
    {
        $stmt = $this->db->query("SELECT COUNT(*) AS total FROM manajemen_registrations");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($row['total'] ?? 0);
    }

    /**
     * Persentase kenaikan pendaftar bulan ini dibanding bulan lalu
     */
    public function getRegistrationGrowthPercentage()
    {
        $stmt = $this->db->query("
            SELECT 
                SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE()) AND YEAR(created_at) = YEAR(CURRENT_DATE()) THEN 1 ELSE 0 END) AS bulan_ini,
                SUM(CASE WHEN MONTH(created_at) = MONTH(CURRENT_DATE() - INTERVAL 1 MONTH) AND YEAR(created_at) = YEAR(CURRENT_DATE() - INTERVAL 1 MONTH) THEN 1 ELSE 0 END) AS bulan_lalu
            FROM manajemen_registrations
        ");
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $this->calculateGrowth((int)($row['bulan_ini'] ?? 0), (int)($row['bulan_lalu'] ?? 0));
    }

    /**
     * Tren pendaftaran mahasiswa 5 bulan terakhir dari tabel manajemen_registrations
     */
    public function getMonthlyRegistrationTrends()
    {
        $namaBulan = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        // Siapkan 5 bulan terakhir (termasuk bulan ini) dengan nilai default 0
        $trends = [];
        for ($i = 4; $i >= 0; $i--) {
            $timestamp = strtotime("-$i months");
            $ym = date('Y-m', $timestamp);
            $monthNum = (int)date('n', $timestamp);
            $trends[$ym] = [
                'label' => $namaBulan[$monthNum],
                'total' => 0
            ];
        }

        $sql = "SELECT DATE_FORMAT(created_at, '%Y-%m') AS ym, COUNT(*) AS total
                FROM manajemen_registrations
                WHERE created_at >= DATE_SUB(DATE_FORMAT(CURRENT_DATE(), '%Y-%m-01'), INTERVAL 4 MONTH)
                GROUP BY ym";
        $stmt = $this->db->query($sql);

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            if (isset($trends[$row['ym']])) {
                $trends[$row['ym']]['total'] = (int)$row['total'];
            }
        }

        $result = [];
        foreach ($trends as $t) {
            $result[$t['label']] = $t['total'];
        }
        return $result;
    }

    /**
     * Mengambil 5 pendaftaran terbaru dari manajemen_registrations (JOIN ke user/mahasiswa)
     */
    public function getRecentRegistrations($limit = 5)
    {
        $sql = "SELECT r.id,
                       COALESCE(u.name, r.nama_pendaftar) AS nama_mahasiswa,
                       r.jenis_program,
                       r.status,
                       r.created_at
                FROM manajemen_registrations r
                LEFT JOIN mahasiswa m ON r.mahasiswa_id = m.id
                LEFT JOIN user u ON m.user_id = u.id
                ORDER BY r.created_at DESC
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $avatarColors = ['student-avatar--teal', 'student-avatar--navy', 'student-avatar--green', 'student-avatar--red'];
        $bulanIndo = [
            1 => 'Jan', 2 => 'Feb', 3 => 'Mar', 4 => 'Apr',
            5 => 'Mei', 6 => 'Jun', 7 => 'Jul', 8 => 'Agu',
            9 => 'Sep', 10 => 'Okt', 11 => 'Nov', 12 => 'Des'
        ];

        $formatted = [];
        foreach ($rows as $idx => $row) {
            $nama = trim($row['nama_mahasiswa'] ?? 'Mahasiswa');
            $words = preg_split('/\s+/', $nama);
            $initials = strtoupper(substr($words[0], 0, 1) . (isset($words[1]) ? substr($words[1], 0, 1) : ''));

            // Format tanggal Indonesia: 13 Okt 2026, 09:41
            $ts = strtotime($row['created_at']);
            $tgl = date('d', $ts) . ' ' . $bulanIndo[(int)date('n', $ts)] . ' ' . date('Y, H:i', $ts);

            // Mapping status ENUM ('Approved', 'Pending', 'Rejected') ke Bahasa Indonesia
            $rawStatus = $row['status'] ?? 'Pending';
            if ($rawStatus === 'Approved') {
                $statusIndo = 'Disetujui';
                $badgeClass = 'status-badge--approved';
            } elseif ($rawStatus === 'Rejected') {
                $statusIndo = 'Ditolak';
                $badgeClass = 'status-badge--rejected';
            } else {
                $statusIndo = 'Tertunda';
                $badgeClass = 'status-badge--pending';
            }

            $formatted[] = [
                'name'    => $nama,
                'program' => $row['jenis_program'] ?? '-',
                'date'    => $tgl,
                'status'  => $statusIndo,
                'avatar'  => $initials ?: 'M',
                'badge'   => $badgeClass,
                'color'   => $avatarColors[$idx % count($avatarColors)]
            ];
        }

        return $formatted;
    }

    private function calculateGrowth($current, $previous)
    {
        if ($previous <= 0) {
            return $current > 0 ? 100 : 0;
        }
        return (int)round((($current - $previous) / $previous) * 100);
    }

    public function delete($id)
    {
        $stmt = $this->db->prepare("DELETE FROM manajemen_registrations WHERE id = :id");
        return $stmt->execute([':id' => (int)$id]);
    }

    public function getAvailableProgramTypes()
    {
        $types = [];

        // 1. Ambil jenis unik dari tabel `program` (jika tabel & datanya sudah diisi temanmu)
        try {
            $stmt = $this->db->query("SELECT DISTINCT jenis FROM program WHERE jenis IS NOT NULL AND jenis != '' ORDER BY jenis ASC");
            $rows = $stmt->fetchAll(PDO::FETCH_COLUMN);
            foreach ($rows as $r) {
                $types[] = trim($r);
            }
        } catch (PDOException $e) {
            // Abaikan jika tabel/kolom `program` belum siap
        }

        // 2. Gabungkan juga jenis yang sudah ada di `manajemen_registrations` agar filter tetap sinkron
        try {
            $stmt2 = $this->db->query("SELECT DISTINCT jenis_program FROM manajemen_registrations WHERE jenis_program IS NOT NULL AND jenis_program != '' ORDER BY jenis_program ASC");
            $rows2 = $stmt2->fetchAll(PDO::FETCH_COLUMN);
            foreach ($rows2 as $r) {
                $val = trim($r);
                if (!in_array($val, $types, true)) {
                    $types[] = $val;
                }
            }
        } catch (PDOException $e) {
        }

        return $types;
    }
}