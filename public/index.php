<?php

session_start();

define('MVC', dirname(__DIR__) . '/src/mvc/');
define('MODEL', MVC . 'model/');
define('VIEW', MVC . 'view/');
define('CONTROLLER', MVC . 'controller/');

require MVC . 'config.php';
require MVC . 'application.php';
require MVC . 'controller.php';

$app = new Application();