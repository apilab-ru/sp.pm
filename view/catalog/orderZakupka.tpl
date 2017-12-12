{strip}
<div class="order__zakupka">
    <div class="order__line-highlight">
        <div class="flex-line">
            <div>
                <span class='order__name'>Закупка:</span> 
                <a href="/zakupka/{$pur}/"> #{$pur} {$group.purchase.name} </a>    
            </div>
            <div style='margin-left: auto'>
                <span class='order__name'>Состояние:</span> 
                {if $group.purchase.status == 'stop'}
                    {if $mode == 'order'}
                        Включено в счёт, оплатить до <b>{strtotime("+2 day",strtotime($group.purchase.date_stop))|date:"d.m.Y"}</b>
                    {else}
                        Оформить и оплатить до <b>{strtotime("+2 day",strtotime($group.purchase.date_stop))|date:"d.m.Y"}</b>
                    {/if}
                {else}
                    {$statuses[$group.purchase.status]}
                    {if $group.purchase.date_stop}
                        <span class='order__stop-time'>СТОП: {$group.purchase.date_stop|date:"d.m.Y"}</span>
                    {/if}
                {/if}
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
                        {if $mode != 'order'}
                        <div class='order__count-change' 
                             onclick='basket.orderChange({$item.stock.id},{$item.param|default:null|json_encode}, +1, this)'> + </div>
                        {/if}
                        <input disabled type='text' class='order__count-input' value='{$item.count}'/>
                        {if $mode != 'order'}
                        <div class='order__count-change' 
                             onclick='basket.orderChange({$item.stock.id},{$item.param|default:null|json_encode}, -1, this)'> - </div>
                        {/if}
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
                {if $mode != 'order'}
                <div class='catalog__order-item-del' 
                     onclick='basket.orderDeleteItem({$item.stock.id},{$item.param|default:null|json_encode}, this);'>
                    <img src='/build/img/remove.png'/>
                    <span class='order__del-title'>
                        Удалить
                    </span>
                </div>
                {/if}
            </div>
        {/foreach}
    </div>
    <div class="order__line-highlight flex-line flex-end">
        {if $mode == 'order'}
            <a onclick='catalog.canselOrder(this, {$pur})' class="button red margin-right-auto"> Отменить </a>
        {/if}
        <div class="order__itog">
            К оплате: {$group.total}руб 
        </div>
        {if $mode=='order'}
            {if $group.purchase.status == 'stop'}
                <a href='/order/pay/{$pur}/' class="green button"> Оплатить </a>
            {/if}
        {else}
            <a onclick='catalog.createOrder(this, event, {$pur})' class="button yellow"> Оформить </a>
        {/if}
    </div>
</div>
{/strip}