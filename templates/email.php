<!doctype html>
<html lang="Ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Поздравляем с победой!</title>
</head>
<body>
<h1>Поздравляем с победой</h1>
<p>Здравствуйте, <?= htmlspecialchars($lot_win['user_name']) ?></p>
<p>Ваша ставка для лота <a href="http://601235-yeticave-10/lot.php?id=<?= htmlspecialchars($lot_win['lot_win_id']) ?>">
        <?= htmlspecialchars($lot_win['lot_title']) ?></a> победила.</p>
<p>Перейдите по ссылке <a href="http://601235-yeticave-10/my-bets.php">мои ставки</a>,
    чтобы связаться с автором объявления</p>
<small>Интернет Аукцион "YetiCave"</small>

</body>
</html>
