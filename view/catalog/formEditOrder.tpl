{strip}
    <div class="block">
        <div class="navbar navbar-inner block-header">
            <div class="muted pull-left"> Заказ #{$object.id} </div>
        </div>
        <div class="block-content">
            {assign var=myid value=$smarty.now|cat:"order":$object.id}
            <form class="form-horizontal" method="post" id="{$myid}" action="/admin/catalog/updateOrder">
                <fieldset>
                    <div class="flexLine">
                        {if $object}
                            <input type="hidden" name="form[id]" value="{$object.id}"/>
                        {/if}
                        <div class="flexRow">
                            <div class="control-group">
                                <label class="control-label">Покупатель </label>
                                <div class="controls">
                                    <select class="js-chosen" name="form[user]">
                                        {foreach from=$users item=item}
                                            <option value="{$item.id}" {if $item.id == $object.user}selected{/if}>#{$item.id} {$item.surname} {$item.name} {$item.secondname} </option>
                                        {/foreach}
                                    </select>
                                </div>
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
                    <h2> Позиции </h2>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th> ID </th>
                                <th> ID товара </th>
                                <th> Название товара </th>
                                <th> Кол-во </th>
                                <th> Цвет </th>
                                <th> Размер </th>
                                <th> Сумма </th>
                            </tr>
                        </thead>
                        {foreach from=$list item=item}
                            <tr class="gradeX">
                                <td> {$item.id} </td>
                                <td> {$item.stock} </td>
                                <td> {$item.name} </td>
                                <td> {$item.count} </td>
                                <td> {$item.color} </td>
                                <td> {$item.size} </td>
                                <td> {$item.summ} </td>
                            </tr>
                        {/foreach}
                        <tr class="gradeX">
                            <td colspan="6"> Итог </td>
                            <td> {$object.summ} </td>
                        </tr>
                    </table>           
                    <div class="form-actions submitLine">
                        <button type="submit" class="btn btn-primary">Сохранить</button>
                    </div>    
                </fieldset>
            </form>
        </div>
    </div>
{/strip}