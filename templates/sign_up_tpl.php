<main>

    <?= $navigation ?>

    <form class="form container <?= count($errors) ? 'form--invalid' : '' ?>" action="sign_up.php" method="post" autocomplete="off"> <!-- form
    --invalid -->
        <h2>Регистрация нового аккаунта</h2>

        <div class="form__item <?= isset($errors['email']) ? 'form__item--invalid' : '' ?>"> <!-- form__item--invalid -->
            <label for="email">E-mail <sup>*</sup></label>
            <input id="email" type="text" name="email" value="<?= getPostVal('email'); ?>" placeholder="Введите e-mail">
            <span class="form__error"><?= $errors['email'] ?? ""; ?></span>
        </div>

        <div class="form__item <?= isset($errors['password']) ? 'form__item--invalid' : '' ?>">
            <label for="password">Пароль <sup>*</sup></label>
            <input id="password" type="password" name="password" value="<?= getPostVal('password'); ?>" placeholder="Введите пароль">
            <span class="form__error"><?= $errors['password'] ?? ""; ?></span>
        </div>

        <div class="form__item <?= isset($errors['name']) ? 'form__item--invalid' : '' ?>">
            <label for="name">Имя <sup>*</sup></label>
            <input id="name" type="text" name="name" value="<?= getPostVal('name'); ?>" placeholder="Введите имя">
            <span class="form__error"><?= $errors['name'] ?? ""; ?></span>
        </div>

        <div class="form__item <?= isset($errors['message']) ? 'form__item--invalid' : '' ?>">
            <label for="message">Контактные данные <sup>*</sup></label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться"><?= getPostVal('message'); ?></textarea>
            <span class="form__error"><?= $errors['message'] ?? ""; ?></span>
        </div>

        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="#">Уже есть аккаунт</a>
    </form>

</main>
