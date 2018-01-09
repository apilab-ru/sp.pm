{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">
                Шаг
            </div>
        </div>
        <div class="block-content">
            <form class="js-form cat-edit__box" action="/admin/main/saveStep">
                {if $object.id}
                    <input type="hidden" name="id" value="{$object.id}"/>
                {/if}
                <input type="hidden" name="struct" value="{$object.struct}"/>
                <div class="control-group">
                    <label class="control-label">Текст </label>
                    <div class="controls">
                        <textarea name="text" style="width:100%;box-sizing: border-box;">{$object.text}</textarea>
                    </div>
                </div> 
                <div class="form-actions submitLine">
                    <button class="btn btn-primary"> Сохранить </button>
                </div>
            </form>
        </div>
    </div>
{/strip}