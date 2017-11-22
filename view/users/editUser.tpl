{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"> Пользователь </div>
        </div>
        <div class="block-content">
            {assign var=myid value=$smarty.now|cat:"useredit":$object.id}
            <form class="form-horizontal" method="post" action="/admin/users/save" id="{$myid}">
                <fieldset>
                    <div class="flexLine">
                        {if $object}
                            <input type="hidden" name="form[id]" value="{$object.id}"/>
                        {/if}
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Email </label>
                                <div class="controls">
                                    <input type="text" name="form[email]" value="{$object.email}"/>
                                </div>
                            </div> 
                            <div class="control-group">
                                <label class="control-label">Новый пароль </label>
                                <div class="controls">
                                    <input type="text" name="form[password]" value=""/>
                                </div>
                            </div>   
                            <div class="control-group">
                                <label class="control-label"> Имя </label>
                                <div class="controls">
                                    <input type="text" name="form[name]" value="{$object.name}"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"> Фамилия </label>
                                <div class="controls">
                                    <input type="text" name="form[surname]" value="{$object.surname}"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"> Отчество </label>
                                <div class="controls">
                                    <input type="text" name="form[secondname]" value="{$object.secondmname}"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label"> Дата Регистрации </label>
                                <div class="controls">
                                    <input type="text" name="form[date_reg]" value="{$object.date_reg|default:$smarty.now|date:"Y-m-d H:i:s"}"/>
                                </div>
                            </div>   
                        </div>     
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Тип </label>
                                <div class="controls">
                                    <select name="form[type]">
                                        {foreach from=$types item=type key=key}
                                            <option value="{$key}" {if $key==$object.type}selected{/if}> 
                                                {$type} 
                                            </option>
                                        {/foreach}
                                    </select>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">День рождения </label>
                                <div class="controls">
                                    <input class="date" type="text" name="form[birthday]" value="{$object.birthday}"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Город </label>
                                <div class="controls">
                                    <input class="date" type="text" name="form[city]" value="{$object.city}"/>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Фото </label>
                                <div class="controls">
                                    <div class="js-file-uploader file-uploader__box one" id="{$myid}">
                                        <img class="file-uploader__img js-uploader-img" src="{$object.photo|img:"0x140x3"}"/>
                                        <input type="file" class="file-uploader__input js-file">
                                        <div class="file-uploader__title">
                                            Выберите файл
                                        </div>
                                    </div>
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
                    user.initEditForm('#{$myid}');
                })
            </script>
        </div>
    </div>
{/strip}