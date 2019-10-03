<main>
    <?= $navigation ?>
    <section class="lot-item container">
        <h2><?= esc($lot['name']) ?></h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="<?= esc('uploads/' . $lot['img_path']) ?>" width="730" height="548" alt="Сноуборд">
                </div>
                <p class="lot-item__category">Категория: <span><?= esc($lot['category_name']) ?></span></p>
                <p class="lot-item__description"><?= esc($lot['description']) ?></p>
            </div>
            <div class="lot-item__right">

                <?php if (empty($_SESSION)): ?>
                    <div>Авторизуйтесь на сайте, чтобы узнать цену и сделать ставку</div>
                <?php else: ?>

                    <div class="lot-item__state">

                        <?php if (get_dt_range($lot["finish_date"])[0] < 1): ?>
                            <div class="lot__timer timer timer--finishing">
                                <?= implode(':', get_dt_range($lot["finish_date"])); ?>
                            </div>
                        <?php else: ?>
                            <div class="lot__timer timer">
                                <?= implode(':', get_dt_range($lot["finish_date"])); ?>
                            </div>
                        <?php endif; ?>

                        <div class="lot-item__cost-state">
                            <div class="lot-item__rate">
                                <span class="lot-item__amount">Текущая цена</span>
                                <span class="lot-item__cost"><?= user_bet($lot["start_price"]) ?></span>
                            </div>
                            <div class="lot-item__min-cost">
                                Мин. ставка <span><?= amount_formatting(min_bid($sum_bet['sum_bet'],
                                        $lot['bid_step']), 0) ?></span>
                            </div>
                        </div>

                        <?php $check_user_bet = false ?>
                        <?php if (!count($history_users_bet)) {
                            $check_user_bet = true;
                        } elseif ($history_users_bet[0]['user_id'] !== ($_SESSION['user']['id'] ?? 0)) {
                            $check_user_bet = true;
                        } ?>

                        <?php if (isset($_SESSION['user']) && ($lot['lot_user_id'] !== $_SESSION['user']['id']) && (strtotime($lot['finish_date']) > time()) && $check_user_bet): ?>

                            <form class="lot-item__form" method="post"
                                  autocomplete="off">
                                <?php $field_cost_error = isset($errors['cost']) ? 'form__item--invalid' : ''; ?>
                                <p class="lot-item__form-item form__item <?= $field_cost_error ?>">
                                    <label for="cost">Ваша ставка</label>
                                    <input id="cost" type="text" name="cost"
                                           placeholder="<?= amount_formatting(min_bid($sum_bet['sum_bet'],
                                               $lot['bid_step']), 0) ?>">
                                    <span
                                        class="form__error"><?= isset($errors['cost']) ? $errors['cost'] : '' ?></span>
                                </p>
                                <button type="submit" class="button">Сделать ставку</button>
                            </form>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>

                <?php if (isset($_SESSION['user'])): ?>
                    <div class="history">
                        <h3>История ставок (<span><?= $sum_bet['total_bet'] ?></span>)</h3>
                        <table class="history__list">
                            <?php foreach ($history_users_bet as $user_bet): ?>
                                <tr class="history__item">
                                    <td class="history__name"><?= esc($user_bet['username']) ?></td>
                                    <td class="history__price"><?= esc(amount_formatting($user_bet['bid_price'],
                                            0)) . ' р' ?></td>
                                    <td class="history__time"><?= esc(get_relative_format($user_bet['bid_date'])) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </section>
</main>
