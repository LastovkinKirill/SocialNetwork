<!DOCTYPE html>
<html>
	<head>
		<title>setting data base</title>
	</head>
	<body>
		<h3>Setting up...</h3>
<?php
	// file for set up tables in DB
	require_once 'functions.php';
	
	function createTableBase($name, $query)      
	{
		queryMysqlBase("CREATE TABLE IF NOT EXISTS $name($query)"); 
		echo "Table '$name' created or already exists.<br>"; 
	}

	createTableBase('users',
		'user VARCHAR(16),
		pass VARCHAR(16),
		INDEX(user(6))');
		
	createTableBase('friends',
		'user VARCHAR(16),
		friend VARCHAR(16),
		INDEX(user(6)),
		INDEX(friend(6))');
		
	createTableBase('profiles',
		'user VARCHAR(16),
		sex CHAR(1),
		birthday DATE,
		country CHAR(20),
		about VARCHAR(4096),
		INDEX(user(6))')
?>
		<br>...done. 
	</body>
</html>