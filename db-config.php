<?php
    /* MySQL database configuration using PDO*/
    date_default_timezone_set('Europe/Helsinki');
    setlocale(LC_TIME, "fi_FI");
    $pdo = new PDO('mysql:host=localhost;dbname=database;charset=utf8', 'username', 'password');
?>