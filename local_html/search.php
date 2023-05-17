<?php 
require_once("helpers.php");
require_once 'init.php';

//  Ищет лот и показывает результат в шаблоне
// если не найдено, то должна быть надпись "Ничего не найдено по вашему запросу"

// В поле формы поиска дб текст заданного запроса
$categories = get_categories($link);

if(isset($_GET['search'])) {
  $search_rqst=trim(htmlspecialchars($_GET['search']));
  
  $items_per_page = 9;
  $offset = 0;
  $passed_data = [];
  $current_page = 0;

  // $_GET['page'] = текущая страница пагинации на странице поиска
  if(isset($_GET['page']) && is_int($_GET['page'])){
    $offset = $items_per_page * intval($_GET['page']);
    $current_page = $_GET["page"] ?? 1;
  }

  array_push($passed_data, $search_rqst, $offset, $items_per_page);

  if(!empty($search_rqst)){

    $sql =  "SELECT l.id, l.title, l.image, l.start_price, l.date_finish, l.step, c.category_name as category ".
            "FROM (SELECT * FROM lots WHERE date_finish > CURDATE()) as l ".
            "JOIN categories as c ".
            "ON l.category_id = c.id ".
            "WHERE MATCH (title, description) AGAINST(?) ".
            "ORDER BY date_creation DESC ".
            "LIMIT ?, ?";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, 'sii', ...$passed_data);
    mysqli_stmt_execute($stmt);
    $res_lots_dbo = mysqli_stmt_get_result($stmt);
    $res_lots = mysqli_fetch_all($res_lots_dbo, MYSQLI_ASSOC); 

    $total_items = mysqli_num_rows($res_lots_dbo);
    $total_pages = ceil($total_items/$items_per_page);
    
    $page_content = include_template("search-content.php", [
      "categories" => $categories,
      "lots" => $res_lots,
      "request" => $search_rqst,
      "total_pages" => $total_pages,
      "current_page" => $current_page
    ]);
    
    $page_layout = include_template("layout.php", [
      "categories" => $categories,
      "content" => $page_content,
      "title" => "Результаты поиска",
      "request" => $search_rqst,
      "user" => $_SESSION['user_name'] ?? NULL
    ]);

    print($page_layout);
    
  } else {
    header("Location: " . $_SERVER['HTTP_REFERER']);
  }
  
} else {
  header("Location: " . $_SERVER['HTTP_REFERER']);
}



?>