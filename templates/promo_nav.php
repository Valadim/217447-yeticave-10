<nav class="nav">
    <ul class="nav__list container">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $cat): ?>
            <li class="promo__item promo__item--<?= esc($cat['class']) ?>">
                <a class="promo__link" href="category.php?cat_id=<?= $cat['id']; ?>"><?= esc($cat['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
