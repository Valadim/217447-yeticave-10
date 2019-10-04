<main>
    <?= $navigation ?>
    <div class="container">
        <section class="lots">
            <?php if ($lots): ?>
                <h2>Все лоты в категории <span>«<?= $category_name ?>»</span></h2>
                <ul class="lots__list">
                    <?php foreach ($lots as $lot) : ?>
                        <li class="lots__item lot">
                            <div class="lot__image">
                                <img src="<?= esc($lot['url']) ?>" width="350" height="260" alt="<?= esc($lot['name']) ?>">
                            </div>
                            <div class="lot__info">
                                <span class="lot__category"><?= esc($lot['category']) ?></span>
                                <h3 class="lot__title"><a class="text-link" href="lot.php?id=<?= $lot['id'] ?>"><?= esc($lot['name']) ?></a></h3>
                                <div class="lot__state">
                                    <div class="lot__rate">
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= formatPrice(esc($lot['price'])) ?></span>
                                    </div>
                                    <?php $expiration = getTimeUntil(esc($lot['expiration'])); ?>
                                    <div class="lot__timer timer <?= $expiration['hours'] === '00' ? 'timer--finishing' : '' ?>">
                                        <?= $expiration['hours'] . ':' . $expiration['minutes'] ?>
                                    </div>
                                </div>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            <?php else: ?>
                <h2>Активные лоты в категории <span>«<?= $category_name ?>»</span> не найдены.</h2>
            <?php endif; ?>
        </section>
        <?php if ($pages_count > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <a href="category.php?category=<?= $category ?>&page=<?= ($cur_page > 1) ? $cur_page - 1 : 1 ?>">Назад</a>
                </li>
                <?php foreach ($pages as $page): ?>
                    <li class="pagination-item <?= ((int)$page === $cur_page) ? 'pagination-item-active' : '' ?>">
                        <a href="category.php?category=<?= $category ?>&page=<?= $page ?>"><?= $page ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                    <a href="category.php?category=<?= $category ?>&page=<?= ($cur_page < count($pages)) ? $cur_page + 1 : $cur_page ?>">Вперед</a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</main>



<main>
    <?= $navigation ?>

    <div class="container">
        <section class="lots">
            <?php $category = !empty($category) ? $category : '' ?>
            <h2>Все лоты в категории <span>«<?= $category_name ?>»</span></h2>
            <ul class="lots__list">
                <?php foreach ($lots as $lot): ?>
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="<?= 'uploads/' . $lot["img_path"] ?>"
                                 width="350"
                                 height="260"
                                 alt="Фото: <?= esc($lot['name']) ?>">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category"><?= esc($lot['category_name']) ?></span>
                            <h3 class="lot__title"><a class="text-link"
                                                      href="lot.php?id=<?= $lot['id'] ?>"><?= esc($lot['name']) ?></a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <?php if ($lot['max_bid'] === null): ?>
                                        <span class="lot__amount">Стартовая цена</span>
                                        <span class="lot__cost"><?= user_bet(esc($lot['start_price'])) ?></span>
                                    <?php elseif ($lot['max_bid'] !== null): ?>
                                        <span class="lot__amount"><?= $lot['bid_count'] ?> <?= get_noun_plural_form((int)$lot['bid_count'],
                                                'ставка', 'ставки', 'ставок') ?></span>
                                        <span class="lot__cost"><?= user_bet(esc($lot['max_bid'])) ?></span>
                                    <?php endif; ?>
                                </div>

                                <?php $get_time = stop_time($lot['finish_date']) ?>
                                <div class="lot__timer timer <?php if ($get_time[1] < '01'): ?>timer--finishing<?php endif; ?>">
                                    <?= $get_time[1] . ':' . $get_time[2] ?>
                                </div>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        </section>

        <?php if ($pages_count > 1): ?>
            <ul class="pagination-list">
                <li class="pagination-item pagination-item-prev">
                    <a href="category.php?search=<?= $category ?>&page=<?= ($cur_page > 1) ? $cur_page - 1 : 1 ?>">Назад</a>
                </li>
                <?php foreach ($pages as $page): ?>
                    <li class="pagination-item <?= ((int)$page === $cur_page) ? 'pagination-item-active' : '' ?>">
                        <a href="category.php?search=<?= $category ?>&page=<?= $page ?>"><?= $page ?></a>
                    </li>
                <?php endforeach; ?>
                <li class="pagination-item pagination-item-next">
                    <a href="category.php?search=<?= $category ?>&page=<?= ($cur_page < count($pages)) ? $cur_page + 1 : $cur_page ?>">Вперед</a>
                </li>
            </ul>
        <?php endif; ?>
    </div>
</main>

