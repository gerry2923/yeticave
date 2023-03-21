
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

    <section class="lot-item container">
      <h2><?= $lot['title'];?></h2>
      <div class="lot-item__content">
        <div class="lot-item__left">
          <div class="lot-item__image">
            <img src="<?=$lot['image'];?>" width="730" height="548" alt="Сноуборд">
          </div>
          <p class="lot-item__category">Категория: <span><?= $lot['category'];?></span></p>
          <p class="lot-item__description"><?=$lot['description'];?></p>
        </div>

        <div class="lot-item__right">

        <?php $style = empty($user) ? "style='display: none;'" : "";?>
          
        <div class="lot-item__state" <?=$style;?>>
            
          <?php 
            $time_left_arr = get_dt_range(DateTime::createFromFormat('Y-m-d H:i:s',$lot['date_finish']));
            $limit_class = "";

            if($time_left_arr[0] == "00") {
              $limit_class = "timer--finishing";
            }

            $time_left_str = $time_left_arr[0] . ":" . $time_left_arr[1]; 
          ?>

          <div class="lot-item__timer timer <?= $limit_class;?>">
            <?= $time_left_str;?>  
          </div>

          <div class="lot-item__cost-state">
            <div class="lot-item__rate">
              <span class="lot-item__amount"><?= $lot['price'];?></span>
              <span class="lot-item__cost"><?= price_format($lot['last_bet']) . " ";?></span>
            </div>

            <?php 
              $next_min_price = $lot['last_bet'] + $lot['step'];
              $next_min_price_formated = price_format($next_min_price) . " ";
            ?>

            <div class="lot-item__min-cost">Мин. ставка <span><?= $next_min_price_formated;?></span></div>
          </div>


          <form class="lot-item__form" action="lot.php?lot_id=<?= $lot['id']?>" method="post" autocomplete="off">
          <?php $errclass = !empty($errors['cost'])?"form__item--invalid":"";?>
            <p class="lot-item__form-item form__item <?=$errclass;?>">
              <label for="cost">Ваша ставка</label>
              <input id="cost" type="text" name="cost" placeholder="12 000">
              <span class="form__error"><?= $errors['cost'];?></span>
            </p>
            <button type="submit" class="button">Сделать ставку</button>
          </form>


          </div>


          <div class="history">
            <h3>История ставок (<span>10</span>)</h3>
            <table class="history__list">
              <tr class="history__item">
                <td class="history__name">Иван</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">5 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Константин</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">20 минут назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Евгений</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">Час назад</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Игорь</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 08:21</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Енакентий</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 13:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Семён</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 12:20</td>
              </tr>
              <tr class="history__item">
                <td class="history__name">Илья</td>
                <td class="history__price">10 999 р</td>
                <td class="history__time">19.03.17 в 10:20</td>
              </tr>
            </table>
          </div>
        </div>
      </div>
    </section>
 