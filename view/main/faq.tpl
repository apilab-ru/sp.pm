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
        </article>
    {/foreach}
{/strip}