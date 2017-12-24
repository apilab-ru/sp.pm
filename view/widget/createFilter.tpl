{strip}
    <form method="get" onsubmit='admin.setFilter(this, event)'>
        <div class='line'>
            <div class='name'> Сортировка </div>
            <div class='value flexLine'>
                <div class='flexRow'>
                    <select name='order'>
                        {foreach from=$sort key=key item=name}
                            <option value='{$key}' {if $filter.order==$key}selected{/if}> {$name} </option>
                        {/foreach}
                    </select>
                </div>
                <div class='flexRow boxSortType'>
                    <label class="itemInput">
                        <input type="radio" name="order_type" {if $filter.order_type!='DESC'}checked{/if} value="ASC"/>
                        <span> По возрастанию </span>
                    </label>
                    <label class="itemInput">
                        <input type="radio" name="order_type" {if $filter.order_type=='DESC'}checked{/if} value="DESC"/>
                        <span> По убыванию </span>
                    </label>    
                </div>    
            </div>
        </div>
        {foreach from=$opt item=item key=key}
           <div class='line'>
                <div class='name'> {$item.name} </div>
                <div class='value'>
                    {if $item.type == 'select'}
                        <select name='{$key}{if $item.multiple}[]{/if}' {if $item.multiple}multiple='true'{/if}>
                            <option value='0'> -- Все -- </option>
                            {foreach from=$item.list item=it}
                                <option value='{$it.id}' {if $it.id|inArray:$filter[$key]}selected{/if}>#{$it.id} {$it.name}</option>
                            {/foreach}
                        </select>
                    {/if}
                    {if $item.type == 'text'}
                        <input type='text' name='{$key}' value='{$filter[$key]}'/>
                    {/if}
                </div>
            </div> 
        {/foreach}
        {*<div class='line'>
            <div class='name'> Сотрудники </div>
            <div class='value'>
                <select name='employees[]' multiple='true'>
                    {foreach from=$employees item=item}
                        <option value='{$item.id}' {if $item.id|inArray:$filter.employees}selected{/if}>{$item.surname} {$item.name} {$item.secondname}</option>
                    {/foreach}
                </select>
            </div>
        </div>
        <div class='line'>
            <div class='name'> Временной отрезок </div>
            <div class="value">
                <input type="text" name="date_start" class="datetime" value="{$filter.date_start|date:"d.m.Y H:i"}" placeholder="От"/>
                <input type="text" name="date_end" class="datetime" value="{$filter.date_end|date:"d.m.Y H:i"}" placeholder="До"/>
            </div>
        </div>
        *}
        <div class='line'>
            <button  class="btn btn-primary">Применить</button>
        </div>
    </form>
{/strip}