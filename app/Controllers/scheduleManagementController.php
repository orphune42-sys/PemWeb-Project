<?php
require_once __DIR__ . '/../Models/ScheduleManagement.php';

class ScheduleManagementController {
    private Schedule $scheduleModel;
    private string $baseUrl;

    public function __construct(string $baseUrl) {
        $this->scheduleModel = new Schedule();
        $this->baseUrl = $baseUrl;
    }

    public function index(): void {
        $message = '';
        $messageType = 'success';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $action = $_POST['action'] ?? '';

            if ($action === 'create') {
                if (!empty(trim($_POST['title'] ?? ''))) {
                    $this->scheduleModel->create($_POST);
                    $message = 'Program baru berhasil ditambahkan!';
                    $messageType = 'success';
                } else {
                    $message = 'Judul program wajib diisi!';
                    $messageType = 'error';
                }
            } elseif ($action === 'update') {
                $id = trim($_POST['id'] ?? '');
                if ($this->scheduleModel->update($id, $_POST)) {
                    $message = 'Program berhasil diperbarui!';
                    $messageType = 'success';
                } else {
                    $message = 'Data program tidak ditemukan!';
                    $messageType = 'error';
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