<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

session_start();

define('CACHE_DIR', basename(__DIR__ . DIRECTORY_SEPARATOR . 'cache'));
define('UPLOAD_PATH', basename(__DIR__ . DIRECTORY_SEPARATOR . 'uploads'));

$db_cfg = require_once 'config/db.php';
$db_cfg = array_values($db_cfg);

require_once 'functions.php';

try {
  $link = mysqli_connect(...$db_cfg);
  
} catch (Throwable $e){
  echo 'Ошибка при подключении к базе данных: ', $e->getMessage(), "\n";
  echo 'Код ошибки: '. mysqli_connect_errno() . '<br>';
}

mysqli_set_charset($link, "utf8");

$categories = [];
$content = '';