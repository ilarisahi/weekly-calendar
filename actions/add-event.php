<?php

    /* Add new events to database
    *  Currently does not have any validation
    *  User has responsibility
    */
    if ($_POST) {
        include "../db-config.php";

        $eventId = $_POST["id"];
        $user = $conn->real_escape_string($_POST["user"]);
        $title = $conn->real_escape_string($_POST["title"]);
        $eventDate = $_POST["event-date"];
        $starts = $eventDate . ' ' . $_POST["starts"];
        $ends = $eventDate . ' ' . $_POST["ends"];
        $description = $conn->real_escape_string($_POST["description"]);
        $location = $conn->real_escape_string($_POST["location"]);
        $hashRaw = $ends + $title;

        $dateNow = date('Y.m.d H:i:s');

        if ($eventId) {
            $query = "UPDATE weeklyCalendarEvents SET user='$user', title='$title', eventDate='$eventDate', starts='$starts', ends='$ends', description='$description', location='$location', modified='$dateNow' WHERE id = $eventId";
        } else {
            $query = "INSERT INTO weeklyCalendarEvents (hash, user, title, eventDate, starts, ends, description, location, created) VALUES (MD5($hashRaw), '$user','$title','$eventDate','$starts','$ends','$description', '$location', '$dateNow')";
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