<li class="lots__item lot">
    <div class="lot__image">
        <img src="<?= $val["img_path"] ?>" width="350" height="260" alt="">
    </div>
    <div class="lot__info">
        <span class="lot__category"><?= esc($val['category_name']) ?></span>
        <h3 class="lot__title"><a class="text-link" href="pages/lot.html"><?= esc($val["name"]) ?></a>
        </h3>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Стартовая цена</span>
                <span class="lot__cost"><?= user_bet($val["start_price"]) ?></span>
            </div>
            <?php if (get_dt_range($val["finish_date"])[0] < 1): ?>
            <div class="lot__timer timer timer--finishing">
                <?= implode(':', get_dt_range($val["finish_date"])); ?>
            </div>
            <?php else: ?>
            <div class="lot__timer timer">
                <?= implode(':', get_dt_range($val["finish_date"])); ?>
            </div>
            <?php endif; ?>
        </div>
    </div>
</li>
