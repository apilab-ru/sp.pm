<?php
/*
 * Smarty plugin
 * -------------------------------------------------------------
 * Файл:     resource.db.php
 * Тип:     resource
 * Имя:     db
 * Назначение:  Получает шаблон из базы данных
 * -------------------------------------------------------------
 */
 function smarty_resource_db_source($tpl_name, &$tpl_source, &$smarty)
{
    // выполняем обращение к базе данных для получения шаблона
    // и занесения полученного результата в в $tpl_source
    $sql = new SQL;
    $sql->query("select tpl_source
                   from my_table
                  where tpl_name='$tpl_name'");
    if ($sql->num_rows) {
        $tpl_source = $sql->record['tpl_source'];
        return true;
    } else {
        return false;
    }
}

function smarty_resource_db_timestamp($tpl_name, &$tpl_timestamp, &$smarty)
{
    // выполняем обращение к базе данных для присвоения значения $tpl_timestamp.
    $sql = new SQL;
    $sql->query("select tpl_timestamp
                   from my_table
                  where tpl_name='$tpl_name'");
    if ($sql->num_rows) {
        $tpl_timestamp = $sql->record['tpl_timestamp'];
        return true;
    } else {
        return false;
    }
}

function smarty_resource_db_secure($tpl_name, &$smarty)
{
    // предполагаем, что шаблоны безопасны
    return true;
}

function smarty_resource_db_trusted($tpl_name, &$smarty)
{
    // не используется для шаблонов
}

$smarty->register_resource("db", array("db_get_template",
    "db_get_timestamp",
    "db_get_secure",
    "db_get_trusted"));
?>