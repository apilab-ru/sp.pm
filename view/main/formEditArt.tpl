{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"> Редактирование статьи </div>
        </div>
        <div class="block-content">
            {assign var=myid value=$smarty.now|cat:"purchase":$object.id}
            <form class="form-horizontal" method="post" id="{$myid}" action="/admin/catalog/saveDiscount">
                <fieldset>
                    {if $object.id}
                        <input type="hidden" name="form[id]" value="{$object.id}"/>
                    {else}
                        <input type="hidden" name="form[order]" value="999"/>
                    {/if}
                    <div class='flexLine'>
                        <div class='flexRow'>
                            <div class="control-group">
                                <label class="control-label">Название </label>
                                <div class="controls">
                                    <input type="text" name="form[name]" value="{$object.name}"/>
                                </div>
                            </div> 
                        </div>
                        <div class='flexRow'>
                            <div class="control-group">
                                <label class="control-label">Ссылка </label>
                                <div class="controls">
                                    <input type="text" name="form[link]" value="{$object.link}"/>
                                </div>
                            </div> 
                        </div>
                    </div>  
                    <div class='flexLine'>
                        <div class='flexRow'>
                            <div class="control-group">
                                <label class="control-label">Родитель </label>
                                <div class="controls">
                                    <select name="form[parent]">
                                        <option value="0"> --- </option>
                                        {foreach from=$cats item=item}
                                            {if $item.id != $object.id}
                                                <option value="{$item.id}" {if $object.parent == $item.id}selected{/if}> {$item.name} </option>
                                            {/if}
                                        {/foreach}
                                    </select>
                                </div>
                            </div> 
                        </div>
                        <div class='flexRow'>
                            <div class="control-group">
                                <label class="control-label">Раздел </label>
                                <div class="controls">
                                    <input type="text" name="form[struct]" value="{$object.struct}"/>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label">Текст </label>
                        <div class="controls">
                            <textarea name='form[text]' id='{$myid}_artText'>{$object.text}</textarea>
                        </div>
                    </div>
                    <div class="form-actions submitLine">
                        <button class="btn btn-primary"> Сохранить </button>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <script>
        $(function(){
            arts.initFormEdit('#{$myid}');
        })
    </script>
{/strip}