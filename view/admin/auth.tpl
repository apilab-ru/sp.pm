{strip}
    <!DOCTYPE html>
    <html>
        <head>
            <title>Авторизация</title>
            <link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700&amp;subset=cyrillic" rel="stylesheet">
            <link href="/build/admin.css" rel="stylesheet">
            <script src="/build/events.js"></script>
        </head>
        <body>
            <div class="container">
                <form class="form-signin" action="/ajax/auth/login/" onsubmit="admin.auth(this,event)">
                    <h3 class="form-signin-heading">Введите данные для начала работы</h3>
                    <input type="text" class="input-block-level" placeholder="Email" name="email">
                    <input type="password" class="input-block-level" placeholder="Пароль" name="pass">
                    <button class="btn btn-large btn-primary" type="submit">Войти</button>
                </form>
            </div>
        </body>
        <script src="https://code.jquery.com/jquery-3.2.1.min.js"  integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4=" crossorigin="anonymous"></script>
        <script src="/build/angular.js"></script>
        <script src="/build/admin.js"></script>
    </html>
{/strip}