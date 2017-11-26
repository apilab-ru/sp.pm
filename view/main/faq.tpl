{strip}
    <h2 class='faq-top-title'>
        На этой странице собраны ответы на самые частые вопросы
    </h2>
    {foreach from=$arts item=item}
        <h2 class='faq-title'>
            <a name='{$item.link}'> {$item.name} </a>
        </h2>
        <article class='faq-text'>
            {$item.text}
            {foreach from=$item.childNodes item=it}
                <h3 class='faq-checkbox'>
                    {$it.name}
                </h3>
                <div class='faq-more'>
                    {$it.text}
                </div>
            {/foreach}
        </article>
    {/foreach}
{/strip}