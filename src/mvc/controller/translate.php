<?php

/*
* Translate class
*/
class Translate extends Controller
{

    /*
    * Translate view
    */
    public function index()
    {
        if (isset($_SESSION['user_id']) && isset($_SESSION['json_id']))
        {
            require VIEW . 'translate.php';
        }
        else
        {
            require VIEW . 'problem.php';
        }
    }
}
