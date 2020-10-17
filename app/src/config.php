<?php
set_time_limit(-1);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
date_default_timezone_set('Europe/Budapest');

$path = '/srv/private/Photos';
//$path = '/home/szabacsik/test';
//$path = "d:\\temp\\test\\";

$PdoDsn = 'mysql:' .
    'host=192.168.xxx.xxx;' .
    'dbname=duplicates;' .
    'charset=utf8mb4';
$username = 'root';
$password = 'PASSWORD';
$PdoOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];
$pdo = new \PDO($PdoDsn, $username, $password, $PdoOptions);
