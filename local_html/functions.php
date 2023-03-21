<?php
  /*** 
   * @param $var - объект, информацю о котором нужно посмотреть. 
  */
  function vardump($var) {
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
  }

  $is_auth = rand(0, 1);

  /**
   * Форматирует цену
   * @param number $price - изначальная цена
   * @return string -отформатированная цена
   * 
   * */
  function price_format($price){
    return number_format(ceil($price), 0, "", " ") . "&nbsp;₽";
  };

  /**
   * Summary of get_dt_range
   * @param datetime $date_final
   * @return array
   */
  function get_dt_range(datetime $date_final) {
  	date_default_timezone_set('Europe/Moscow');
  	$date_current = date_create('now');
    $rest_of_time = date_diff($date_current, $date_final); //DateTime
    $rest_of_time_str  = $rest_of_time->format("%R%d %H %I");

  	if(substr($rest_of_time_str, 0, 1) == "-") {
  		$result[0] = $result[1] = "00";
  		return $result; 
  	}

  	$rest_arr = explode(" ", $rest_of_time_str);
  	$hours = $rest_arr[0] * 24 + $rest_arr[1];
  	$minutes = $rest_arr[2];
  	$hours = str_pad($hours, 2, "0", STR_PAD_LEFT);
  	$minutes = str_pad($minutes, 2, "0", STR_PAD_LEFT);
  	$result[] = $hours;
  	$result[] = $minutes;
  	return $result;
  }
  // ---------------------------------------------------
  function validate_text_length($text, $min_val = 10, $max_val = 100){

  	if(!empty($text)) {
  		$text_length = strlen($text);
  		if( ($text_length < $min_val) || ($text_length > $max_val) ) {
  			return "Количество символов должно быть в пределах от $min_val до $max_val";
  		}
  	}
  	return null;
  }
  /**
   * Проверка валидности категорий
   * @param number $id  - идентиф. номер выбранной категории
   * @param array $exist_ids
   * 
   * Если id не принадлежит существующим значениям в exist_ids, то категория не верна
   */
  function validateCategory($id, $exist_ids){
    $exist_ids = array_column($exist_ids, "id");

    if(!empty($id))
  		if(!in_array($id, $exist_ids)) {
				return "Такой категории не существует";
  	}	 
  	
  	return null;
  } 

  /**
   * Summary of check_regular_expression
   * @param mixed $template
   * @param mixed $string
   * @return bool|int
   */
  function check_regular_expression($template, $string){
    $result = preg_match($template, $string);
    return $result;
  }

  /**
   * Summary of validateRate
   * @param string $price
   * @return string|null
   */
  function validateRate(string $price){
  	$template_reg_price = '/^(?!-)\d+[\.,]{0,1}\d*$/';
    if(!empty($price)) {
      
      if(!check_regular_expression($template_reg_price, $price)){
        return "Цена должна быть числом больше нуля";
      } 
    } else {
      return null;
    }
   
  }

  function validateStep($step){

    $template_reg_step = '/^(?!-)[1-9]+\d*$/';
    if(!empty($step)){
      if(!check_regular_expression($template_reg_step, $step)){
        return "Шаг ставки должен быть целым числом больше нуля!!!!";
      }
    }else {
      return null;
    }

  }

  /**
   * Summary of validate_bet_price
   * @param string $price
   * @param int $min_bet
   * @return string|null
   */
  function validate_bet_price(string $price, int $min_bet) {
    $template_reg_price = '/^(?!-)\d+[\.,]{0,1}\d*$/';
    
    if(!empty($price)){
      if(!check_regular_expression($template_reg_price, $price)) {
        return "Цена должна быть целым числом больше нуля";
      } else {
        if(intval($price) < $min_bet) {
          return "Цена должна быть не меньше $min_bet";
        }
        return null;
      }

    } else {
      return null;
    }

  }


  function validateDate($date, $format = "Y-m-d") {
  	date_default_timezone_set('Europe/Moscow');

  	if(!empty($date)) {
      // разбирает строку с датой согласно указанному формату. На выходе или объект Даты, или false
			$d = DateTime::createFromFormat($format, $date);
	    // преобразуем дату в строку функцией date_format() и сравниваем строки.
      // если строки с датами одинаковы, и получилось создать объект Даты, то проверка прошла успешно
      $is_correct = ($d && ($d->format($format) === $date) );


	    if($is_correct) {

	    	$day_today = date_create("now");
	    	$your_date = date_create($date);

	    	$difference = $day_today->diff($your_date);
	    	$difference_with_format = $difference->format("%R%d");

	    	$interval = intval($difference_with_format);

	    	if($interval < 1) {
	    		return "Дата окончания торгов должна быть в будущем как минимум завтра";
	    	}

	    } else {
        return "Дата должна быть в формате \"ГГГГ-ММ-ДД\"";
      }
      
	  } 
    return null;
  }
  /** 
   * Проверка валидности почты 
   *  - содержить определенные символы
   *  - существует в базе данных
   * @param link - открытое соединение с сервером
   * @param eml - адрес почты 
  */
  function validate_email($link, $eml){
    // $pattern = '/[a-zA-Z0-9_\.]+?@[a-zA-Z_]+?\.[a-zA-Z]{2,6}/';
    if(!empty($eml)){
      if($link) {
        // if(preg_match($pattern, $eml)) {
        if(filter_var($eml, FILTER_VALIDATE_EMAIL)) {
          $sql = "SELECT id FROM users WHERE email='" . $eml . "'";
          $check_dbo = mysqli_query($link, $sql);
          $check = mysqli_fetch_assoc($check_dbo);
          
          if($check) {
            return "Пользователь с такой почтой существует";
          } else {
            return null;
          }

        } else {
          return "Заполните поле правильно.";
        }
      } else {
        return "Ошибка подключения к базе данных" . mysqli_error($link);
      }
    } else {
      return null;
    }

  }

  function validate_name($name, $min_val, $max_val, $link) {
    $is_errors = validate_text_length($name, $min_val, $max_val);
    if(empty($is_errors)) {
      $sql = "SELECT user_name FROM users WHERE user_name = '" . $name . "'";
      $check_dbo = mysqli_query($link, $sql);
      $check = mysqli_fetch_assoc($check_dbo);
      if($check) {
        return "Пользователь с таким именем уже существует";
      } else {
        return null;
      }
    } else {
      return $is_errors;
    }
  }
  // расположение названий полей лота должно соответствовать их расположению в массиве с информацией
  function get_query_create_lot($user_id) {
    return "INSERT INTO lots (title, category_id, description, start_price, step, date_finish, image, user_id) VALUES (?,?,?,?,?,?,?,$user_id)";
  }

  function get_query_create_user() {
    return "INSERT INTO users (email, user_password, user_name, contacts) VALUES (?,?,?,?)";
  }


  function db_get_prepare_stmt_version($link, $sql, $data = []) {
    
    $stmt = mysqli_prepare($link, $sql);

    if(!$stmt) {

      $errorMsg = "Не удалос инициализировать подготовленное выражение: " . mysqli_error($link);
      die($errorMsg);
    }

    if($data) {
      $types = "";
      $stmt_data = [];

      foreach($data as $key => $value) {
        $type = "s";
        if(is_int($value)) {
          $type = "i";
        } else if(is_double($value)) {
          $type = "d";
        }
        $types .= $type;
        $stmt_data[] = $value;
      }

      mysqli_stmt_bind_param($stmt, $types, ...$stmt_data);

    } else {
      $errorMsg = "Информация о новом объекте отсутствует";
      die($errorMsg);
    }

    return $stmt;
  }

  function get_categories($link) {
    $sql = "SELECT * FROM categories";
    $categories_dbo = mysqli_query($link, $sql);
    return mysqli_fetch_all($categories_dbo, MYSQLI_ASSOC); 
  }

  /**
   * Summary of get_single_lot
   * @param mixed $link - установленное соединение с БД
   * @param int $id - id выбранного лота
   * @return array|bool|null - лот со всеми значениями
   */
  function get_single_lot($link, int $id) {
    $sql = "SELECT l.id as id, l.date_creation, l.title, c.category_name as category, l.description, l.image, l.start_price as price, l.date_finish, l.step, ifnull((SELECT MAX(price) FROM bets WHERE bets.lot_id = l.id), l.start_price) as last_bet FROM lots as l JOIN categories as c ON l.category_id = c.id WHERE l.id = ?";
    
    try {
      $stmt = mysqli_prepare($link, $sql);
      mysqli_stmt_bind_param($stmt, 'i', $id);
      mysqli_stmt_execute($stmt);

      $lot_dbo = mysqli_stmt_get_result($stmt);
      // $row_nmb = mysqli_num_rows($db_obj_lot);

      return mysqli_fetch_assoc($lot_dbo);

    } catch(Throwable $e) { 
      echo "Ошибка" . $e->getMessage();
      return null;
    }
		
  }

  function add_bet($link, int $price, int $user_id, int $lot_id) {
    $values = [];
    $sql = "INSERT INTO bets (price, user_id, lot_id) VALUES (?, ?, ?)";
    array_push($values, $price, $user_id,$lot_id);
    
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "iii", ...$values);
    mysqli_stmt_execute($stmt);
  }

  function get_bets($link, int $user_id) {
    $sql = "SELECT l.id as lot_id,
    l.winner_id, 
    l.date_finish as bet_finish_date, 
    l.image as lot_image, 	
    l.title as lot_title, 
    c.category_name AS lot_category, 
    u.contacts as user_contacts, 
    b.price AS bet_price, 
    b.date_create AS bet_create_date 
  FROM bets as b 
  INNER JOIN lots as l 
  ON l.id = b.lot_id 
  INNER JOIN categories as c 
  ON c.id = l.category_id 
  INNER JOIN users as u 
  ON u.id = b.user_id
  INNER JOIN (SELECT lot_id, max(price) as mprice from bets group by lot_id) as b2
  ON b.lot_id = b2.lot_id and b.price = b2.mprice 
  WHERE b.user_id = ? order by b.date_create DESC;";

    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    $res_bets_dbo = mysqli_stmt_get_result($stmt);
    return mysqli_fetch_all($res_bets_dbo, MYSQLI_ASSOC); 
      
  }

  /**
   * Summary of get_last_number
   * @param int $num - число, последнюю цифру котого нужно проверить
   * @return string - символ последней цифры
   */
  function get_last_number(int $num){
    $array = str_split(strval($num));
    return $array[count($array) - 1];
  }

  /**
   * Summary of get_bet_date
   * @param mixed $creation_date - дата создания ставки в виде строки или объекта datetime
   * @return string - возвращает строку даты в подходящем формате
   */
  function get_bet_date($creation_date) {
    $result = "";
    date_default_timezone_set('Europe/Moscow');
    if(gettype($creation_date) == "string") {
      $creation_date = date_create_from_format('Y-m-d H:i:s', $creation_date);
    }

  	$date_current = date_create('now');
    $difference_int = date_timestamp_get($date_current) - date_timestamp_get($creation_date);

    if($difference_int < 60 ) {
      $result = "Только что";
    } else if($difference_int >=60 && $difference_int < 3600) {
      $n = floor($difference_int/60);
      $cn = get_last_number($n);
      
      if($cn === "1") {
        $result = $n . " минуту назад.";
      } else if($cn === "2" || $cn === "3" || $cn === "4"){
        $result = $n . " минуты назад.";	
      } else {
        $result = $n . " минут назад.";
      }
      
    } else if($difference_int >= 3600 && $difference_int < 86400){
      $n = floor($difference_int/3600);
      
      if( ($n >= 5) && ($n <= 20) ){
        $result = $n . " часов назад";
      } else {
        $cn = get_last_number($n);
        if($cn === "1") {
        $result = $n . " час назад.";
        } else if($cn === "2" || $cn === "3" || $cn === "4"){
          $result = $n . " часа назад.";	
        } else {
          $result = $n . " часов назад.";
        }
      }
      
    } else if (86400 <= $difference_int && $difference_int < 172800){
      $result = "Вчера.";
    } else {
      $result = $creation_date->format("d.m.y в G:i");
    }
    return $result;
  }

  function get_winners($link) {
    $sql = "SELECT l.id as lot_id, b.user_id, b.date_create FROM (SELECT * FROM lots WHERE date_finish <= CURRENT_DATE) as l
    INNER JOIN bets as b ON l.id = b.lot_id
    WHERE b.date_create IN(SELECT MAX(b2.date_create) FROM bets b2 WHERE b2.lot_id = b.lot_id)";

    $winners_dbo = mysqli_query($link, $sql);
    $winners = mysqli_fetch_all($winners_dbo, MYSQLI_ASSOC);

    return $winners;
  }

  function set_winner($link, $winner_info){
    $sql = "UPDATE lots SET winner_id = ? WHERE id=?";
    $stmt = mysqli_prepare($link, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $winner_info['user_id'], $winner_info["lot_id"]);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_get_result($stmt);
    return;
  }