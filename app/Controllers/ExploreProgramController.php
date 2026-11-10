<?php
require_once __DIR__ . '/../Models/exploreProgram.php';
class ExploreProgramController
{
    public function index(): void
    {
        $programModel = new exploreProgram();
        $programs = $programModel->getAll();
        $baseUrl = rtrim(dirname(dirname($_SERVER['SCRIPT_NAME'])), '/\\');
        require __DIR__ . '/../Views/explore/exploreProgram.php';
    }
}
