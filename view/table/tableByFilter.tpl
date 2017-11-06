{strip}
    <div class="row-fluid">
        <div class="block">
            {if $title}
                <div class="navbar navbar-inner block-header">
                    <div class="muted pull-left">{$title}</div>
                </div>
            {/if}
            <div class="block-content collapse in">
                <div class="span12">
                    <div class="table-toolbar">
                        <div class="btn-group">
                            {if $add}
                                <a onclick='apilab.addForm("{$add}");event.preventDefault()' href="{$add}">
                                    <button class="btn btn-success">Добавить <i class="icon-plus icon-white"></i></button>
                                </a>
                            {/if}
                        </div>
                        {*<div class="btn-group pull-right">
                            <button data-toggle="dropdown" class="btn dropdown-toggle">Дополнительно <span class="caret"></span></button>
                            <ul class="dropdown-menu">
                                <li><a href="#">Печать</a></li>
                            </ul>
                        </div>*}
                    </div>
                    <div class="tableBox">
                        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
                            <thead>
                                <tr>
                                    {foreach from=$labels item=label key=field}
                                        <th> 
                                            {if is_array($label)}
                                                {$label.name}
                                            {else}
                                                {$label}
                                            {/if}
                                        </th>
                                    {/foreach}
                                    {if $edit}
                                        <th width='40'> Редактировать </th>
                                        {/if}
                                    {if $delete}
                                        <th width='40'> Удалить </th>
                                    {/if}
                                    {if $curadd}
                                        <th width='40'> Добавить </th>
                                    {/if}
                                </tr>
                            </thead>
                            <tbody>
                                {foreach from=$list item=item}
                                    <tr class="gradeX">
                                        {foreach from=$labels item=label key=field}
                                            <td> 
                                                {if is_array($label)}
                                                    {if $label.type=='date'}
                                                        <nobr>
                                                            {$item[$field]|date:"d.m.Y"}
                                                        </nobr>
                                                    {/if}
                                                    {if $label.type=='datetime'}
                                                        <nobr>
                                                            {$item[$field]|date:"d.m.Y H:i"}
                                                        </nobr>
                                                    {/if}
                                                    {if $label.type=='checkbox'}
                                                        <div class="center">
                                                            <label class="itemInput">
                                                                <input type="checkbox" {if $item[$field]}checked{/if} onchange="apilab.changeCheck(this,{$item.id},'{$field}','{$label.link}')"/>
                                                                <span></span>
                                                            </label>
                                                        </div>
                                                    {/if}
                                                {else}
                                                    {$item[$field]} 
                                                {/if}
                                            </td>
                                        {/foreach}
                                        {if $edit}
                                            <td class="center">
                                                <a class="btn btn-primary" onclick='apilab.editForm("{$edit}",{$item.id});event.preventDefault()' href="{$edit}?id={$item.id}">
                                                    <i class="icon-pencil icon-white"></i>
                                                </a>
                                            </td>
                                        {/if}
                                        {if $delete}
                                            <td class="center">
                                                <a class="btn btn-danger" onclick='apilab.deleteItem(this,"{$delete}",{$item.id});event.preventDefault()' href="{$delete}?id={$item.id}">
                                                    <i class="icon-remove icon-white"></i>
                                                </a>
                                            </td>
                                        {/if}
                                        {if $curadd}
                                            <td class="center">
                                                <a class="btn btn-success" onclick='apilab.editForm("{$curadd}",{$item.id});event.preventDefault()' href="{$curadd}?id={$item.id}">
                                                     <i class="icon-plus icon-white"></i>
                                                </a>
                                            </td>
                                        {/if}
                                    </tr>
                                {/foreach}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            
            {widget 
                name  =pagination
                page  = $page
                count = $count
                limit = $limit
                link  = $link
            }               
                            
        </div>
    </div>
{/strip}