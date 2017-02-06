<?php
echo ("<div class='calendar-content'>
        <div class='calendar-navigation'>
            <button class='btn btn-primary' style='float: left;' value='previous' name='previous' onClick='navigate(\"" . $monday . "\",\"previous\")'><i class='fa fa-backward' aria-hidden='true'></i></button>
            <button class='btn btn-primary' style='float: right;'value='next'  name='next' onClick='navigate(\"" . $monday . "\",\"next\")'><i class='fa fa-forward' aria-hidden='true'></i></button>
            <button class='btn btn-primary' style='margin: 0 auto; display: inherit;' value='now' name='now' onClick='navigate(\"" . $monday . "\",\"now\")'>THIS WEEK</button>
        </div>
        <div class='calendar-title'>");

    $beginMonth = date('F', strtotime($monday));
    $endMonth = date('F', strtotime($sunday));
    $beginMonthInt = intval(date('m', strtotime($monday)))-1;
    $endMonthInt = intval(date('m', strtotime($sunday)))-1;
    $months = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $beginYear = date('Y', strtotime($monday));
    $endYear = date('Y', strtotime($sunday));

    if ($beginMonth == $endMonth) {
        echo (date('d.', strtotime($monday)) . " - " . date('d.', strtotime($sunday)). " ". $months[$endMonthInt]. " " . date('Y', strtotime($sunday)));
    } else if ($beginMonth != $endMonth && $beginYear != $endYear) {
        echo (date('d.', strtotime($monday)). " ". $months[$beginMonthInt]. " " . date('Y', strtotime($monday)) . " - " . date('d.', strtotime($sunday)). " ". $months[$endMonthInt]. " " . date('Y', strtotime($sunday)));
    } else if ($beginMonth != $endMonth && $beginYear == $endYear) {
        echo (date('d.', strtotime($monday)). " ". $months[$beginMonthInt]. " - " . date('d.', strtotime($sunday)). " ". $months[$endMonthInt]. " " . date('Y', strtotime($sunday)));
    }

    echo("</div>
        <div class='calendar-week'>");
        
    include 'db-config.php';

    $weekDays = array('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday');

    foreach ($weekDays as $day) {
        echo("<div class='day " . $day . "'>
            <div class='day-name'>" . $day . "</div>
            <div class='date'>" . date('d.m.', strtotime($firstDay)) . "</div>");
        
        $query  = "SELECT * FROM weeklyCalendarEvents WHERE eventDate='" . $firstDay . "' ORDER BY starts ASC";
        $result = $conn->query($query);
        
        if ($result->num_rows) {
            echo("<div class='events-wrapper'>");
            while ($data = $result->fetch_array(MYSQLI_ASSOC)) {
                echo("<div class='event' id='event-" . $data['id'] . "'>
                    <span class='event-title'>" . $data['title'] . "</span>
                    <span class='event-description'>" . $data['description'] . "</span>
                    <div class='event-time-wrapper'>
                    <img class='event-img' src='http://i.imgur.com/71LiFU3.png' alt='clock' height='20' width='20'>
                    <span class='event-time'>" . date('H:i', strtotime($data['starts'])) . " - " . date('H:i', strtotime($data['ends'])) . "</span>
                    </div>");
                if ($data['location'] != "") {
                    echo("<div class='event-location-wrapper'>
                        <img class='event-img' src='http://i.imgur.com/r8uVuwd.png' alt='pin' height='22' width='22'>
                        <span class='event-location'>" . $data['location'] . "</span>
                        </div>");
                }
                echo("<span class='event-owner'>" . $data['user'] . "</span>");	
                echo ("<div class='event-button-wrapper'>
                    <a class='event_button btn btn-primary' href='?editId=" . $data['id'] . "'><i class='fa fa-pencil' aria-hidden='true'></i></a>
                    <form style='' action='actions/event-delete.php' method='post'>
                    <input type='hidden' name='id' value='" . $data['id'] . "'>
                    <button name='clear' value='clear' class='btn btn-primary'><i class='fa fa-trash' aria-hidden='true'></i></button>
                    </form>
                    </div>");
                
                
                echo("</div>");
            }
            echo("</div>");
        }
        echo("</div>");
        
        $firstDay = date('Y-m-d H:i:s', strtotime($firstDay . ' +1 day'));
    }
    echo ("</div>
        </div>");
?>