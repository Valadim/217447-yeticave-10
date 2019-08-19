<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
        горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $key => $val): ?>
            <li class="promo__item promo__item--<?= htmlspecialchars($key) ?>">
                <a class="promo__link" href="pages/all-lots.html"><?= htmlspecialchars($val) ?></a>
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
        <?php foreach ($ads as $key => $val): ?>
            <?= include_template('lot.php', ['val' => $val]); ?>
        <?php endforeach; ?>
    </ul>
</section>

