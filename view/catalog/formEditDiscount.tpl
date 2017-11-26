{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"> Тег </div>
        </div>
        <div class="block-content">
            <form class="form-horizontal" method="post" id="{$myid}" action="/admin/catalog/saveDiscount">
                <fieldset>
                    {if $object}
                        <input type="hidden" name="form[id]" value="{$object.id}"/>
                    {/if}
                    <div class="control-group">
                        <label class="control-label">Название </label>
                        <div class="controls">
                            <input type="text" name="form[name]" value="{$object.name}"/>
                        </div>
                    </div> 
                    <div class="control-group">
                        <label class="control-label">Описание </label>
                        <div class="controls">
                            <textarea name="form[description]">{$object.description}</textarea>
                        </div>
                    </div> 
                    <div class="form-actions submitLine">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>    
                </fieldset>
            </form>
        </div>
    </div>
{/strip}