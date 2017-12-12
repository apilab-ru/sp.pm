{strip}
    <div class="tableBox">
        <div class="table-toolbar">
            <button onclick="log.clear()" class="btn btn-success"> Очистить </button>
        </div>
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
            <thead>
            <th> Дата </th>
            <th> Название </th>
            <th> Лог </th>
            </thead>
            {foreach from=$list item=item}
                <tr class="gradeX">
                    <td> {$item.time} </td>
                    <td> {$item.name} </td>
                    <td>
                        <pre>
                            {$item.log} 
                        </pre>
                    </td>
                </tr>
            {/foreach}
        </table>
    </div>
{/strip}