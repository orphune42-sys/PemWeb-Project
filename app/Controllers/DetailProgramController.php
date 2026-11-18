<?php
require_once __DIR__ . '/../Models/Program.php';
class DetailProgramController {
    private Program $programModel;
    private string $baseUrl;

    public function __construct(PDO $pdo, string $baseUrl) {
        $this->programModel = new Program($pdo);
        $this->baseUrl = $baseUrl;
    }

    public function index(): void {
        // Validasi ID program
        $id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        if (!$id || $id < 1) {
            http_response_code(400);
            exit('ID program tidak valid. Silakan pilih program melalui halaman Explore.');
        }

        // Mengambil data program melalui Model
        $program = $this->programModel->getById($id);

        if (!$program) {
            http_response_code(404);
            exit('Program tidak ditemukan.');
        }

        // Menentukan kategori
        $kategori = strtolower(trim($program['kategori'] ?? ''));

        $categoryClass = match ($kategori) {
            'beasiswa' => 'scholarship',
            'lomba' => 'competition',
            default => 'other'
        };

        // Menentukan status pendaftaran
        $status = strtolower(trim($program['status'] ?? ''));
        $isOpen = ($status === 'dibuka');

        $statusClass = $isOpen
            ? 'status-open'
            : 'status-closed';

        // Data untuk View
        $baseUrl = $this->baseUrl;

        require __DIR__ . '/../Views/detail-program/index.php';
    }
}
