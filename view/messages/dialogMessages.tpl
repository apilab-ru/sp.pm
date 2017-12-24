{strip}
    {foreach from=$list item=item}
        <div class='dialog__message-item {if $item.from == $user.id}dialog__from{else}dialog__to{/if} {if $item.read}readed{else}not-readed{/if}'>
            <div class="flex-line">
                <div class="dialog__message-date">{$item.date}</div>
                <div class="dialog__message-user"> 
                    {if $item.from == $user.id}
                        {$user.surname} {$user.name} {$user.secondname}
                    {else}
                        {$opponent.surname} {$opponent.name} {$opponent.secondname}
                    {/if}
                </div>
            </div>
            <div class="dialog__message-text">{$item.text}</div>
        </div>
    {/foreach}
{/strip}