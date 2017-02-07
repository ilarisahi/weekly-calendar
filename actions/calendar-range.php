<?php
if ($_POST["direction"] == "previous") {
	$firstDay = date('Y-m-d', strtotime($_POST["begin"] . '- 7 days'));
	$lastDay = date('Y-m-d', strtotime($_POST["begin"] . '- 1 day'));
	$monday = $firstDay . " 00:00:00";
	$sunday = $lastDay . " 23:59:59";

	include '../calendar-view.php';
}

if ($_POST["direction"] == "now") {
	if (date('N') == 7) {
		$firstDay = date('Y-m-d', strtotime( 'monday last week'));
		$lastDay = date('Y-m-d', strtotime( 'sunday last week'));
	} else {
		$firstDay = date('Y-m-d', strtotime( 'monday this week'));
		$lastDay = date('Y-m-d', strtotime( 'sunday this week'));
	}	
	
	$monday = $firstDay . " 00:00:00";
	$sunday = $lastDay . " 23:59:59";

	include '../calendar-view.php';
}

if ($_POST["direction"] == "next") {
	$firstDay = date('Y-m-d', strtotime($_POST["begin"] . '+ 7 days'));
	$lastDay = date('Y-m-d', strtotime($_POST["begin"] . '+ 13 days'));
	$monday = $firstDay . " 00:00:00";
	$sunday = $lastDay . " 23:59:59";

	include '../calendar-view.php';
}

?>