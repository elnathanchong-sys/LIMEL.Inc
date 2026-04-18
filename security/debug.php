<?php
require_once '../base.php';

$email    = 'admin@limel.com';
$password = 'Admin@1234';

$stmt = $_db->prepare("SELECT * FROM staff WHERE staffEmail = ? LIMIT 1");
$stmt->execute([$email]);
$row = $stmt->fetch();

echo '<pre>';

if (!$row) {
    echo 'NO ROW FOUND — email does not exist in staff table';
} else {
    echo 'Row found:' . PHP_EOL;
    print_r($row);
    echo PHP_EOL;
    echo 'staffPassword value: ' . $row->staffPassword . PHP_EOL;
    echo PHP_EOL;
    echo 'password_verify result: ';
    var_dump(password_verify($password, $row->staffPassword));
}

echo '</pre>';