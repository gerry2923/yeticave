<nav class="nav">
      <ul class="nav__list container">
        <?php foreach($categories as $category): ?>
        <li class="nav__item">
          <a href="all-lots.html"><?= $category["category_name"];?></a>
        </li>
        <?php endforeach;?>
      </ul>
    </nav>

    <div class="container">
      <section class="lots">
        <h2>Результаты поиска по запросу «<span><?= $request;?></span>»</h2>

        <ul class="lots__list">
          <?php foreach($lots as $lot): ?>
          <li class="lots__item lot">
            <div class="lot__image">
              <img src="<?= $lot['image'];?>" width="350" height="260" alt="<?php $lot['title']?>">
            </div>

            <div class="lot__info">
              <span class="lot__category"><?= $lot['category'];?></span>
              <h3 class="lot__title"><a class="text-link" href="lot.php?lot_id=<?=$lot['id']?>"><?= $lot['title'];?></a></h3>
              <div class="lot__state">
                <div class="lot__rate">
                  <span class="lot__amount"><?= $lot['start_price'];?></span>
                  <span class="lot__cost"><?= price_format($lot['start_price']); ?></span>
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
    
        <?php if($total_pages === 1):?>
          <ul class="pagination-list">
            <li class="pagination-item pagination-item-active"><a>1</a></li>
          </ul>
        <?php endif;?>

        <?php if($total_pages > 1): ?>
          <?php $next = $current_page + 1; ?>
          <?php $prev = $current_page - 1; ?> 
          <ul class="pagination-list">
            <li class="pagination-item pagination-item-prev"><a href="search.php?search=<?= $request; ?>&page=<?= $prev; ?>">Назад</a></li>
            
            <?php for($i = 1; $i > ($total_pages + 1); $i++):?>
              <li class="pagination-item <?php if($i === $current_page):?> pagination-item-active<?php endif;?>">
                <a <?php if($i !== $current_page):?>href="search.php?page=<?=$i;?>"<?php endif;?> > <?= $i; ?> </a>
              </li>
            <?php endfor;?>

            <li class="pagination-item pagination-item-next"><a href="search.php?search=<?= $request; ?>&page=<?= $next; ?>">Вперед</a></li>
          </ul>
        <?php endif;?>
    </div>