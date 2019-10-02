<nav class="nav">
    <ul class="nav__list container">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $cat): ?>
            <li class="promo__item promo__item--<?= esc($cat['class']) ?>">
                <a class="promo__link" href="pages/all-lots.html"><?= esc($cat['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
