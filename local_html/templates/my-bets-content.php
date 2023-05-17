<nav class="nav">
  <ul class="nav__list container">
    <?php
      foreach($categories as $cat):?>
        <li class="nav__item">
          <a href="all-lots.html"><?= $cat['category_name'];?></a>
      </li>
    <?php endforeach;?>
  </ul>
</nav>

<section class="rates container">
  <h2>Мои ставки</h2>
  <table class="rates__list">
    <?php foreach($rates as $rate):?>


      <?php $is_winner = ($rate['winner_id'] == $_SESSION['user_id'])? true : false;?>
      
      <?php $time = get_dt_range(DateTime::createFromFormat('Y-m-d H:i:s', $rate['bet_finish_date']));?>
      
      <?php if($time[0] == "00" && $time[1] == "00") {
        $classname = ($is_winner) ? "rates__item--win":"rates__item--end";}
      ?>

      <tr class="rates__item <?=$classname;?>">
        <td class="rates__info">
          <div class="rates__img">
            <img src="<?= $rate['lot_image'];?>" width="54" height="40" alt="<?= $rate['lot_category'];?>">
          </div>

          <div>
          <h3 class="rates__title"><a href="lot.php?lot_id=<?= $rate['lot_id'];?>"><?= $rate['lot_title'];?></a></h3>
          <!-- Если ставка выиграла, то добавить контакты пользователя -->

          <?php if($rate['winner_id'] == $_SESSION['user_id']): ?>
        
            <p><?= $rate['user_contacts'];?></p>
          <?php endif;?>
          </div>
        </td>
        <td class="rates__category">
          <?= $rate['lot_category'];?>
        </td>
        <td class="rates__timer">

        
        <?php
        
        if($time[0] === "00" && $time[1] === "00") {
          if($is_winner) {
            $classname = "timer--win";
          } else {
            $classname="timer--end";
          }
          
        } else if($time[0] === "00" && $time[1] !== "00") {
          $classname = "timer--finishing";
        } else {
          $classname = "";
        }
        
        ?>



        <!-- Если ставка выиграла, то пишем вместо времени "Ставка выиграла" -->
        <?php if($is_winner):?>
          <div class="timer <?= $classname;?>">Ставка выиграла</div>
        <?php endif;?>

        <?php if($classname === "timer--end"):?>
          <div class="timer <?=$classname;?>">Торги окончены</div>  
        <?php endif;?>

        <?php if(!$is_winner && ($classname !== "timer--end")):?>
          <div class="timer <?= $classname;?>"><?= $time[0];?>:<?=$time[1];?></div>
        <?php endif;?>

        </td>
        <td class="rates__price">
        <?= price_format($rate['bet_price']) . " ";?>
        </td>
        <td class="rates__time"> <?= get_bet_date($rate['bet_create_date']);?>
          <!-- (now - date__finish) 5 минут назад, Час назад, Вчера, в 21:30, 19.03.17 в 08:21 -->
        </td>
      </tr>
    
    <?php endforeach;?>
    
  </table>
</section>

