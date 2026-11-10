<?php
class Schedule
{
    private string $dataFile;

    public function __construct()
    {
        $this->dataFile = dirname(__DIR__, 2) . '/data/schedules.json';
    }

    public function getAll(): array
    {
        if (!file_exists($this->dataFile)) {
            return [];
        }
        $data = json_decode(file_get_contents($this->dataFile), true);
        return is_array($data) ? $data : [];
    }

    public function create(array $data): bool
    {
        $schedules = $this->getAll();
        $newSchedule = [
            'id'          => 'sch_' . bin2hex(random_bytes(5)),
            'title'       => trim($data['title'] ?? ''),
            'category'    => trim($data['category'] ?? 'Lomba'),
            'status'      => trim($data['status'] ?? 'Aktif'),
            'start_date'  => trim($data['start_date'] ?? ''),
            'end_date'    => trim($data['end_date'] ?? ''),
            'event_date'  => !empty($data['event_date']) ? trim($data['event_date']) : (trim($data['start_date'] ?? '') ?: date('Y-m-d')),
            'location'    => trim($data['location'] ?? ''),
            'description' => trim($data['description'] ?? '')
        ];
        $schedules[] = $newSchedule;
        return (bool) file_put_contents($this->dataFile, json_encode($schedules, JSON_PRETTY_PRINT));
    }

    public function update(string $id, array $data): bool
    {
        $schedules = $this->getAll();
        $found = false;
        foreach ($schedules as &$sch) {
            if (($sch['id'] ?? '') === $id) {
                $sch['title']       = trim($data['title'] ?? '');
                $sch['category']    = trim($data['category'] ?? 'Lomba');
                $sch['status']      = trim($data['status'] ?? 'Aktif');
                $sch['start_date']  = trim($data['start_date'] ?? '');
                $sch['end_date']    = trim($data['end_date'] ?? '');
                $sch['event_date']  = !empty($data['event_date']) ? trim($data['event_date']) : (trim($data['start_date'] ?? '') ?: date('Y-m-d'));
                $sch['location']    = trim($data['location'] ?? '');
                $sch['description'] = trim($data['description'] ?? '');
                $found = true;
                break;
            }
        }
        unset($sch);
        if ($found) {
            return (bool) file_put_contents($this->dataFile, json_encode($schedules, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function delete(string $id): bool
    {
        $schedules = $this->getAll();
        $initialCount = count($schedules);
        $schedules = array_values(array_filter($schedules, fn($sch) => ($sch['id'] ?? '') !== $id));
        if (count($schedules) < $initialCount) {
            return (bool) file_put_contents($this->dataFile, json_encode($schedules, JSON_PRETTY_PRINT));
        }
        return false;
    }

    public function formatTanggalIndo(?string $dateStr): string
    {
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

    public function formatPeriodeIndo(?string $start, ?string $end): string
    {
        if (empty($start) && empty($end)) return '-';
        if (!empty($start) && !empty($end)) {
            return $this->formatTanggalIndo($start) . ' - ' . $this->formatTanggalIndo($end);
        }
        return $this->formatTanggalIndo($start ?: $end);
    }
}