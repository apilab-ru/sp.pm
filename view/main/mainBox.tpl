{strip}
<h1 class="main-titlte"> Добро пожаловать на сайт совместных закупок </h1>
<div class='main-box'>
    <div class='main-catalog'>
        <div class='main-catalog__title-box'>
            <div class='main-catalog__yzor'></div>
            <div class='main-catalog__title'>
                <h2 class='main-catalog__title-text'> Каталог </h2>
                <div class='main-catalog__description'>
                    Выбирайте качественные товары по низким ценам
                </div>
            </div>
        </div>
        <div class='main-catalog__list'>
            {foreach from=$cats item=item}
                <a class='main-catalog__item' href='/catalog/?cats={$item.link}'>
                    <object type="image/svg+xml" data="{$item.image}" class='main-catalog__item-logo'></object>
                    <div class='main-catalog__item-title'>
                        {$item.name}
                    </div>
                </a>
            {/foreach}
        </div>
    </div>
    <div class='main-box__info'>
        <h2 class="main-info__title"> О нас </h2>
        <article class='info-article'>
            Здесь мы, обычные покупатели, объединяемся для приобретения товаров оптом у прямых поставщиков.
            <br><br>
            Так просто экономить свой бюджет, покупая одежду, обувь и другие товары для всей семьи по оптовым ценам!
        </article>
        <h2 class="main-info__title"> Узнайте больше </h2>
        <ul class='main-info-links'>
            <li class='main-info__link-item'>
                <a href='/' class='main-info__link'> Что такое совместные покупки? </a>
            </li>
            <li class='main-info__link-item'>
                <a href='/' class='main-info__link'> Правила нашего сайта </a>
            </li>
            <li class='main-info__link-item'>
                <a href='/' class='main-info__link'> Как сделать заказ? </a>
            </li>
            <li class='main-info__link-item'>
                <a href='/faq/#buy' class='main-info__link'> Как оплатить и получить заказ? </a>
            </li>
            <li class='main-info__link-item'>
                <a href='/faq/#delivery' class='main-info__link'> Подробности о доставке </a>
            </li>
        </ul>
    </div>
</div>
{/strip}