<nav class="nav">
    <ul class="nav__list container">
        <?php foreach ($categories as $cat): ?>
            <li class="nav__item">
                <a href="<?= esc($cat['class']) ?>"><?= esc($cat['name']) ?></a>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
