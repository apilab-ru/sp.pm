{strip}
    <div class="zakupka">
        <div class="zakupka-info">
            <div class="zakupka__left-row">
                 <div class="sub-navi">
                    <a class="sub-navi__item" href="/catalog/">Каталог</a> / <a class="sub-navi__act"> Закупка #{$purchase.id} {$purchase.name}</a>
                </div>
                <h1 class="art-title">{$purchase.name}</h1>
                <div class="art__after-title">
                    <span>Организатор</span> <span class="catalog__item-user">{$organizator.name}</span>
                    <span class="catalog__item-decor"></span> <span>{$purchase.date_create}</span>
                    {if $purchase.date_stop}
                        Стоп: <span class="catalog__item-decor"></span> <span>{$purchase.date_stop}</span>
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
                    {/foreach}
                </div>
                
                <div class='p-already-ordered'> 
                    Уже заказано на: 0 руб (0 шт.) 
                </div>
                
                <a target="_blank" href='/photos/stock/2017/11/sizes.jpg' class='p-conditions__name js-modal'>
                    <div>Размерная сетка:</div>
                    <img src='/cachephoto/stock/2017/11/sizes_100x100x2s.jpg'/>
                </a>
            </div>
            <div class="zakupka__right-row">
                <img class="organizator__photo-img" src="{$organizator.photo|img:"168x198x3"}"/>
                <p> {$organizator.name} {$organizator.surname} </p>
                <a class="main-info__link-item main-info__link">
                    Профиль организатора
                </a>
                {if $user}
                <a class="main-info__link-item main-info__link">
                    Написать сообщение
                </a>
                <label class="checkbox">
                    <input class="checkbox__input" type="checkbox" 
                           {if $isFavorite}checked="true"{/if}
                           onchange="catalog.setPurchaseFavorite({$purchase.id}, this)"/>
                    <span class="checkbox__check checkbox__check-stat"></span>
                    <span>Подписаться на закупку</span>
                </label>
                {/if}
            </div>
        </div>
    </div>
    
    <div class="c-title"> Каталог </div>
    <div class="c-box">
        <div class="c-left">
            <div class="c-top-filter">
                
            </div>
        </div>
        <div class="c-right">
            <div class="c-filter__box">
                <div class="c-filter__title">
                    Категории
                </div>
                <label class="checkbox c-filter__check-item">
                    <span class="c-filter__check-name"> Все категории </span>
                    <input type="checkbox" class="checkbox__input js-cat" name="cats" value="0"/>
                    <span class="c-filter__check-value checkbox__check-stat"></span>
                </label>
                {foreach from=$cats item=cat}
                <label class="checkbox c-filter__check-item">
                    <span class="c-filter__check-name"> {$cat.name} </span>
                    <input type="checkbox" class="checkbox__input js-cat" name="cats" value="{$cat}"/>
                    <span class="c-filter__check-value checkbox__check-stat"></span>
                </label>
                {/foreach}
            </div>
        </div>
    </div>            
{/strip}