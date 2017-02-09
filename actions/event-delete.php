<?php

/* Delete event from database */
if ($_POST['clear']) {
	include '../db-config.php';
		
	$id = $_POST['id'];
	
	$prepared = $conn->prepare("DELETE FROM weeklyCalendarEvents WHERE id = ?");
	$prepared->bind_param('i', $id);

	if(!$prepared->execute()) {
		echo $prepared->error;
	}
	header('Location: /weekly-calendar');
	exit();
}
?>
