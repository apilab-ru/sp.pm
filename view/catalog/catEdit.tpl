{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">
            {if $object.id}
               Редактирование: {$object.name}
            {else}
                Добавление категории
            {/if}
            </div>
        </div>
        <div class="block-content">
            <form class="js-form cat-edit__box">
                {if $object.id}
                    <input type="hidden" name="id" value="{$object.id}"/>
                {/if}
                <div class="control-group">
                    <label class="control-label">Название </label>
                    <div class="controls">
                        <input type="text" name="name" value="{$object.name}"/>
                    </div>
                </div> 
                <div class="control-group">
                    <label class="control-label">Ссылка </label>
                    <div class="controls">
                        <input type="text" name="link" value="{$object.link}"/>
                    </div>
                 </div>
                <div class="control-group">
                    <label class="control-label">Ссылка </label>
                    <div class="controls">
                        <div class="js-file-uploader file-uploader__box one" id="{$myid}">
                            <img class="file-uploader__img js-uploader-img" src="{$object.image|img:"80x80x3"}"/>
                            <input type="file" class="file-uploader__input js-file">
                            <div class="file-uploader__title">
                                Выберите файл
                            </div>
                        </div>
                    </div>
                </div>
                <div class="form-actions submitLine">
                    <button class="btn btn-primary"> Сохранить </button>
                </div>
            </form>
        </div>
    </div>
{/strip}