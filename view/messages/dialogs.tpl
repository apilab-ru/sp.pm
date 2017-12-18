{strip}
    <div class='dialogs__list'>
        {foreach from=$list item=item}
            <a href="/messages/user/{$item.user}/" class='dialogs__list-item flex-line {if !$item.read}dialogs__not-read{/if}'>
                <img src="/cachephoto{$item.folder}{$item.img}_35x35x3.{$item.type}"/>
                <div class="dialogs__list-item-name">
                    {$item.surname} {$item.name} {$item.secondname}
                </div>
                <div class="dialogs__list-item-last">
                    <div class="dialogs__list-item-date">
                        {$item.date|date:"h:i d.m.y"}
                    </div>
                    <div class="dialogs__list-item-text">
                        {$item.text}
                    </div>
                </div>
            </a>
        {/foreach}
    </div>
{widget 
    name  = 'pagination'
    pages = $pages
    page  = $page
    link  = '?page'
    count = $count}
{/strip}