<?php

class Install extends Controller
{
    function __construct()
    {
        $this->controller = new Controller();
    }

    public function index()
    {
        require VIEW . 'problem.php';
    }

    public function create()
    {
        if (isset($_SESSION['user_id']))
        {
            if ($_SESSION['user_id'] == 1)
            {
                $this->controller->model->createDb();
                header('location: ' . URL);
                return;
            }
        }
        header('location: ' . URL . 'problem');
    }

    public function seed()
    {
        if (isset($_SESSION['user_id']))
        {
            if ($_SESSION['user_id'] == 1)
            {
                $this->controller->model->seedDB();
                header('location: ' . URL);
                return;
            }
        }
        header('location: ' . URL . 'problem');
    }
}
