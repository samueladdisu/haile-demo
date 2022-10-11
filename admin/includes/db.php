<?php

// include($_SERVER['DOCUMENT_ROOT'] . DIRECTORY_SEPARATOR . basename(dirname(dirname(dirname(__FILE__)))) . "/admin_config.php");
include(dirname(dirname(dirname(__FILE__))) . "/admin_config.php");
$db_username = $_ENV['DB_USERNAME'];
$db_pwd = $_ENV['DB_PASSWORD'];
$db_name = $_ENV['DB_NAME'];

// $connection = mysqli_connect('localhost', $db_username, $db_pwd, $db_name);
$connection = mysqli_connect('localhost', 'root', '', 'haile');

if (!$connection) {
    echo "Error: Unable to connect to MySQL." . PHP_EOL;
    echo "Debugging errno: " . mysqli_connect_errno() . PHP_EOL;
    echo "Debugging error: " . mysqli_connect_error() . PHP_EOL;
    exit;
}
