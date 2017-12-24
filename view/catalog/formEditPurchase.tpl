{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"> Закупка </div>
        </div>
        <div class="block-content">
            {assign var=myid value=$smarty.now|cat:"purchase":$object.id}
            <form class="form-horizontal" method="post" id="{$myid}">
                <fieldset>
                    <div class="flexLine">
                        {if $object}
                            <input type="hidden" name="form[id]" value="{$object.id}"/>
                        {/if}
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Название </label>
                                <div class="controls">
                                    <input type="text" name="form[name]" value="{$object.name}"/>
                                </div>
                            </div> 
                            <div class="control-group">
                                <label class="control-label">Организатор </label>
                                <div class="controls">
                                    <select name="form[user]">
                                        {foreach from=$users item=user}
                                            <option {if $object.user == $user.id}selected{/if} value="{$user.id}"> 
                                                {$user.surname} {$user.name} {$user.secondname} 
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div> 
                            <div class="control-group">
                                <label class="control-label">Активна </label>
                                <div class="controls">
                                    <label class="checkbox">
                                        <input class="checkbox__input" name="form[active]" value="1" type="checkbox" 
                                               {if $object.active}checked{/if}>
                                        <span class="checkbox__check checkbox__check-stat"></span>
                                        <span>Активировать</span>
                                    </label>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Статус </label>
                                <div class="controls">
                                    <select name="form[status]">
                                        {foreach from=$statuses item=item key=key}
                                            <option {if $object.status == $key}selected{/if} value="{$key}"> 
                                                {$item} 
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div> 
                        </div>     
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Теги </label>
                                <div class="controls">
                                    <select name="form[tags][]" multiple="true">
                                        {foreach from=$tags item=tag}
                                            <option {if $object.tags[$tag.id]}selected{/if} value="{$tag.id}"> 
                                                {$tag.name} 
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Акции </label>
                                <div class="controls">
                                    <select name="form[discounts][]" multiple="true">
                                        {foreach from=$discounts item=discount}
                                            <option {if $object.discounts[$discount.id]}selected{/if} value="{$discount.id}"> 
                                                {$discount.name}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div> 
                            <div class="control-group">
                                <label class="control-label">Дата стопа </label>
                                <div class="controls">
                                     <input type="datetime-local" name="form[date_stop]" value="{if $object.date_stop}{$object.date_stop|date:"Y-m-d\TH:i"}{/if}"/>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="flexRow">
                        <div class="control-group">
                            <label class="control-label">Описание </label>
                            <div class="controls">
                                <textarea id="editDescr" class="js-descr" name="form[description]">
                                    {$object.description}
                                </textarea>
                            </div>
                        </div> 
                    </div>
                    <h2> Опции </h2>
                    <div class="flexRow">
                        {foreach from=$options item=option}
                            <div class="control-group">
                                <label class="control-label"> {$option.name} </label>
                                <div class="controls">
                                    {if $option.type == 'price' || $option.type=='percent'}
                                        <input name="option[{$option.id}]" type="number" value="{$object.options[$option.id].value}"/>
                                        {if $option.type == 'percent'}%{/if}
                                    {/if}
                                    {if $option.type == 'radio'}
                                        <label class="checkbox">
                                            <input class="checkbox__input" name="option[{$option.id}]" value="1" type="radio" 
                                                   {if $object.options[$option.id].value === "1"}checked{/if}>
                                            <span class="checkbox__check checkbox__check-stat"></span>
                                            <span>Да</span>
                                        </label>
                                        <label class="checkbox">
                                            <input class="checkbox__input" name="option[{$option.id}]" value="0" type="radio" 
                                                   {if $object.options[$option.id].value === "0"}checked{/if}>
                                            <span class="checkbox__check checkbox__check-stat"></span>
                                            <span>Нет</span>
                                        </label>
                                        <label class="checkbox">
                                            <input class="checkbox__input" name="option[{$option.id}]" value="" type="radio" 
                                                   {if $object.options[$option.id].value === "1" && $object.options[$option.id].value === "0"}checked{/if}>
                                            <span class="checkbox__check checkbox__check-stat"></span>
                                            <span>Не выбранно</span>
                                        </label>
                                    {/if}
                                    {if $option.type=='text'}
                                        <textarea name="option[{$option.id}]" id="option{$option.id}{$smarty.now}" class="js-text-option text-option">
                                            {$object.options[$option.id].value}
                                        </textarea>
                                    {/if}
                                    {if $option.type=='files'}
                                        <div class="js-file-uploader file-uploader__box list">
                                            <ul class="js-list file-uploader__list">
                                                {foreach from=$object.options[$option.id].value item=item}
                                                    <li myid='{$item.id}' class='js-file-item'>
                                                        <span onclick='fileUploader.removeThisFile(this)' class='icon-remove icon-white remove-icon'></span>
                                                        <img src='{$item|img:"0x150x3"}'/>
                                                    </li>
                                                {/foreach}
                                            </ul>
                                            <div class="file-uploader__title" style='position: relative'>
                                                Выберите файлы
                                                <input type="file" class="file-uploader__input js-file" multiple>
                                            </div>
                                        </div>
                                    {/if}
                                </div>
                            </div> 
                        {/foreach}
                    </div>           
                    <div class="form-actions submitLine">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>    
                </fieldset>
            </form>
            <script>
                $(function(){
                    catalog.initEditPurchase('#{$myid}');
                })
            </script>
        </div>
    </div>
{/strip}