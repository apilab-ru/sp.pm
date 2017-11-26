{strip}
<div class='block'>
    <div class="navbar navbar-inner block-header">
        <div class="muted pull-left">Категории товаров</div>
    </div>
    <div class="table-toolbar">
        <div class="btn-group">
            <button onclick="catalog.editCat(0)" class="btn btn-success">Добавить <i class="icon-plus icon-white"></i></button>
        </div>
    </div>
    <div class='block-content collapse in flexLine'>
        <div class="dd" id="cats">
            <ol class="dd-list">
                {foreach from=$cats item=item}
                    {include file='app:main/artEditItem' item=$item}
                {/foreach}
            </ol>
        </div>
        <div class='cat__box-edit js-cat-edit'></div>
    </div>
</div>
<script>
    $(function () {
        catalog.initEditCats();
    });
</script>
{/strip}