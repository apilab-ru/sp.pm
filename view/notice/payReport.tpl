Пользователь #{$user.id} {$user.surname} {$user.name} Отправил отчёт об оплате заказа #{$order.id}, закупки #{$purchase.id} {$purchase.name}<br>
Сумма заказа: {$order.summ} / Последние цифры номера карты: {$num}<br>
Страница для отметки заказа <a href='{$site}/admin/catalog/ordersTable?orderid={$order.id}'> {$site}/admin/catalog/ordersTable </a>