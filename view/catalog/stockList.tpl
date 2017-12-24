{strip}
    <div class="p-catalog__stock-list">
        {foreach from=$list item=item}
            <div class="p-stock__item">
                <a class="js-modal" href="#stockDescription{$item.id}">
                    <img class="p-stock__img" src="{$item.img[0]|img:"145x195x3"}"/>
                </a>
                <div style="display:none" id="stockDescription{$item.id}">
                    <div class="flex-line">
                        <div class="p_stock__modal-img-list">
                            {foreach from=$item.img item=img}
                                <a href="{$img|img:"0x0x2"}" class="js-modal">
                                    <img class="p-stock__modal-img" src="{$img|img:"250x400x3"}"/>
                                </a>
                            {/foreach}
                        </div>
                        <div class="p_stock__modal-descr">{$item.description}</div>
                    </div>
                </div>
                <div class="p-stock__descr">
                    <div class="p-stock__prop">
                        {$item.name}
                    </div>
                    <div class="p-stock__prop">
                        <span class="p-stock__prop-name">
                            Цена:           
                        </span>
                        <span class="p-stock__prop-val">
                            {$item.price} руб
                        </span>
                    </div>
                    {if $org}
                    <div class="p-stock__prop">
                        <span class="p-stock__prop-name">
                            Орг.Сбор           
                        </span>
                        <span class="p-stock__prop-val">
                            {$org}%
                        </span>
                    </div>
                    {/if}
                    {if $item.sizes}
                    <div class="p-stock__prop">
                        <span class="p-stock__prop-val p-stock__select-box">
                            <span class="p-stock__prop-name p-stock__cust-prop-name">
                               Размер           
                            </span>
                            <select class="js-size custom-select" placeholder="Размер" style="width:100px;">
                                {foreach from=$item.sizes item=size}
                                    <option value="{$size}"> {$size} </option>
                                {/foreach}
                            </select>
                        </span>
                    </div>
                    {/if}
                    {if $item.colors}
                    <div class="p-stock__prop">
                        <span class="p-stock__prop-val p-stock__select-box">
                            <span class="p-stock__prop-name p-stock__cust-prop-name">
                               Цвет           
                            </span>
                            <select class="js-color custom-select" placeholder="Размер" style="width:100px;">
                                {foreach from=$item.colors item=color}
                                    <option value="{$color}"> {$color} </option>
                                {/foreach}
                            </select>
                        </span>
                    </div>
                    {/if}
                </div>
                
                <div class="p-stock__buy {if $basket->checkStock($item.id)}p-stock__bayed{/if} js-buy-but"
                     onclick="basket.toCard({$item.id}, this)">
                    <div class='p-stock__bayd-text' onclick='event.stopPropagation();'>
                        <span class='p-stock__basket-change' onclick='basket.minus({$item.id},this);'> - </span>
                        <input class='p-stock__basket-count js-current'
                               onchange='basket.change({$item.id},this);'
                               type="text" value="{$basket->getCount($item.id)}"/> 
                        <span class='p-stock__basket-change' onclick='basket.add({$item.id},this);'> + </span>
                        <span>шт</span>
                    </div>
                    <div class='p-stock__to-buy-text' >
                        Купить
                    </div>
                </div>
            </div>
        {/foreach}
    </div>
    {widget 
        name='pagination'
        count=$count
        page=$page
        limit=$limit
        func="catalog.setPageStock({$purchase})"}
{/strip}