<?php 
// require_once("vendor/autoload.php");

require_once('helpers.php');
require_once 'init.php';

if($link == false) {
    echo "Ошибка подключения: " . mysqli_connect_error();
  } else {
    $winners = get_winners($link);

    if(!empty($winners)){
      foreach($winners as $winner){
        set_winner($link, $winner);
      }
    }

    $lots_sql =  "SELECT l.id, title, image, start_price as price, date_finish, category_name as category 
                  FROM lots as l 
                  JOIN categories AS c 
                  ON l.category_id = c.id 
                  WHERE date_finish > CURRENT_TIMESTAMP" ;

    $lots_dbo = mysqli_query($link, $lots_sql);
    $lots = mysqli_fetch_all($lots_dbo, MYSQLI_ASSOC);

    $categories = get_categories($link);
    
    $page_content = include_template("main.php", [
      "categories" => $categories,
      "lots" => $lots]);
      
    $layout_content = include_template("layout.php", [
      "content" => $page_content,
      "categories" => $categories,
      "title" => "Главная",
      "user" => $_SESSION["user_name"] ?? NULL,
      "search_rqst" => NULL
      ]);

    print($layout_content);
  }

?>

