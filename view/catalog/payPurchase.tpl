{strip}
    <div class='dostavka__art-box'>
        <div class="sub-navi">
            <a class="sub-navi__item" href="/catalog/">Каталог</a> / <a class="sub-navi__item" href='/order/'>  Корзина </a> / <a class='sub-navi__act'> Оплата </a>
        </div>
        {if $error}
            <div class='message error'>
                {if $error == 'list'}
                    Ошибочная ссылка! 
                    {if $order}
                        <br>Возможно вы искали <a href='/order/{$order}/'>этот заказ</a>.
                    {/if}
                    <br>Нет покупок по данной закупке или на них уже выставлен счёт, посмотреть счёта можно на <a href='/orders/'>странице</a>.
                {else}
                    Ошибка! Закупка не найдена или она не в статусе выкупа.
                {/if}
            </div>
        {else}
            <div class='flex-line subnavigation__box'>
                <div class='subnavigation__title'>
                    Оплата через:
                </div>
                <section class="subnavigation__box">
                    <a class="main-info__link-item main-info__link subnavigation__item check">
                        Зачисление на реквизиты 
                    </a>
                </section>
            </div>
            <div class='order-info__box'>
                <div>
                    <a href='/order/{$order.id}/'> Заказ #{$order.id} </a>
                </div>
                {include file='app:catalog/orderInfo'}
            </div>
        {/if}
    </div>
{/strip}