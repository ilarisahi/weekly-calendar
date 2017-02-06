<?php
if ($_POST['clear']) {
	include '../db-config.php';
		
	$id = $_POST['id'];
	$id = $conn->real_escape_string($id);
	
	$sql = "DELETE FROM weeklyCalendarEvents WHERE id = '" . $id . "'";
	$result = $conn->query($sql) or die($conn->error);
	header('Location: /weekly-calendar');
	exit();
}
?>
