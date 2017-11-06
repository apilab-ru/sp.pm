<?php

error_reporting(1);
ini_set('mysql.connect_timeout', 100000);
date_default_timezone_set('Europe/Samara');

return array(
    'db' => array(
        'user'  => 'root',
        'pass'  => '',
        'host'  => 'localhost',
        'table' => 'sp'
    )
);