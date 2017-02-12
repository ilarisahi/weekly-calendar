<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>Weekly Calendar</title>
        <link rel="stylesheet", href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css", integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u", crossorigin="anonymous">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
        <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous">
        <link rel="stylesheet" href="style.css" type="text/css">
        <script src="https://code.jquery.com/jquery-3.1.1.min.js", integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8=", crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
        <script src="calendar-navigation.js"></script>
    </head>
    <body>
        <div class="container calendar-container">
            <div class="row">
                <div class="col-md-8">
                    <h1>Weekly Calendar</h1>
                    <ul class="nav nav-pills">
                        <li class="active">
                            <a href="#calendar-pill" data-toggle="tab">Calendar</a>
                        </li>
                        <li>
                            <a href="#editor-pill" data-toggle="tab">Editor</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div id="calendar-pill" class="tab-pane fade in active">
                            <?php
                                /* Initialize this week's monday and sunday */
                                date_default_timezone_set("Europe/Helsinki");
                                setlocale(LC_TIME, "fi_FI");

                                if (date("N") == 7) {
                                    $firstDay = date("Y-m-d", strtotime( "monday last week"));
                                    $lastDay = date("Y-m-d", strtotime( "sunday last week"));
                                } else {
                                    $firstDay = date("Y-m-d", strtotime( "monday this week"));
                                    $lastDay = date("Y-m-d", strtotime( "sunday this week"));
                                }
                                $monday = $firstDay . " 00:00:00";
                                $sunday = $lastDay . " 23:59:59";

                                /* Get new monday and sunday if user has event id */
                                $urlId = $_GET["eventId"];
                                include "db-config.php";
                                if (!empty($urlId)) {
                                    $prepared = $conn->prepare("SELECT eventDate FROM weeklyCalendarEvents WHERE id=?");
                                    $prepared->bind_param('i', $urlId);
                                    if(!$prepared->execute()) {
                                        echo $prepared->error;
                                        $urlResult = null;
                                    } else {
                                        $urlResult = $prepared->get_result();
                                    }
                                    
                                    if($urlResult) {
                                        $urlData = $urlResult->fetch_array(MYSQLI_ASSOC);
                                        $urlDay = $urlData["eventDate"];
                                        $urlDayNum = date("N", strtotime($urlDay));
                                        $firstDay = date("Y-m-d", strtotime($urlDay . "-" . ($urlDayNum - 1) . " days"));
                                        $lastDay = date("Y-m-d", strtotime($urlDay . "+" . (7 - $urlDayNum) . " days"));
                                        
                                        $monday = $firstDay . " 00:00:00";
                                        $sunday = $lastDay . "23:59:59";
                                    }
                                }

                                echo ("<div class='calendar-wrapper'>");
                                include 'calendar-view.php';
                                echo ("</div>");

                                if ($urlId != 0) {
                                    /* Scroll to specified event and highlight it */
                                    echo("<script>
                                            jQuery(document).ready(function() {
                                                document.getElementById('event-" . $urlId . "').scrollIntoView(true);
                                                jQuery('#event-' + " . $urlId . ").css({'background-color':'rgba(251, 255, 0, 0.3)', 'margin-left':'-10px', 'margin-right':'-10px', 'padding-left':'10px', 'padding-right':'10px'});
                                            });
                                        </script>");
                                }
                            ?>
                        </div>
                        <div id="editor-pill" class="tab-pane">
                            <?php
                                /* Populate form fields if user wants to edit event */
                                $editUrlId = $_GET["editId"];

                                if ($editUrlId){
                                    $editId = null; $editUser = null; $editTitle = null; $editLocation = null; $editDate = null; $editStarts = null; $editEnds = null; $editDesc = null;

                                    $prepared = $conn->prepare("SELECT * FROM weeklyCalendarEvents WHERE id=?");
                                    $prepared->bind_param('i', $editUrlId);
                                    if(!$prepared->execute()) {
                                        echo $prepared->error;
                                        $editResult = null;
                                    } else {
                                        $editResult = $prepared->get_result();
                                    }

                                    print($conn->error);
                                    if ($editResult) {
                                        echo("<script>
                                                jQuery(document).ready(function () {
                                                    $('.nav-pills a[href=\"#editor-pill\"]').tab('show');
                                                });
                                            </script>");
                                        $editData = $editResult->fetch_array(MYSQLI_ASSOC);
                                        $editId = $editData["id"];
                                        $editUser = $editData["user"];
                                        $editTitle = $editData["title"];
                                        $editLocation = $editData["location"];
                                        $editDate = date("Y-m-d", strtotime($editData["eventDate"]));
                                        $editStarts = date("H:i", strtotime($editData["starts"]));
                                        $editEnds = date("H:i", strtotime($editData["ends"]));
                                        $editDesc = $editData["description"];
                                    }
                                }
                            ?>
                            <form method="post" action="actions/add-event.php">
                                <input type="hidden" name="id" value="<?php print($editId); ?>">
                                <div class="form-group">
                                    <label for="user">Name</label>
                                    <input type="text" class="form-control" id="user" name="user" value="<?php print($editUser); ?>" placeholder="Enter your name">
                                </div>
                                <div class="form-group">
                                    <label for="title">Event title</label>
                                    <input type="text" class="form-control" id="title" name="title" value="<?php print($editTitle); ?>" placeholder="Enter event title">
                                </div>
                                <div class="form-group">
                                    <label for="location">Event location</label>
                                    <input type="text" class="form-control" id="location" name="location" value="<?php print($editLocation); ?>" placeholder="Enter event location">
                                </div>
                                <div class="form-group">
                                    <label for="event-date">Event date</label>
                                    <input type="date" class="form-control" id="event-date" name="event-date" value="<?php print($editDate); ?>" placeholder="yyyy-mm-dd">
                                </div>
                                <div class="form-group">
                                    <label for="starts">Starts at</label>
                                    <input type="time" class="form-control" id="starts" name="starts" value="<?php print($editStarts); ?>" placeholder="hh:mm">
                                </div>
                                <div class="form-group">
                                    <label for="ends">Ends at</label>
                                    <input type="time" class="form-control" id="ends" name="ends" value="<?php print($editEnds); ?>" placeholder="hh:mm">
                                </div>
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea class="form-control" id="description" name="description" placeholder="Enter event description"><?php print($editDesc); ?></textarea>
                                </div>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <h1>Upcoming Events</h1>
                    <?php include "calendar-widget.php" ?>
                </div>
            </div>
        </div>
    </body>
</html>