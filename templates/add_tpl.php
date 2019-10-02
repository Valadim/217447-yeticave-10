    <main>
        <?= $navigation ?>
        <form class="form form--add-lot container <?= count($errors) ? 'form--invalid' : '' ?>" action="add.php"


              method="post" enctype="multipart/form-data">
            <h2>Добавление лота</h2>
            <div class="form__container-two">
                <div class="form__item
            <?php if (isset($errors['name_lot'])): ?>
            form__item--invalid
            <?php endif; ?>">
                    <label for="name_lot">Наименование <sup>*</sup></label>
                    <input id="name_lot" type="text" name="name_lot" placeholder="Введите наименование лота"
                           value="<?= getPostVal('name_lot'); ?>">
                    <span class="form__error"><?= $errors['name_lot'] ?? ""; ?></span>
                </div>


                <div class="form__item <?= isset($errors['category_id']) ? 'form__item--invalid' : '' ?>">


                    <label for="category">Категория <sup>*</sup></label>
                    <select class="<?= $classname; ?>" id="category_id" name="category_id">
                        <option>Выберите категорию</option>
                        <?php foreach ($cats as $cat): ?>

                            <option value="<?= $cat['id'] ?>"
                                    <?php if ($cat['id'] == getPostVal('category_id')): ?>selected<?php endif; ?>>
                                <?= esc($cat['name']); ?>

                            </option>
                        <?php endforeach; ?>
                    </select>
                    <span class="form__error"><?= $errors['category_id'] ?? ""; ?></span>
                </div>
            </div>


            <div class="form__item form__item--wide <?= isset($errors['description']) ? 'form__item--invalid' : '' ?>">
                <label for="description">Описание <sup>*</sup></label>
                <textarea id="description" placeholder="Напишите описание лота" name="description"><?= getPostVal('description'); ?></textarea>
                <span class="form__error"><?= $errors['description'] ?? ""; ?></span>
            </div>


            <div class="form__item form__item--file <?= isset($errors['image']) ? 'form__item--invalid' : '' ?>">
                <label>Изображение <sup>*</sup></label>
                <div class="form__input-file">
                    <input class="visually-hidden" type="file" name="image" id="image" value="">
                    <label for="image" class="">
                        Добавить
                    </label>

                </div>
            </div>


            <div class="form__container-three">
                <div
                    class="form__item form__item--small <?= isset($errors['initial_price']) ? 'form__item--invalid' : '' ?>">
                    <label for="initial_price">Начальная цена <sup>*</sup></label>
                    <input id="initial_price" type="text" name="initial_price" placeholder="0"
                           value="<?= getPostVal('initial_price'); ?>">
                    <span class="form__error"><?= $errors['initial_price'] ?? ""; ?></span>
                </div>
                <div
                    class="form__item form__item--small <?= isset($errors['step_rate']) ? 'form__item--invalid' : '' ?>">

                    <label for="step_rate">Шаг ставки <sup>*</sup></label>
                    <input id="step_rate" type="text" name="step_rate" placeholder="0"
                           value="<?= getPostVal('step_rate'); ?>">
                    <span class="form__error"><?= $errors['step_rate'] ?? ""; ?></span>
                </div>

                <div class="form__item <?= isset($errors['expiration_date']) ? 'form__item--invalid' : '' ?>">

                    <label for="expiration_date">Дата окончания торгов <sup>*</sup></label>
                    <input class="form__input-date" id="expiration_date" type="text" name="expiration_date"
                           placeholder="Введите дату в формате ГГГГ-ММ-ДД"
                           value="<?= getPostVal('expiration_date'); ?>">
                    <span class="form__error"><?= $errors['expiration_date'] ?? ""; ?></span>
                </div>
            </div>
            <span class="form__error form__error--bottom">Пожалуйста, заполните форму корректно.<br>
            <?= $errors['file'] ?? ""; ?>
        </span>
            <button type="submit" class="button">Добавить лот</button>
        </form>


    </main>
