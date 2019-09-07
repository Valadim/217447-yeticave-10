<?php if (!$con): ?>
    <section class="promo">
        <h2 class="promo__title">Нужен стафф для катки?</h2>
        <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и
            горнолыжное снаряжение.</p>
        <ul class="promo__list">
            <!--заполните этот список из массива категорий-->
            <?php foreach ($categories as $cat): ?>
                <li class="promo__item promo__item--<?= esc($cat['class']) ?>">
                    <a class="promo__link" href="pages/all-lots.html"><?= esc($cat['name']) ?></a>
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
<?php else: ?>
    <div class="content__main-col">
        <header class="content__header">
            <h2 class="content__header-text">Ошибка Подлкючения к БД</h2>
        </header>
        <article class="gif-list">
            <p class="error"><?= $error; ?></p>
        </article>
    </div>
<?php endif; ?>

