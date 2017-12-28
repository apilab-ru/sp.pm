{strip}
    <div class="flex-line cabinet-main-box">
        <div class="cabinet-left-row">
            <div class="user-photo__box js-user-cab-photo">
                <div class="js-photo user-photo__img" style="background-image: url('{$user.photo|img:"168x198x3"}')"></div>
                <div class="user-photo__ventex"></div>
            </div>
            <div class="cabinet-actions-box">
                <a class="main-info__link-item main-info__link" href="/messages/user/{$user.id}/">
                    Написать сообщение
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
                <span class="form-line__label"> Мобильный телефон: </span>
                <span type="text" name="phone" placeholder="обильный телефон" class="form-input"> {$user.phone} </span>
            </div>
            <div class="form-line">
                {$user.description}
            </div>
        </div>
    </div>
{/strip}