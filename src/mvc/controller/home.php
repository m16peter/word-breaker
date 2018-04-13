<?php

/*
* Home class - the main view of the page
*
* - if session of auth is set,
*   load the actual home-page,
*   else load the login page.
*
*/
class Home extends Controller
{

    /*
    * Start page
    */
    public function index()
    {
        if (isset($_SESSION['auth']))
        {
            require VIEW . 'home.php';
        }
        else
        {
            require VIEW . 'login.php';
        }
    }
}
