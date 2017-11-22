{strip}
    <div class="tableBox">
        <table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered">
            <thead>
            <th> Дата </th>
            <th> Название </th>
            <th> Лог </th>
            </thead>
            {foreach from=$list item=item}
                <tr class="gradeX">
                    <td> {$item.date_create} </td>
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