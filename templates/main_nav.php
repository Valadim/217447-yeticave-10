<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="category.php?cat_id=<?= $cat['id']; ?>"><?= esc($cat['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
