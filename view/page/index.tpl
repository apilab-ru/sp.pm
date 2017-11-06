{strip}
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <title>{$title}</title>
    <link href="/build/build.css?{$smarty.now}" type="text/css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Lobster|Ubuntu:400,700&amp;subset=cyrillic" rel="stylesheet">
</head>
<body>
    <header class="main-head main-col">
        <h1 class="main-title"> Бутичок </h1>
        <div class="main-head__right-col">
            <div class="auth-box">
                {if $user}
                    <a href="/cabinet/" class="button yellow"> Личный кабинет </a>
                    <a class="js-auth-out button yellow"> Выйти </a>
                {else}
                    <a class="js-reg button yellow"> Регистрация </a>
                    <a class="js-auth-in button yellow"> Вход </a>
                {/if}
            </div>
            <div class="basket-box">
                <div class="basket-count__box">
                    <div class='basket-count'>12</div>
                </div>
                <div class="basket-img"></div>
            </div>
        </div>
    </header>
    <div class='auth-form__box'>
        <div class="reg-auth js-form-reg-auth">
            <div class="reg-auth-close">X</div>
            <div class="menu-list">
                <div class="menu-item" act="reg">
                    <a>Регистрация</a>
                </div>
                <div class="menu-item menu-checked" act="auth">
                    <a>Вход</a>
                </div>
            </div>
            <div class='req-auth__box'>
                <form class="reg-form req-auth__item" act="reg" onsubmit="auth.reg(this,event)">
                    <div class="form-line">
                        <span class="form-line__label"> Email: </span>
                        <input type="text" name="email" placeholder="Email" class="form-input">
                    </div>
                    <div class="form-line">
                        <span class="form-line__label"> Ф.И.О: </span>
                        <input type="text" name="name" placeholder="Ф.И.О" class="form-input">
                    </div>
                    <div class="form-line">
                        <span class="form-line__label"> Пароль: </span>
                        <input type="password" name="password" placeholder="Пароль" class="form-input">
                    </div>
                    <div class="error form-line__error"></div>
                    <div class="form-submit-line">
                        <button class="main-info__link main-info__link-item form-submit" type="submit"> Зарегистрироваться </button>
                    </div>
                </form>
                <form class="auth-form req-auth__item checked" act="auth" onsubmit="auth.auth(this,event)">
                    <div class="form-line">
                        <span class="form-line__label"> Email: </span>
                        <input type="text" name="email" placeholder="Email" class="form-input">
                    </div>
                    <div class="form-line">
                        <span class="form-line__label"> Пароль: </span>
                        <input type="password" name="password" placeholder="Пароль" class="form-input">
                    </div>
                    <div class="error form-line__error"></div>
                    <div class="form-submit-line">
                        <button class="main-info__link main-info__link-item form-submit" type="submit"> Войти </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <section class='menu-box main-col'>
        <ul class='menu-list'>
            {foreach from=$menu item=item}
            <li class='menu-item {if $item.checked}menu-checked{/if}'>
                <a href='/{$item.link}{if $item.link}/{/if}'> {$item.name} </a>
            </li>
            {/foreach}
        </ul>
    </section>
    <section class='content main-col'>
        {$content}
    </section>
    <footer></footer>
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
    <script src="/build/build.js?{$smarty.now}"></script> 
</body>
{/strip}