{strip}
{foreach from=$cursor item=item name=foo}
    {if $smarty.foreach.foo.last}
        <span class="active item">{$item.name}</span>
        {else}
        <span class='item'>
            <a {*href="{$item.link}"*}>{$item.name}</a> <span class="divider">/</span>	
        </span>
    {/if}
{/foreach}
{/strip}