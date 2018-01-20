{strip}
    <div class='order-info__box'>
        <div class="order__line-highlight">
            <div class="flex-line">
                <div>
                    <span class='order__name'>Закупка:</span> 
                    <a href="/zakupka/{$purchase.id}/"> #{$purchase.id} {$purchase.name} </a>    
                </div>
            </div> 
            <div class='order__organizator-line'>
                <span class='order__name'>Организатор:</span>
                <a href='/organizator/{$purchase.user.id}/'>{$purchase.user.name} {$purchase.user.surname}</a>
            </div>
        </div>
        <div class='order__stock-box'>
            {foreach from=$list item=item}
                <div class="order__item">
                    <img src="{$item.stock.img[0]|img:"100x100x3"}"/>
                    <div class='order__item-params'>
                        <div> 
                            <span class='order__name'>Название:</span>
                            <span class='order__param-value'>{$item.stock.name}</span>
                        </div>
                        {if $item.color}
                            <div>
                                <span class='order__name'>Цвет:</span>
                                <span class='order__param-value'>{$item.color}</span>
                            </div>
                        {/if}
                        {if $item.size}
                            <div>
                                <span class='order__name'>Размер:</span>
                                <span class='order__param-value'>{$item.size}</span>
                            </div>
                        {/if}
                        <div>
                            <span class='order__name'>Код товара:</span>
                            <span class='order__param-value'>{$item.stock.id}</span>
                        </div>
                    </div>
                    <div class='order__count-box'>
                        <div class='order__name'>Количество:</div>
                        <div class='order__count-block'>
                            <input disabled type='text' class='order__count-input' value='{$item.count}'/>
                        </div>
                    </div>
                    <div class='order__price-box'>
                        <div class='order__name'>
                            Цена:
                        </div>
                        <div class='catalog__summ-val'>
                            {$item.summ} р.
                        </div>
                    </div>
                </div>
            {/foreach}
        </div>
        <div class="order__line-highlight flex-line flex-end">
            <div class="order__itog">
                К оплате: {$order.summ}руб 
            </div>
        </div>
    </div>
    
    {if $order.status == 'check'}
    <div class='order-info__req-info'>
        Для оплаты заказа, переведите {$order.summ}руб на реквизиты {$purchase.user.requsites}. Затем заполните отчёт об оплате далее.
    </div>
    <div class='form-pay-notice' style="padding-bottom: 100px">
        <h2> Отчёт об оплате </h2>
        <form onsubmit='catalog.sendPayReport(this, event)'>
            <div class='form-line'>
                <span class="form-line__label"> Заказ # </span>
                <input type="text" name="order" class="form-input" value='{$order.id}'>
            </div>
            {*<div class='form-line'>
                <span class="form-line__label"> Сумма (Заполняется заказ или сумма) </span>
                <input type="text" name="summ" class="form-input" value='{$order.summ}'>
            </div>*}
            <div class='form-line'>
                <span class="form-line__label"> Последние 4 цифры вашей карты </span>
                <input type="text" name="num" class="form-input" value=''>
            </div>
            <div class='form-submit-line'>
                <button class="main-info__link main-info__link-item form-submit" type="submit"> Отправить </button>
            </div>
        </form>
    </div>
    {/if}
{/strip}