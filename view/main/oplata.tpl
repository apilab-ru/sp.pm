{strip}
    <div class='flex-line art-page'>
        <div class='pay-blockchain'>
            {foreach from=$steps item=item name=foo}
                <div class='pay-blockchain__item'>
                    {$item.text}
                </div>
                {if !$smarty.foreach.foo.last}
                    <div class='pay-blockchain__strelka'></div>
                {/if}
            {/foreach}
        </div>
        {/strip}
        <div class='article-text'>
            {$art.text}
        </div>
    </div>