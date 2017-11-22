{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"> Продукт закупки </div>
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
                        </div>
                        <div class='flexRow'>
                            <div class="control-group">
                                <label class="control-label">Закупка </label>
                                <div class="controls">
                                    <select name="form[purchase]">
                                        {foreach from=$purchases item=item}
                                            <option {if $object.purchase == $item.id}selected{/if} value="{$item.id}"> 
                                                #{$item.id} {$item.name}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div> 
                                    
                            <div class="control-group">
                                <label class="control-label">Категория </label>
                                <div class="controls">
                                    <select name="form[cat]">
                                        {foreach from=$cats item=item}
                                            <option {if $object.cat == $item.id}selected{/if} value="{$item.id}"> 
                                                #{$item.id} {$item.name}
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                        </div>
                       <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Цена </label>
                                <div class="controls">
                                    <input type="number" name="form[price]" value="{$object.price}"/>
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="flexRow" style='width:100%'>
                        <div class="control-group">
                            <label class="control-label">Описание </label>
                            <div class="controls">
                                <textarea id="editDescr" class="js-descr" name="form[description]">
                                    {$object.description}
                                </textarea>
                            </div>
                        </div> 
                        <div class="control-group">
                            <label class="control-label">Фото </label>
                            <div class="controls">
                                <div class="js-file-uploader file-uploader__box list">
                                    <ul class="js-list file-uploader__list">
                                        {foreach from=$object.images item=item}
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
                            </div>
                        </div>
                    </div>
                    <h2> Опции </h2>
                    <div class="control-group">
                        <label class="control-label"> Цвета </label>
                        <div class="controls">
                            <div class="js-colors cus-tlist__box">
                                {foreach from=$object.colors item=item}
                                    <div class="cust-list__item js-item">
                                        <span class='js-remove remove-icon'></span>
                                        <span class='js-name cust-list__item-name'>{$item}</span>
                                    </div>
                                {/foreach}
                                <div class="cust-list__box-add js-add-control">
                                    <input type='text' class='cust-list__input js-edit-name'/>
                                    <span class="btn btn-success js-button-add">
                                        Добавить <i class="icon-plus icon-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="control-group">
                        <label class="control-label"> Размеры </label>
                        <div class="controls">
                            <div class="js-sizes cus-tlist__box">
                                {foreach from=$object.sizes item=item}
                                    <div class="cust-list__item js-item">
                                        <span class='js-remove remove-icon'></span>
                                        <span class='js-name cust-list__item-name'>{$item}</span>
                                    </div>
                                {/foreach}
                                <div class="cust-list__box-add js-add-control">
                                    <input type='text' class='cust-list__input js-edit-name'/>
                                    <span class="btn btn-success js-button-add">
                                        Добавить <i class="icon-plus icon-white"></i>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-actions submitLine">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>    
                </fieldset>
            </form>
            <script>
                $(function(){
                    catalog.initEditStock('#{$myid}');
                })
            </script>
        </div>
    </div>
{/strip}