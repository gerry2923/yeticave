<?php 
	require_once('helpers.php');
	require_once 'init.php';

  if(empty($_SESSION['user_name'])) {
    header("Location: http://yeticave/index.php");
    exit();
  } else {
    $user = $_SESSION["user_name"];
  }

  $categories = get_categories($link);
  $rates = get_bets($link, intval($_SESSION['user_id']));

  // vardump($rates);

  $mybets_content = include_template("my-bets-content.php", [
    "rates" => $rates,
    "categories" => $categories
  ]);

  $layout_content = include_template("layout.php", [
    "content" => $mybets_content,
    "categories" => $categories,
    "user" => $user,
    "title" => "Мои ставки",
    "request" => ""]);

  print($layout_content);


  ?>