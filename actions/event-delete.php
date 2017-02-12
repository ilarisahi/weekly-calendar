<?php

/* Delete event from database */
if ($_POST['clear']) {
	include '../db-config.php';
		
	$id = $_POST['id'];
	
	$stmt = $pdo->prepare("DELETE FROM weeklyCalendarEvents WHERE id = ?");

	if(!$stmt->execute(array($id))) {
	}
	header('Location: /weekly-calendar');
	exit();
}
?>
