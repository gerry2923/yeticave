
<section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach($categories as $val): ?>
            <li class="promo__item promo__item--boards">
                <a class="promo__link" href="pages/all-lots.html"><?= $val['category_name']; ?></a>
            </li>
            <?php endforeach; ?>
        </ul>
    </section>
    <section class="lots">
        <div class="lots__header">
            <h2>Открытые лоты</h2>
        </div>
        <ul class="lots__list">
            <!--заполните этот список из массива с товарами-->

            <?php foreach($lots as $lot): ?>
            <li class="lots__item lot">
                <div class="lot__image">
                    <img src="<?= $lot['image'];?>" width="350" height="260" alt="">
                </div>
                <div class="lot__info">
                    <span class="lot__category"><?= $lot["category"];?></span>
                    <h3 class="lot__title">
                      <a class="text-link" href="lot.php?lot_id=<?=$lot['id']?>"><?= $lot['title']; ?></a></h3>
                    <div class="lot__state">
                        <div class="lot__rate">
                            <span class="lot__amount"><?= $lot['price'];?></span>
                            <span class="lot__cost"><?= price_format($lot['price']); ?></span>
                        </div>

                       <?php 
                                
                          $time_left_arr = get_dt_range(DateTime::createFromFormat('Y-m-d H:i:s',$lot['date_finish']));

                          $limit_class = "";

                          if($time_left_arr[0] == "00") {
                            $limit_class = "timer--finishing";
                          }

                          $time_left_str = $time_left_arr[0] . ":" . $time_left_arr[1]; 
                        ?>
                       
                        <div class="lot__timer timer <?= $limit_class;?>">
                          <?= $time_left_str;?>  
                        </div>
                    </div>
                </div>
            </li>
            <?php endforeach;?>
        </ul>
    </section>
