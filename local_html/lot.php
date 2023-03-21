<?php 
	require_once('helpers.php');
	require_once 'init.php';

  $user = $_SESSION["user_name"] ?? "";
  $errors['cost'] = "";
// если были отправлены данные с формы
  if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $price = intval(trim(htmlspecialchars($_POST['cost'])));

    if ( isset($_GET['lot_id']) ){
      $lot_id = intval($_GET['lot_id']);
      $lot = get_single_lot($link, $lot_id);
      $categories = get_categories($link);
      $min_bet = $lot['last_bet'] + $lot['step'];
      // проверка правильной ставки
      $errors['cost'] = validate_bet_price($_POST['cost'], $min_bet);
      // добавить ставку в таблицу ставок bets
      $errors = array_filter($errors);

      if(!$errors) {
        add_bet($link, intval($_POST['cost']), intval($_SESSION['user_id']), $lot_id);
      }
      // отрисовать страницу
      $lot_content = include_template("lot-content.php", [
        "lot" => $lot,
        "categories" => $categories,
        "user" => $user,
        "errors" => $errors
      ]);
  
      $layout_content = include_template("layout.php", [
            "content" => $lot_content,
            "categories" => $categories,
            "user" => $user,
            "title" => $lot['title']]);
  
      print($layout_content);

    } else {
      // если не задан id лота
      $error_content = include_template("404.php", [
        "categories" => $categories
      ]);

      $page_layout = include_template("layout.php", [
        "categories" => $categories,
        "content" => $error_content,
        "user" => $user,
        "title" => "Ошибочка"
      ]);
      print($page_layout);
      }
  } else {
// если не были отправлены данные с формы а просто смотрим на выбранный лот
    if ( isset($_GET['lot_id']) ) {
      $lot_id = intval($_GET['lot_id']);	
      $lot = get_single_lot($link, $lot_id);

      $cateroies = get_categories($link);

      $lot_content = include_template("lot-content.php", [
        "lot" => $lot,
        "categories" => $cateroies,
        "user" => $user,
        "errors" => $errors
      ]);
  
      $layout_content = include_template("layout.php", [
            "content" => $lot_content,
            "categories" => $cateroies,
            "user" => $user,
            "title" => $lot['title']]);
  
      print($layout_content);
    } else {
      // если не был передан id лота
      $categories = get_categories($link);
      $error_content = include_template("404.php", [
          "categories" => $categories
      ]);
  
      $page_layout = include_template("layout.php", [
        "categories" => $categories,
        "content" => $error_content,
        "user" => $user,
        "title" => "Ошибочка"
      ]);
      print($page_layout);
      
    }
  }
	
?>