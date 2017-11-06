{strip}
<div class="flex-line cabinet-main-box">
    <div class="cabinet-left-row">
        <div class="user-photo__box js-user-cab-photo">
            <div class="user-photo__title js-status">
                <span class='js-status-text'>Загрузить фото</span>
                <input type='file' class='upload-input js-input'/>
                <div class='status-process js-process'></div>
            </div>
            <div class="js-photo user-photo__img" style="background-image: url('{$user.photo|img:"168x198x3"}')"></div>
            <div class="user-photo__ventex"></div>
        </div>
        <div class="cabinet-actions-box">
            <a class="main-info__link-item main-info__link">
                Посмотреть сообщения
            </a>
            <a class="main-info__link-item main-info__link">
                Написать сообщения
            </a>
            <a class="main-info__link-item main-info__link">
                Изменить пароль
            </a>
            <a class="main-info__link-item main-info__link">
                Сообщить об оплате
            </a>
        </div>
    </div>
    <div class="user-info__box">
        <div class="form-line">
            <span class="form-line__label"> Email: </span>
            <span type="text" name="email" placeholder="Email" class="form-input"> {$user.email} </span>
        </div>
        <div class="form-line">
            <span class="form-line__label"> Ф.И.О: </span>
            <span type="text" name="name" placeholder="Ф.И.О" class="form-input"> {$user.name} </span>
        </div>
        <div class="form-line">
            <span class="form-line__label"> Населённый пункт: </span>
            <span type="text" name="city" placeholder="Населённый пункт" class="form-input"> {$user.city} </span>
        </div>
        <div class="form-line">
            <span class="form-line__label"> Мобильный телефон: </span>
            <span type="text" name="phone" placeholder="обильный телефон" class="form-input"> {$user.phone} </span>
        </div>
        <div class="form-line">
            <span class="form-line__label"> Реквизиты для возврата средств: </span>
            <span type="text" name="requsites" placeholder="Реквизиты для возврата средств" class="form-input"> {$user.requsites} </span>
        </div>
        <div class="form-line">
            <span class="form-line__label"> Последние 4 цифры карты: </span>
            <span type="text" name="last_num" placeholder="Реквизиты для возврата средств" class="form-input"> {$user.last_num} </span>
        </div>
    </div>
</div>
<script>
    var initCabinet = 1;
</script>
{/strip}