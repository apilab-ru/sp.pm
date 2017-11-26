<li class="js-item dd-item dd3-item" data-id="{$item.id}">
    <div class="dd-handle dd3-handle">Drag</div>
    <div class="dd3-content">
        <div class="flexLine">
            <span class="dd-name">{$item.name}</span>
            <span class="btn btn-danger" onclick="catalog.deleteCat({$item.id}, this)">
                <i class="icon-remove icon-white"></i>
            </span>
            <span class="btn btn-primary" onclick="catalog.editCat({$item.id})">
                <i class="icon-pencil icon-white"></i>
            </span>
        </div>
    </div>
    {if $item.childNodes}
        <ol class="dd-list">
            {foreach from=$item.childNodes item=it}
                {include file='app:catalog/catsEditChild' item=$it}
            {/foreach}
        </ol>
    {/if}
</li>