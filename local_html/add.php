<?php

require_once("helpers.php");
require_once("functions.php");
error_reporting(E_ALL);
ini_set('display_errors', 'on');

session_start();
if(empty($_SESSION["user_name"])) {
  header("Location: http://yeticave/403.php");
  exit();
}

$user = $_SESSION['user_name'];
// если форма была заполнена и отправлена
if($_SERVER['REQUEST_METHOD'] === 'POST') {
  $connection = mysqli_connect("localhost", "root", "admin123", "yeticave_db");
  if($connection) {
    
    $sql = "SELECT * FROM categories";
    $categories_dbo = mysqli_query($connection, $sql);
    $categories = mysqli_fetch_all($categories_dbo, MYSQLI_ASSOC);

    $sql = "SELECT id FROM categories";
    $categories_id_dbo = mysqli_query($connection, $sql);
    $categories_id = mysqli_fetch_all($categories_id_dbo, MYSQLI_ASSOC);

    // получаем данные из формы
    $lot = filter_input_array(INPUT_POST, 
    [
      "lot-name" => FILTER_DEFAULT,
      "category" => FILTER_DEFAULT, 
      "message" => FILTER_DEFAULT,
      "lot-rate" => FILTER_DEFAULT,
      "lot-step" => FILTER_DEFAULT,
      "lot-date" => FILTER_DEFAULT
    ], true);

    $required = ["lot-name", "category", "message", "lot-rate", "lot-step", "lot-date"];


    $rules = [
      "lot-name" => function($value){
        return validate_text_length($value, 10, 200);
      },

      "category" => function($value) use ($categories_id) {
        return validateCategory($value, $categories_id);
      },

      "message" => function($value) {
        return validate_text_length($value, 10, 3000);
      },

      "lot-rate" => function($value) {
        return validateRate($value);
      },

      "lot-step" => function($value) {
        return validateStep($value);
      },

      "lot-date" => function($value) {
        return validateDate($value);
      }];

      foreach($lot as $field => $value) {
        if(isset($rules[$field])){
          $rule = $rules[$field];
          $errors[$field] = $rule($value);
        }

        if(in_array($field, $required) && empty($value)) {
          $errors[$field] = "Поле должно быть заполнено";//"Поле $field нужно заполнить";
        }
      }

      $errors = array_filter($errors);

      // проверка загрузки файла
      if(!empty($_FILES["lot-image"]["name"])){
        // vardump($_FILES);

        $tmp_name = $_FILES['lot-image']['tmp_name'];
        $path = $_FILES['lot-image']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE); // возвращает все расширения MYME_TYPE (это массив)
        $file_type = finfo_file($finfo, $tmp_name); // массив с расширениями и название файла =>возвращает строку или false
        
        if($file_type === "image/jpeg") {
          $extension = ".jpg";
        } else if( $file_type === "image/png") {
          $extension = ".png";
        }

        // проверяем расширение
        if($extension) {
          $filename = uniqid() . $extension;

          $lot['image'] = "uploads/" . $filename;
          $path = "var/www/yeticave/local_html/uploads/" . $filename;
          $cur_name = $_FILES['lot-image']['tmp_name'];
          echo $path . '\n';
          echo $cur_name . '\n';
          $is_moved = move_uploaded_file($cur_name, 'uploads/' . $filename);
          echo $_FILES['lot-image']['error'];
          var_dump($is_moved);
          
        } else {
          $errors['lot-image'] = "Допустимые форматы файлов: jpg, jpeg, png";
        }
        
      } else {
        $errors['lot-image'] = "Добавьте, пожалуйста, изображение.";
      }
      // vardump($lot);
      // если есть ошибки открыть ту же страницу add.lot
      if($errors) {
        $page_content = include_template("add-lot.php", [
          "errors" => $errors,
          "categories" => $categories
        ]);

        $layout_content = include_template("layout.php", [
          "categories" => $categories,
          "content" => $page_content,
          "title" => "Добавить лот",
          "user" => $user
        ]);

        print($layout_content);

      } else {
        // сохранить в БД и перенаправить на страницу лота
        $sql = get_query_create_lot(2);
        $stmt = db_get_prepare_stmt_version($connection, $sql, $lot);
        $res =  mysqli_stmt_execute($stmt);
        
        if($res) {
          $fresh_lot_id = mysqli_insert_id($connection);
          // header("location:/lot.php?id=" );
          mysqli_close($connection);
          header('Location: http://yeticave/lot.php?lot_id=' . $fresh_lot_id);
          exit();
        } else {
          $error = mysqli_error($connection);
        }
      }

  } else {
    echo "Ошибка подключения к базе данных:" . "  " . mysqli_error($connection);
  } 
} else 
// если перешли с кнопки "Добавить"
{
 $connection = mysqli_connect("localhost", "root", "admin123", "yeticave_db");
 if($connection) {
  $sql = "SELECT * FROM categories";
  $categories_dbo = mysqli_query($connection, $sql);
  $categories = mysqli_fetch_all($categories_dbo, MYSQLI_ASSOC);

  $page_content = include_template("add-lot.php", [
    "categories" => $categories,
    "errors" => []
  ]);

  $layout_content = include_template("layout.php", [
    "categories" => $categories,
    "content" => $page_content,
    "user" => $user,
    "title" => "Добавить лот"
  ]);
  print($layout_content);

 } else {
  echo "Ошибка подключения к базе данных:" . "  " . mysqli_error($connection);
 } 
}

