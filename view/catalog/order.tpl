{strip}
    <div class="dostavka__art-box">
        <div class="sub-navi">
            <a class="sub-navi__item" href="/catalog/">Каталог</a> / <a class="sub-navi__act">  Корзина </a>
         </div>
        {foreach from=$groupped item=group key=pur}
            <table class="order__table">
                <tr>
                    <td colspan="4">
                        Закупка: {$group.name}  Организатор:{$group.user} <a href="/zakupka/{$pur}/"> ссылка </a>  
                    </td> 
                </tr>
                {foreach from=$group.list item=item name=foo}
                    <tr class="order__item">
                        <td>
                            #{$smarty.foreach.foo.iteration}
                        </td>
                        <td>
                            {$item.name}
                        </td>
                        <td>
                            {foreach from=$item.img item=img}
                                <img src="{$img|img:"100x100x3"}"/>
                            {/foreach}
                        </td>
                        <td>
                            Количество: {$item.count} Цена: {$item.price}руб. <span title="{$item.sbor}">c сбором</span> <b>{$item.itog}руб.</b>
                        </td>
                    </tr>
                    {if $item.param}
                        {foreach from=$item.param item=param}
                            <tr>
                                <td></td>
                                <td colspan="3"> 
                                    {if $param.color}
                                        <span class="order__param">
                                        Цвет: {$param.color}
                                        </span>
                                    {/if}
                                    {if $param.size}
                                        <span class="order__param">
                                        Размер: {$param.size}
                                        </span>
                                    {/if}
                                </td>
                            </tr>
                        {/foreach}
                    {/if}
                {/foreach}
                <tr>
                    <td colspan="3"></td>
                    <td >
                        <div class="flex-line flex-end">
                            <div class="order__itog">
                                К оплате: {$group.total}руб 
                            </div>
                            <a href="/payment/{$pur}/" class="yellow-button"> Оплатить </a>
                        </div>
                    </td>
                </tr>
            </table>
        {/foreach}
    </div>
{/strip}