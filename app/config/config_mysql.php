<?php

return [
    'dsn'     => "mysql:localhost;dbname=project;",
    'username'        => "root",
    'password'        => "root",
    'driver_options'  => [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'UTF8'"],
    'table_prefix'    => "phpmvc_",
    'verbose' => false,
    //'debug_connect' => 'true',
];
