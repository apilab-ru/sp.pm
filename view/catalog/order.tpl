{strip}
    <div class="dostavka__art-box">
        <div class="sub-navi">
            <a class="sub-navi__item" href="/catalog/">Каталог</a> / <a class="sub-navi__act">  Покупки </a>
         </div>
        <section class="subnavigation__box">
            <a href="/order/" class="main-info__link-item main-info__link subnavigation__item check">
                Корзина 
            </a>
            <a href="/archive/" class="main-info__link-item main-info__link subnavigation__item"> 
                Архив покупок 
            </a>
            <a href="/favotite/" class="main-info__link-item main-info__link subnavigation__item"> 
                Список желаний 
            </a>
            {*<a> Подписки </a>*}
        </section>
        <div class="order__box">
        {foreach from=$groupped item=group key=pur}
            <div class="order__zakupka">
                <div class="order__line-highlight">
                    <div class="flex-line">
                        <div>
                            <span class='order__name'>Закупка:</span> <a href="/zakupka/{$pur}/"> {$group.purchase.name} </a>    
                        </div>
                        <div style='margin-left: auto'>
                            <span class='order__name'>Состояние:</span> СТОП 28-11-2017
                        </div>
                    </div> 
                    <div class='order__organizator-line'>
                        <span class='order__name'>Организатор:</span><a href='/organizator/{$group.purchase.user.id}/'>{$group.purchase.user.name} {$group.purchase.user.surname}</a>
                    </div>
                </div>
                <div class='order__stock-box'>
                    {foreach from=$group.list item=item name=foo}
                        <div class="order__item">
                            <img src="{$item.stock.img[0]|img:"100x100x3"}"/>
                            <div class='order__item-params'>
                                <div> 
                                    <span class='order__name'>Название:</span>
                                    <span class='order__param-value'>{$item.stock.name}</span>
                                </div>
                                {if $item.param.color}
                                    <div>
                                        <span class='order__name'>Цвет:</span>
                                        <span class='order__param-value'>{$item.param.color}</span>
                                    </div>
                                {/if}
                                {if $item.param.size}
                                    <div>
                                        <span class='order__name'>Размер:</span>
                                        <span class='order__param-value'>{$item.param.size}</span>
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
                                    <div class='order__count-change'> + </div>
                                    <input type='text' class='order__count-input' value='{$item.count}'/>
                                    <div class='order__count-change'> - </div>
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
                            <div class='catalog__order-item-del'>
                                <img src='/build/img/remove.png'/>
                                <span class='order__del-title'>
                                    Удалить
                                </span>
                            </div>
                        </div>
                    {/foreach}
                </div>
                <div class="order__line-highlight flex-line flex-end">
                    <div class="order__itog">
                        К оплате: {$group.total}руб 
                    </div>
                    <a href="/payment/{$pur}/" class="yellow-button"> Оплатить </a>
                </div>
            </div>
        {/foreach}
        </div>
    </div>
{/strip}