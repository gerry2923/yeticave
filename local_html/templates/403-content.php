<nav class="nav">
  <ul class="nav__list container">
    <?php foreach($categories as $category): ?>  
  
    <li class="nav__item">
      <a href="all-lots.html"><?= $category["category_name"];?></a>
    </li>
    <?php endforeach;?>
  </ul>
</nav>

<section class="lot-item container">
  <h2>403 Доступ к странице запрещен.</h2>
  <p><?= $message;?></p>
</section>