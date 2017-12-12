{strip}
{if $groupped}
    <h1> Товары ждущие оформления </h1>
    {foreach from=$groupped item=group key=pur}
        {include file='app:catalog/orderZakupka' pur=$pur group=$group}
    {/foreach}
{/if}
{if $orderGroups}
    <h1> Товары ожидающие оплаты </h1>
    {foreach from=$orderGroups item=group key=pur}
        {include file='app:catalog/orderZakupka' pur=$pur group=$group mode='order'}
    {/foreach}
{/if}
{if !$groupped && !$grouppedStocks}
    В корзине пусто, самое время в неё что-нибудь <a href="/catalog/">положить</a>
{/if}
{/strip}