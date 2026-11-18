<?php
require_once __DIR__ . '/../Models/ScheduleManagement.php';

class ScheduleManagementController {
    private Schedule $scheduleModel;
    private string $baseUrl;

    public function __construct(string $baseUrl) {
        $this->scheduleModel = new Schedule();
        $this->baseUrl = $baseUrl;

        if (!isset($_SESSION['user']['role']) || $_SESSION['user']['role'] != 'admin') {
            http_response_code(403);
            exit("Access denied.");
        }
    }

    public function index(): void {
        $message = '';
        $messageType = 'success';



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'create' || $action === 'update') {
                $title = trim($_POST['title'] ?? '');
                $allowedCategories = ['Lomba', 'Beasiswa'];
                $allowedStatuses = ['Aktif', 'Akan Datang', 'Selesai'];

                if ($title === '') {
                    $message = 'Judul program wajib diisi!';
                    $messageType = 'error';

                } elseif (!in_array($_POST['category'] ?? '', $allowedCategories, true)) {
                    $message = 'Kategori tidak valid!';
                    $messageType = 'error';

                } elseif (!in_array($_POST['status'] ?? '', $allowedStatuses, true)) {
                    $message = 'Status tidak valid!';
                    $messageType = 'error';

                } else {
                    if ($action === 'create') {
                        if ($this->scheduleModel->create($_POST)) {
                            $message = 'Program baru berhasil ditambahkan!';
                        } else {
                            $message = 'Gagal menambahkan program baru!';
                            $messageType = 'error';
                        }
                    } elseif ($action === 'update') {
                        $id = trim($_POST['id'] ?? '');

                        if ($this->scheduleModel->update($id, $_POST)) {
                            $message = 'Program berhasil diperbarui!';
                        } else {
                            $message = 'Program tidak ditemukan!';
                            $messageType = 'error';
                            }
                        }
                    }

            } elseif ($action === 'delete') {
                $id = trim($_POST['id'] ?? '');
                if ($this->scheduleModel->delete($id)) {
                    $message = 'Program berhasil dihapus!';
                    $messageType = 'success';
                } else {
                    $message = 'Gagal menghapus program, data tidak ditemukan!';
                    $messageType = 'error';
                }
            }
        }

        $schedules = $this->scheduleModel->getAll();
        $totalPrograms = count($schedules);
        $baseUrl = $this->baseUrl;
        $scheduleModel = $this->scheduleModel;

        require __DIR__ . '/../Views/schedule/scheduleManagement.php';
    }
}