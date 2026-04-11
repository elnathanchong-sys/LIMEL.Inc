<?php

date_default_timezone_set('Asia/Kuala_Lumpur');

$_db = new PDO('mysql:dbname=limel', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

html_file($key, $accept = '', $attr = '');
get_file($key);
save_photo($f, $folder, $width = 200, $height = 200);
html_number($key, $min = '', $max = '', $step = '', $attr = '');
is_money($value);