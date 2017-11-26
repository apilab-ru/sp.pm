{strip}
    <div class="catalog__content-list">
        {foreach from=$list item=item}
            <div class="catalog__item">
                <a onclick="navigation.goLink('/zakupka/{$item.id}/',event)" class="catalog__item-name" href='/zakupka/{$item.id}/'>{$item.name}</a>
                <div class='flex-line'>
                    <div class="catalog__item-descr">
                        <a class="catalog__item-user" href="/organizator/{$item.id}/">{$item.user_name}</a> 
                        <span class="catalog__item-decor"></span>
                        <span class="catalog__item-date">{$item.date_create|date:"d.m.Y, H:i"}</span>
                    </div>
                    <div class='catalog__item-tags'>
                        {foreach from=$item.discounts  item=discount}
                            <span class="catalog__item-discount"> {$discounts[$discount].name} </span>
                        {/foreach}
                        {foreach from=$item.tags item=tag}
                            <span class="catalog__item-tag"> {$tags[$tag].name} </span>
                        {/foreach}
                    </div>
                </div>
            </div>
        {foreachelse}
            <div class="catalog__content-none">
                По вашему запросу не найдено закупок
            </div>
        {/foreach}
    </div>
{/strip}