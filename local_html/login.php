<?php 
require_once("helpers.php");
require_once("functions.php");
error_reporting(E_ALL);
ini_set('display_errors', 'on');

if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $connection = mysqli_connect("localhost", "root", "admin123", "yeticave_db");
  if($connection){
    $sql = "SELECT * FROM categories";
    $categories_dbo = mysqli_query($connection, $sql);
    $categories = mysqli_fetch_all($categories_dbo, MYSQLI_ASSOC);
    $errors = [];
    $user = filter_input_array(INPUT_POST, [
      "email" => FILTER_DEFAULT,
      "password" => FILTER_DEFAULT
    ]);

    $required = ["email", "password"];
    foreach ($user as $key => $value) {
      if(in_array($key, $required) && empty($value)) {
        $errors[$key] = "Поле должно быть заполнено";
      }
    }

    $errors = array_filter($errors);

    if($errors) {
      $page_content = include_template("login-content.php", [
        "categories" => $categories,
        "errors" => $errors
      ]);

      $layout_content = include_template("layout.php", [
        "categories" => $categories,
        "content" => $page_content,
        "title" => "Вход"
      ]);

      print($layout_content);
    } else {
      $sql = "SELECT * FROM users WHERE email='".$user['email']."'";
      $usr_dbo = mysqli_query($connection, $sql);
      $usr = mysqli_fetch_assoc($usr_dbo);

      if(!empty($usr)) {
        if(password_verify($user['password'], $usr['user_password'])) {
          
          $is_session = session_start();
          $_SESSION['user_id'] = $usr['id'];
          $_SESSION["user_name"] = $usr["user_name"];
        } else {
          $errors['password'] = "Вы ввели неверный пароль";
        }
      } else {
        $errors['email']  = "Пользоателя с таким email не существует";
      }

      if($errors) {
        $page_content = include_template("login-content.php", [
          "categories" => $categories,
          "errors" => $errors
        ]);
  
        $layout_content = include_template("layout.php", [
          "categories" => $categories,
          "content" => $page_content,
          "title" => "Вход"
        ]);
  
        print($layout_content);
      } else {
        mysqli_close($connection);
        header("Location: http://yeticave/index.php");
        exit();
      }
    }

  } else {
    echo "Ошибка подключения к БД" . mysqli_error($connection);
  }
} else {
  $connection = mysqli_connect("localhost", "root", "admin123", "yeticave_db");
  if($connection) {
    $sql = "SELECT * FROM categories";
    $categories_dbo = mysqli_query($connection, $sql);
    $categories = mysqli_fetch_all($categories_dbo, MYSQLI_ASSOC);

    $page_content = include_template("login-content.php", [
      "categories" => $categories,
      "errors" => []
    ]);

    $layout_content = include_template("layout.php", [
      "categories" => $categories,
      "content" => $page_content,
      "title" => "Вход"
    ]);

    print($layout_content);
  } else {
    echo "Ошибка подключения к БД" . mysqli_error($connection);
  }
}



?>