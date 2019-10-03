<main>
    <?= $navigation ?>
    <section class="rates container">
        <h2>Мои ставки</h2>
        <table class="rates__list">
            <?php foreach ($my_bets as $my_bet): ?>
                <tr class="rates__item <?= get_status_user_bet($my_bet['finish_date'], $my_bet['winner_id']) ?>">
                    <td class="rates__info">
                        <div class="rates__img">
                            <img src="uploads/<?= esc($my_bet['img_path']) ?>"
                                 width="54" height="40" alt="Крепления">
                        </div>
                        <div>
                            <h3 class="rates__title"><a
                                    href="lot.php?id=<?= $my_bet['lot_id'] ?>"><?= esc($my_bet['lot_name']) ?></a></h3>
                            <?php if ($my_bet['winner_id'] === $_SESSION['user']['id']): ?>
                                <p><?= esc($my_bet['user_contacts']) ?></p>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td class="rates__category">
                        <?= esc($my_bet['category_name']) ?>
                    </td>
                    <td class="rates__timer">
                        <?php if ((strtotime($my_bet['finish_date']) < time()) && ($my_bet['winner_id'] === null)): ?>
                            <div class="timer timer--end">Торги окончены</div>

                        <?php elseif (strtotime($my_bet['finish_date']) > time() && ($my_bet['winner_id'] === null)): ?>
                            <?php $get_time = stop_time($my_bet['finish_date']) ?>
                            <div class="timer <?php if ($get_time[1] < '01'): ?>timer--finishing<?php endif; ?>">
                                <?= $get_time[1] . ':' . $get_time[2] ?>
                            </div>

                        <?php elseif ($my_bet['winner_id'] === $_SESSION['user']['id']): ?>
                            <div class="timer timer--win">Ставка выиграла</div>
                        <?php endif; ?>
                    </td>
                    <td class="rates__price">
                        <?= user_bet(esc($my_bet['max_my_bet'])) ?>
                    </td>
                    <td class="rates__time">
                        <?= get_relative_format($my_bet['bid_date']) ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>
