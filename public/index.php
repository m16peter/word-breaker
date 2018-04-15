<?php

session_start();

define('SRC', dirname(__DIR__) . '/src/');
define('MVC', SRC . '/mvc/');
define('MODEL', MVC . 'model/');
define('VIEW', MVC . 'view/');
define('CONTROLLER', MVC . 'controller/');

require MVC . 'config.php';
require MVC . 'application.php';
require MVC . 'controller.php';
require MVC . 'types.php';

$app = new Application();