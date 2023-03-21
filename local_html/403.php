<?php 
	require_once('helpers.php');
	require_once('functions.php');
  // error_reporting(E_ALL);
  // ini_set('display_errors', 'on');

  session_start();
  $message = empty($_SESSION["user_name"]) ? "Пройдите регистрацию или выполните вход." : "Вы не можете зарегистрироваться дважды.";
  $con = mysqli_connect('localhost', "root", "admin123", "yeticave_db");
  if($con) {
    $categories = get_categories($con);

    $page_content = include_template("403-content.php", [
      "categories" => $categories, 
      "message" => $message
    ]);

    $page_layout = include_template("layout.php", [
      "categories" => $categories,
      "title" => "Ошибка",
      "content" => $page_content
    ]);

    print($page_layout);
    
  } else {
    echo "Ошибка подключения к БД" . mysqli_error($con);
  }
  
  ?>