<?php
	session_start();

	require_once 'functions.php';

	$titleStr = " - log in or sign up";

	$appname = "MySocialNetwork!";

	// выход из УЗ
	if (isset($_POST['logOut']))
	{
		destroySession();

		header ('Location: index.php');
		exit();
	}

	if (isset($_SESSION['user']))
		{
		$user = $_SESSION['user'];
		$loggedin = TRUE;
		$titleStr = " ($user)";
		}
	else $loggedin = false;

	echo "<!DOCTYPE html>\n<html><head>" .
		 "<title>$appname$titleStr</title><link rel='stylesheet'" .
		 "href='styles.css' type='text/css'>" .
		 "<script src='javascript.js'></script>" .
		 "</head>" .
		 "<body><header><div align='left'><a href='index.php'>$appname(logo)</a></div><div align='right'>";


	if ($loggedin)
		echo "<span>$user</span>" .
			 "<form method='post' action='header.php'>" .
			 "<input type='submit'  name='logOut' value='log out'></form>" .
			 "</div><nav><ul>" .
			 "<li><a href='profile.php'>My Profile</a></li>" .
		     "<li><a href='chats.php'>Chats</a></li>" .
 			 "<li><a href='friends.php'>Friends</a></li>" .
 			 "<li><a href='users.php'>Find friends</a></li>" .
			 "<li><a href='settings.php'>Settings</a></li></ul></nav></header>";
?>
