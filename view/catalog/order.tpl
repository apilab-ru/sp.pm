{strip}
    <div class="dostavka__art-box">
        <div class="sub-navi">
            <a class="sub-navi__item" href="/catalog/">Каталог</a> / <a class="sub-navi__act">  Покупки </a>
         </div>
        <section class="subnavigation__box">
            <a href="/order/" class="main-info__link-item main-info__link subnavigation__item {if $selected=='order'}check{/if}">
                Корзина 
            </a>
            <a href="/orders/" class="main-info__link-item main-info__link subnavigation__item {if $selected=='orders'}check{/if}"> 
                Активные заказы 
            </a>
            <a href="/orders/archive/" class="main-info__link-item main-info__link subnavigation__item {if $selected=='archive'}check{/if}"> 
                Архив покупок 
            </a>
           {*<a href="/favotite/" class="main-info__link-item main-info__link subnavigation__item"> 
                Список желаний 
            </a>*}
        </section>
        <div class="order__box">
            {$content}
        </div>
    </div>
{/strip}