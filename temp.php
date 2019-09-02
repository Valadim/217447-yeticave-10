<section class="promo">
    <h2 class="promo__title">Нужен стафф для катки?</h2>
    <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
        горнолыжное снаряжение.</p>
    <ul class="promo__list">
        <!--заполните этот список из массива категорий-->
        <?php foreach ($categories as $key => $val): ?>
            <li class="promo__item promo__item--<?= esc($key) ?>">
                <a class="promo__link" href="pages/all-lots.html"><?= esc($val) ?></a>
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
        <?php foreach ($lots as $val): ?>
            <?= include_template('lot.php', ['val' => $val]); ?>
        <?php endforeach; ?>
    </ul>
</section>

<?php if ($is_auth == 1): ?>
    <div class="user-menu__logged">
        <p><?= esc($user_name); ?></p>
        <a class="user-menu__bets" href="pages/my-bets.html">Мои ставки</a>
        <a class="user-menu__logout" href="#">Выход</a>
    </div>
<?php else: ?>
    <ul class="user-menu__list">
        <li class="user-menu__item">
            <a href="#">Регистрация</a>
        </li>
        <li class="user-menu__item">
            <a href="#">Вход</a>
        </li>
    </ul>
<?php endif; ?>

