<?php

class Document extends Controller
{
    public function index()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['json_id']))
        {
            require VIEW . 'document.php';
        }
        else
        {
            require VIEW . 'problem.php';
        }
    }
}
