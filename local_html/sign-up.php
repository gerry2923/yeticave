<?php 
require_once("helpers.php");
require_once("functions.php");
error_reporting(E_ALL);
ini_set('display_errors', 'on');

session_start();
if(!empty($_SESSION['user_name'])) {
  header("Location: //yeticave/403.php");
  exit();
}


if($_SERVER['REQUEST_METHOD'] === 'POST'){
  $connection = mysqli_connect("localhost", "root", "admin123", "yeticave_db");
  if($connection) {
    $sql = "SELECT * FROM categories";
    $categories_dbo = mysqli_query($connection, $sql);
    $categories = mysqli_fetch_all($categories_dbo, MYSQLI_ASSOC);

    $user = filter_input_array(INPUT_POST, [
      "email" => FILTER_DEFAULT,
      "password" => FILTER_DEFAULT,
      "name" => FILTER_DEFAULT,
      "message" => FILTER_DEFAULT
    ], true);
    
    $required = ["email", "password", "name", "message"];

    $rules = [
      "email" => function($value) use ($connection){
        return validate_email($connection, $value);
      },

      "password" => function($value) {
        return validate_text_length($value, 3, 20);
      },

      "name" => function ($value) use ($connection){
        return validate_name($value, 1, 70, $connection);
      }, 

      "message" => function($value) {
        return validate_text_length($value, 10, 1000);
      }
    ];

    foreach ($user as $field => $value) {
      if(isset($rules[$field])) {
        $rule = $rules[$field];
        $errors[$field] = $rule($value);
      }

      if(in_array($field, $required) && empty($value)) {
        $errors[$field] = "Поле должно быть заполнено";
      }
    }

    $errors = array_filter($errors);

    if($errors) {
      // Есть ошибки. Возвращаемся обратно и исправляем
      $page_content = include_template("sign-up-content.php", [
        "categories" => $categories,
        "errors" => $errors
      ], true);

      $layout_content = include_template("layout.php", [
        "categories" => $categories,
        "content" => $page_content,
        "title" => "Регистрация"
      ], true);

      print($layout_content);

    } else {
      // Все верно. Сохраняем в БД
      echo "Сохраняем <br />";
      $sql = get_query_create_user();
      $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
      $stmt = db_get_prepare_stmt_version($connection, $sql, $user);
      $res = mysqli_stmt_execute($stmt);

      if($res){
        mysqli_close($connection);
        header('Location: http://yeticave/pages/login.html');
        exit();
      } else {
        echo "Ошибка добавления нового пользователя" . mysqli_error($connection);
      }

    }

  } else {
    echo "Ошибка подключения к базе данных" . mysqli_error($connection);
  }

} else {
  //  перешли с сылки региста
  $connection = mysqli_connect("localhost", "root", "admin123", "yeticave_db");
  if($connection) {
    $sql = "SELECT * FROM categories";
    $categories_dbo = mysqli_query($connection, $sql);
    $categories = mysqli_fetch_all($categories_dbo, MYSQLI_ASSOC);

    $page_content = include_template('sign-up-content.php', [
      "categories" => $categories,
      "errors" => []
    ]);

    $layout_content = include_template("layout.php", [
      "categories" => $categories,
      "content" => $page_content,
      "title" => "Регистрация"
    ]);

    print($layout_content);

  } else {
    echo 'Ошибка подключения к базе данных' . mysqli_error($connection);
  }
}
?>