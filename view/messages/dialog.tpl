{strip}
    <div class='dialog'>
        <div class='dialog__box'>
            <div class='dialog__list'>
                <div>
                    {$content}
                </div>
            </div>
        </div>
        {if $opponent.id}
        <form class='dialog__form flex-line' onsubmit='messages.submit(this, event, {$opponent.id})'>
            <textarea class='dialog__text js-text'  placeholder='Текст сообщения'></textarea>
            <button class='main-info__link-item main-info__link subnavigation__item dialog__send'> Отправить </button>
        </form>
        {/if}
    </div>
    <script>
        window.dialogUser = '{$opponent.surname} {$opponent.name}';
        events.trigger('openDialog', '{$opponent.surname} {$opponent.name}');
    </script>
{/strip}