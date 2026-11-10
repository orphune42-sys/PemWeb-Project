<?php

class programManagementModel
{
    private $file;

    public function __construct()
    {
        $this->file = __DIR__ . '/../../data/programs.json';
    }

    public function getAll()
    {
        if (!file_exists($this->file)) {
            return [];
        }

        $data = file_get_contents($this->file);

        return json_decode($data, true) ?? [];
    }

    public function getById($id)
    {
        $programs = $this->getAll();

        foreach ($programs as $program) {
            if ($program['id'] == $id) {
                return $program;
            }
        }

        return null;
    }

    private function save($programs)
    {
        $json = json_encode(
            $programs,
            JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE
        );

        return file_put_contents($this->file, $json, LOCK_EX) !== false;
    }

    public function add($data)
    {
        $programs = $this->getAll();

        $ids = array_column($programs, 'id');
        $newId = empty($ids) ? 1 : max($ids) + 1;

        $data['id'] = $newId;

        $programs[] = $data;

        return $this->save($programs);
    }

    public function update($id, $data)
    {
        $programs = $this->getAll();

        foreach ($programs as $key => $program) {
            if ($program['id'] == $id) {
                $data['id'] = (int) $id;
                $programs[$key] = $data;

                return $this->save($programs);
            }
        }

        return false;
    }

    public function delete($id)
    {
        $programs = $this->getAll();

        $filteredPrograms = array_filter(
            $programs,
            function ($program) use ($id) {
                return $program['id'] != $id;
            }
        );

        if (count($filteredPrograms) === count($programs)) {
            return false;
        }

        return $this->save(array_values($filteredPrograms));
    }
}
