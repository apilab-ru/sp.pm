{strip}
    <table class="table table-striped table-bordered">
        <tr>
            <th> #ID </th>
            <th> Закупка </th>
            <th> Оформлен </th>
            <th> Изменён </th>
            <th> Сумма </th>
            <th> Статус </th>
        </tr>
        {foreach from=$list item=item}
            <tr>
                <td>
                    {$item.id}
                </td>
                <td>
                   <a href='/zakupka/{$item.purchase}/'>
                        {$item.purchase_name}
                    </a>
                </td>
                <td>
                    {$item.date|date:"d.m.Y"}
                </td>
                <td>
                    {$item.date_change|date:"d.m.Y"}
                </td>
                <td>
                    {$item.summ}руб
                </td>
                <td>
                    {$statuses[$item.status]}
                </td>
            </tr>
        {/foreach}
    </table>
    {$pagination}
{/strip}