<?php

class Install extends Controller
{
    function __construct()
    {
        $this->controller = new Controller();
    }

    public function index()
    {
        if (isset($_SESSION['user_id']))
        {
            if ($_SESSION['user_id'] == 1)
            {
                $this->controller->model->install();
                header('location: ' . URL);
                return;
            }
        }
        header('location: ' . URL . 'problem');
    }
}
