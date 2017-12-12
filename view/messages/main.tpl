{strip}
<div class="flex-line dostavka__art-box">
    <div class="messages__submenu">
        <div class="sub-navi">
            <a class="sub-navi__item" href="/messages/dialogs/">Диалоги</a>
            {if $navigation=='user'} / <a class="sub-navi__act">  {$user.surname} {$user.name} {$user.secondname} </a> {/if}
            {if $navigation=='notices'} / <a class="sub-navi__act"> Уведомления  </a> {/if}
        </div>
        <a href="/messages/dialogs/" class="main-info__link-item main-info__link subnavigation__item {if $navigation=='dialogs'}check{/if}">
            Диалоги
        </a>
        <a href="/messages/notices/" class="main-info__link-item main-info__link subnavigation__item {if $navigation=='notices'}check{/if}">
            Уведомления
        </a>
        {*<a href="/messages/notice/" class="main-info__link-item main-info__link">
            
        </a>*}
    </div>
    <div class="messages__box">
        {$content}
    </div>
</div>
{/strip}