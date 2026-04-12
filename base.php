<?php

date_default_timezone_set('Asia/Kuala_Lumpur');

$_db = new PDO('mysql:dbname=limel', 'root', '', [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
]);

