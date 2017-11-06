<?php

function smarty_function_widget($params)
{
    $name = $params['name'];
    unset($params['name']);
    return \app\app::getWidget()->$name($params);
}
