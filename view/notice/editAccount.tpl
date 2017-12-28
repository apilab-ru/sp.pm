{strip}
<div class="block">
    <div class="navbar navbar-inner block-header">
        <div class="muted pull-left">Аккаунт почты </div>
    </div>
    <div class="block-content collapse in">
        <form class="" onsubmit='notice.saveNotice(this, event)'>
            {if $object.id}
                <input type="hidden" name="id" value="{$object.id}"/>
            {/if}
            <div class="control-group">
                <label class="control-label">Сервер </label>
                <div class="controls">
                    <input type="text" name="host" value="{$object.host}"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Логин </label>
                <div class="controls">
                    <input type="text" name="host" value="{$object.login}"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Пасс </label>
                <div class="controls">
                    <input type="text" name="pass" value="{$object.pass}"/>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label">Защита </label>
                <div class="controls">
                    <select name="secure">
                        <option {if $object.secure == 'ssl'}selected{/if} value="ssl"> SSL </option>
                        <option {if $object.secure == 'tls'}selected{/if} value="tls"> TLS </option>
                    </select>
                </div>
            </div> 
            <div class="control-group">
                <label class="control-label">Порт </label>
                <div class="controls">
                    <input type="text" name="port" value="{$object.port}"/>
                </div>
            </div>
            <div class="form-actions submitLine">
                <button class="btn btn-primary"> Сохранить </button>
            </div>
        </form>
    </div>
</div>
{/strip}