{strip}
    <div class="zakupka">
        <div class="zakupka-info">
            <div class="zakupka__left-row">
                <div class="sub-navi">
                    <a onclick="navigation.goMenu('/catalog/',event)" class="sub-navi__item" href="/catalog/">Каталог</a> 
                    &nbsp;/ <a class="sub-navi__act"> Закупка #{$purchase.id} {$purchase.name}</a>
                </div>
                <h1 class="art-title">{$purchase.name}</h1>
                <div class="art__after-title">
                    <span>Организатор</span> <span class="catalog__item-user">{$organizator.name}</span>
                    <span class="catalog__item-decor"></span> <span>{$purchase.date_create|date:"Y-m-d"}</span>
                    {if $purchase.date_stop && $purchase.date_stop!='0000-00-00'}
                        &nbsp;&nbsp;&nbsp;Стоп: <span class="catalog__item-decor"></span> <span>{$purchase.date_stop}</span>
                    {/if}
                </div>
                
                {if $discounts}
                    <div class="p-discount__list">
                        <div class="p-discount__title">Акции:</div>
                        {foreach from=$discounts item=discount}
                            <div class="p-discount__item">
                                {$discount.name}
                            </div>
                        {/foreach}
                    </div>
                {/if}
                
                <div class="purchase-description">
                    {$purchase.description}
                </div>
                <div class="p-conditions">
                    <div class="p-conditions__title"> Условия закупки: </div>
                    {foreach from=$options item=option}
                        {if $option.key != 'sizes-map' && $option.key != 'sizes-table'}
                            <div class="p-conditions__item">
                                <span class="p-conditions__name"> {$option.name}: </span>
                                <span class="p-conditions__value"> 
                                    {if $option.type == 'radio'}
                                        {if $option.value}Да{else}Нет{/if}
                                    {else}
                                        {$option.value}
                                    {/if}
                                    {if $option.type == 'price'}
                                    &nbsp;руб 
                                    {/if}
                                    {if $option.type == 'percent'}
                                    &nbsp;% 
                                    {/if}
                                </span>
                            </div>
                        {/if}
                    {/foreach}
                </div>
                
                <div class='p-already-ordered'> 
                    Уже заказано на: {$total.summ|number_format :2:".":" "} руб. ({$total.count} шт.) 
                </div>
                
                {if $options[9].value || $options[10].value}
                    <br>
                    <div><a>Размерная сетка:</a></div>
                    <div class='flex-line'>
                    {foreach from=$options[9].value item=img}
                        <a target="_blank" href='{$img|img:"0x0x3"}' class='p-conditions__name js-modal'>
                            <img src='{$img|img:"100x100x2"}'/>
                        </a>
                    {/foreach}
                    {if $options[10].value}
                        <a href='#size-table' class='button yellow js-modal' style="margin-top:10px">
                             Таблица размеров
                        </a>
                        <div id="size-table" style="display:none">
                           <div class="zakupka__table-sises">
                           {$options[10].value}
                           </div>
                        </div>
                    {/if}
                    </div>
                {/if}
            </div>
            <div class="zakupka__right-row">
                <img class="organizator__photo-img" src="{$organizator.photo|img:"168x198x3"}"/>
                <p> {$organizator.name} {$organizator.surname} </p>
                <a class="main-info__link-item main-info__link" href="/organizator/{$organizator.id}/">
                    Профиль организатора
                </a>
                {if $user}
                    <a href="/messages/user/{$organizator.id}/" class="main-info__link-item main-info__link">
                        Написать сообщение
                    </a>
                    {if $purchase.providerInfo}
                    <p> {$purchase.providerInfo.name} </p>
                    <label class="checkbox">
                        <input class="checkbox__input" type="checkbox" 
                               {if $isFavorite}checked="true"{/if}
                               onchange="catalog.setPurchaseFavorite({$purchase.provider}, this)"/>
                        <span class="checkbox__check checkbox__check-stat"></span>
                        <span>Подписаться на закупку</span>
                    </label>
                    {/if}
                {/if}
            </div>
        </div>
    </div>
    
    <div class="c-title"> Каталог </div>
    <div class="c-box">
        <div class="c-left">
            <form class="c-top-filter js-catalog-filter" onsubmit="catalog.purchaseReload();event.preventDefault();">
                <div class="c-top-filter__range">
                    <span class="c-top-filter__title"> Цена от/до: </span>
                    <input name="price_from" class="c-top-filter__range-value"/>
                    <span class="long-defis"></span>
                    <input name="price_to" class="c-top-filter__range-value"/>
                </div>
                <button class="button yellow"> Искать </button>
            </form>
            <div class="p-catalog__box js-catalog-content"></div>
        </div>
        <div class="c-right">
            <div class="c-filter__box js-catalog-cats">
                <div class="c-filter__title">
                    Категории
                </div>
                <label class="checkbox c-filter__check-item">
                    <span class="c-filter__check-name"> Все категории </span>
                    <input type="checkbox" class="checkbox__input js-cat-all" name="cats" value="0" checked onchange="catalog.changeStockCat(this)"/>
                    <span class="c-filter__check-value checkbox__check-stat"></span>
                </label>
                {foreach from=$cats item=cat}
                <label class="checkbox c-filter__check-item">
                    <span class="c-filter__check-name"> {$cat.name} </span>
                    <input type="checkbox" class="checkbox__input js-cat" name="cats" value="{$cat.id}" onchange="catalog.changeStockCat(this)"/>
                    <span class="c-filter__check-value checkbox__check-stat"></span>
                </label>
                {/foreach}
            </div>
        </div>
    </div>
            
    <script>
        var initPurchase = {$purchase.id};
        if('catalog' in window){
            catalog.purchaseInit();
        }
    </script>
{/strip}