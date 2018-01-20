{strip}
    <div class="sub-navi">
       
    <div class='dostavka__art-box'>
        <a class="sub-navi__item" href="/catalog/">Каталог</a> / <a href="/orders/">  Покупки </a> / <a  class="sub-navi__act">  Заказ #{$order.id} </a>
        <p>Статус: {$statuses[$order.status]}</p>
        {include file='app:catalog/orderInfo'}
    </div>
{/strip}