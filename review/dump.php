<?php
require_once('init.php');

	session_start();

	if (!isset($_SESSION['user_id']))
	{
		header('location: login.php');
		exit();
	}
	
	$dal = new DataAccessLayer();
	$schols = $dal->GetFinalScoring();

	foreach ($schols as $row) {
		print implode("\t", $row) . "\n";
	}
?>
