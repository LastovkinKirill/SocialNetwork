<?php
	require_once 'functions.php';
	
	if (isset($_POST['user']))
	{
		header('Content-Type: text/xml');	
	
		$user = sanitizeString($_POST['user']);
		
		$result = queryMysqlBase("SELECT * FROM users WHERE user='$user'");

		if ($result->num_rows)
		{
			$text = "Sorry, this username is taken";
			$status = "0";
		}
		else
		{
			$text = "This username is available";
			$status = "1";
		}	
		$xmlstr = <<<XML
<?xml version='1.0' encoding='utf-8'?><client>
	<text>$text</text>
	<status>$status</status>
</client>
XML;
		// создаем xml-объект-дерево
		$sxe = new SimpleXMLElement($xmlstr);
		// записываем в файл 
		$sxe->asXML('xmlforclient.xml');
		// передаем файл целеком
		echo file_get_contents('xmlforclient.xml');
		
		/*
		if ($result->num_rows)
			echo "<span>Sorry, this username is taken</span>";
		else
			echo "<span>This username is available</span>";
		*/
	}
?>