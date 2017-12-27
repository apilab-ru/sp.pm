{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left">Страница доставки </div>
        </div>
        <div class="block-content collapse in">
            <div class="flexLine">
                <div class="delivery-page-edit__text js-delivery-page-text">
                    <textarea id="deliveryPageText">{$art.text}</textarea>
                    <div class="form-actions submitLine">
                         <span class="btn btn-primary" onclick="delivery.savePageText()">Сохранить</span>
                    </div>
                </div>
                <div class="delivery-page-edit__steps">
                    <div class="table-toolbar">
                        <div class="btn-group">
                            <button class="btn btn-success" onclick="admin.editForm('/admin/main/editStep',0,{ struct:5 })">
                                Добавить <i class="icon-plus icon-white"></i>
                            </button>
                        </div>
                    </div>
                    <div class="dd delivery-steps-dd" id="steps">
                        <ol class="dd-list">
                            {foreach from=$steps item=item}
                            <li class="js-item dd-item dd3-item" data-id="{$item.id}">
                                <div class="dd-handle dd3-handle">Drag</div>
                                <div class="dd3-content">
                                    <div class="flexLine">
                                        <span class="dd-name">{$item.text}</span>
                                        <span class="btn btn-danger" onclick="delivery.deleteStep({$item.id}, this)">
                                            <i class="icon-remove icon-white"></i>
                                        </span>
                                        <span class="btn btn-primary" onclick="admin.editForm('/admin/main/editStep',{$item.id})">
                                            <i class="icon-pencil icon-white"></i>
                                        </span>
                                    </div>
                                </div>
                            </li>
                            {/foreach}
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        $(function(){
            events.trigger('editPageDelivery');
        });
    </script>
{/strip}