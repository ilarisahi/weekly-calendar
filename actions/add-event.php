<?php

    /* Add new events to database
    *  Currently does not have any validation
    *  User has responsibility
    */
    if ($_POST) {
        include "../db-config.php";

        $eventId = $_POST["id"];
        $user = $_POST["user"];
        $title = $_POST["title"];
        $eventDate = $_POST["event-date"];
        $starts = $eventDate . ' ' . $_POST["starts"];
        $ends = $eventDate . ' ' . $_POST["ends"];
        $description = $_POST["description"];
        $location = $_POST["location"];
        $hashRaw = $ends + $title;

        $dateNow = date('Y.m.d H:i:s');

        if ($eventId) {
            $stmt = $pdo->prepare("UPDATE weeklyCalendarEvents SET user=?, title=?, eventDate=?, starts=?, ends=?, description=?, location=?, modified=? WHERE id = ?");
            $stmt->execute(array($user, $title, $eventDate,$starts,$ends,$description, $location, $dateNow, $eventId));
        } else {
            $stmt = $pdo->prepare("INSERT INTO weeklyCalendarEvents (hash, user, title, eventDate, starts, ends, description, location, created) VALUES (MD5(?), ?,?,?,?,?,?,?,?)");
            $stmt->execute(array($hashRaw, $user, $title, $eventDate,$starts,$ends,$description, $location, $dateNow));
            $eventId = $pdo->lastInsertId();
        }
        header("Location: /weekly-calendar/?eventId=".$eventId);
    }
?>