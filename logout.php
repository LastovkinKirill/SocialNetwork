<?php	
	require_once 'functions.php';
	
	session_start();
	
	if (isset($_SESSION['user']))
		{
		echo"asd";
		destroySession();

		header ('Location: index.php');  // перенаправление на нужную страницу
		exit();
		}
?>
