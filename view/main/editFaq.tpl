{strip}
    <div class='block'>
        <div class='navbar navbar-inner block-header'>
            <div class='muted pull-left'>  Статьи </div>
        </div>
        <div class='block-content'>
            <div class="table-toolbar">
                <div class="btn-group">
                    <button onclick="admin.editForm('/admin/main/editArt',0,{$struct})" class="btn btn-success">Добавить <i class="icon-plus icon-white"></i></button>
                </div>
            </div>
            <div class="dd arts__list-box" id='arts'>
                <ol class="dd-list">
                    {foreach from=$list item=item}
                        {include file='app:main/artEditItem' item=$item}
                    {/foreach}
                </ol>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            arts.initEditList();
        });
    </script>
{/strip}