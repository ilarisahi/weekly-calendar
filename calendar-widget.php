<?php
    /* Shorten event description */
    function shorten($text, $length) {
        $length = abs((int)$length);
        if(strlen($text) > $length) {
            $text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
        }
        return($text);
    }

    include "db-config.php";
    date_default_timezone_set('Europe/Helsinki');
    setlocale(LC_TIME, "fi_FI");
    $now = date("Y-m-d H:i:s");

    /* Get 5 upcoming events */
    $stmt = $pdo->prepare("SELECT * FROM weeklyCalendarEvents WHERE ends > ? ORDER BY starts ASC LIMIT 5");
    if(!$stmt->execute(array($now))) {
        $result = null;
    } else {
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    echo ("<div class='calendar-upcoming'>");
    
    if ($stmt->rowCount()) {
        foreach($result as $data){
            $eventDate = date("d.m.", strtotime($data["eventDate"]));
            $starts = date("H:i", strtotime($data["starts"]));
            $ends = date("H:i", strtotime($data["ends"]));
            $desc = shorten($data["description"], 150);

            echo("<div class='calendar-upcoming-event' id='upcoming-" . $data["id"] . "'>
            <a href='/weekly-calendar/?eventId=" . $data["id"] . "' style='text-decoration: none;'>
            <span class='event-title' id='event-title-" . $data["id"] . "'>" . $data['title'] . "</span>
            <div class='upcoming-event-time-wrapper'>
                <img class='calendar-img' src='http://i.imgur.com/71LiFU3.png' alt='clock' height='20' width='20'>
                <span class='event-time'>" . $eventDate . " " . $starts . " - " . $ends . "</span>
            </div>
            <span class='upcoming-event-description'>" . $desc . "</span>
            </a>
            </div>
            <div style='border-bottom: 1px solid #e5e5e5; margin-bottom: 6px;'></div>");
        }
    } else {
        echo ("<span>No upcoming events.</span>");
    }
    echo("</div>")
?>