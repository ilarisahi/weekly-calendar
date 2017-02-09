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
            $prepared = $conn->prepare("UPDATE weeklyCalendarEvents SET user=?, title=?, eventDate=?, starts=?, ends=?, description=?, location=?, modified=? WHERE id = ?);
            $prepared->bind_param("ssssssssi", $user, $title, $eventDate,$starts,$ends,$description, $location, $dateNow, $eventId);
            $prepared->execute();
        } else {
            $prepared = $conn->prepare("INSERT INTO weeklyCalendarEvents (hash, user, title, eventDate, starts, ends, description, location, created) VALUES (MD5(?), ?,?,?,?,?,?,?,?)");
            $prepared->bind_param("sssssssss", $hashRaw, $user, $title, $eventDate,$starts,$ends,$description, $location, $dateNow);
            $prepared->execute();
        }
        $result = $conn->query($query);

        if (!$result) {
            print($conn->error);
        } else {
            $data = $conn->insert_id;
            header("Location: /weekly-calendar/?eventId=".$data);
        }    
    }
?>