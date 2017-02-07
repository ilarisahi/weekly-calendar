<?php
    /* MySQL database configuration */
    date_default_timezone_set('Europe/Helsinki');
    setlocale(LC_TIME, "fi_FI");
    $conn = new mysqli("localhost", "user", "password", "database");
    $conn->set_charset("utf8");
?>