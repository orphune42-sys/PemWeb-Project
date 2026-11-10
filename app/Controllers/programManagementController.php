<?php

require_once __DIR__ . '/../Models/programManagementModel.php';

class programManagementController
{
    private $model;

    public function __construct()
    {
        $this->model = new programManagementModel();
    }

    public function index()
    {
        $programs = $this->model->getAll();

        $stats = [
            'total' => 48,
            'active' => 31,
            'upcoming' => 9,
            'finished' => 8
        ];

        require __DIR__ . '/../Views/program-management/programManagement.php';
    }

    public function store()
    {
        $data = $this->validateInput();

        if ($data !== false) {
            $this->model->add($data);
        }

        $this->redirect();
    }

    public function update()
    {
        $id = $_POST['id'] ?? null;
        $data = $this->validateInput();

        if ($id && $data !== false) {
            $this->model->update($id, $data);
        }

        $this->redirect();
    }

    public function destroy()
    {
        $id = $_POST['id'] ?? null;

        if ($id) {
            $this->model->delete($id);
        }

        $this->redirect();
    }

    private function validateInput()
    {
        $name = trim($_POST['name'] ?? '');
        $organizer = trim($_POST['organizer'] ?? '');
        $type = $_POST['type'] ?? '';
        $field = trim($_POST['field'] ?? '');
        $deadline = $_POST['deadline'] ?? '';
        $registrants = $_POST['registrants'] ?? '';

        $allowedTypes = [
            'Beasiswa',
            'Lomba',
            'Program',
            'Magang'
        ];

        if (
            $name === '' ||
            $organizer === '' ||
            !in_array($type, $allowedTypes, true) ||
            $field === '' ||
            $deadline === '' ||
            !preg_match('/^\d{4}-\d{2}-\d{2}$/', $deadline) ||
            filter_var($registrants, FILTER_VALIDATE_INT) === false ||
            (int) $registrants < 0
        ) {
            return false;
        }

        return [
            'name' => $name,
            'organizer' => $organizer,
            'type' => $type,
            'field' => $field,
            'deadline' => $deadline,
            'registrants' => (int) $registrants
        ];
    }

    private function redirect()
    {
        header('Location: index.php?page=program-management');
        exit;
    }
}
