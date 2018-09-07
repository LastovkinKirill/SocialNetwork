<?php
	require_once 'header.php';
	
	echo <<<_END
<script>
	status
	
	function checkUserName(user)
	{	
		if (user.value == '')
		{
			document.getElementById('info').innerHTML = ''
			return
		}

		params = "user=" + user.value
		request = new ajaxRequest()
		request.open("POST", "checkuser.php", true)
		request.setRequestHeader("Content-type","application/x-www-form-urlencoded")
	
		request.onreadystatechange = function()
		{
			if (this.readyState == 4)
				if (this.status == 200)
					if (this.responseText != null)
					{
						text = this.responseXML.getElementsByTagName('text')[0].childNodes[0].nodeValue
						status = this.responseXML.getElementsByTagName('status')[0].childNodes[0].nodeValue
						document.getElementById('info').innerHTML = text
					}
		}
		request.send(params)
	}
	
	function validateLog(form)
	{
		if (form.userL.value == "" || form.passL.value == "")
		{
			document.getElementById('userLog').placeholder = "please, fill all fields"
			document.getElementById('passLog').placeholder = "please, fill all fields"
			return false
		}
		return true
	}
	
	function validateSign(form)
	{
		if (form.userS.value == "" || form.passS.value == "")
		{
			document.getElementById('info').innerHTML = "please, fill all fields"
			return false
		}
			
		if (status == "0")
			return false
		
		return true
	}	
</script>
_END;
	
	$userL = $passL = $infoL = $error = "";
	$userS = $passS = $infoS = "";
	
	// выход из УЗ
	if (isset($_POST['logOut']))
	{
		destroySession();

		header ('Location: index.php');
		exit();
	}
	
	// аунтификация выш должна быть чем регистрация
	if (isset($_POST['userL']))
		{
		$userL = sanitizeString($_POST['userL']);
		$passL = sanitizeString($_POST['passL']);
		
		if ($userL == "" || $passL == "")
			{
			$infoL = "Please, enter this field";
			}
		else
			{
			$result = queryMySQLBase("SELECT user,pass FROM users WHERE user='$userL' AND pass='$passL'");
			if ($result->num_rows == 0)
				{
				$error = "Username/Password invalid";
				}
			else
				{
				$_SESSION['user'] = $userL;
				$_SESSION['pass'] = $passL;
				
				header ('Location: profile.php');
				exit();
				}
			}
		}
		
	// регестрация
	if (isset($_POST['userS']))
		{
			$userS = sanitizeString($_POST['userS']);
			$passS = sanitizeString($_POST['passS']);
			
			if ($userS == "" || $passS == "")
			{
				$infoS= "please, fill all fields";
			}
			else
			{
				$result = queryMysqlBase("SELECT * FROM users WHERE user='$userS'");
			
				if ($result->num_rows)
					$infoS = "Sorry, this username is taken";
				else
					{
					queryMysqlBase("INSERT INTO users VALUES('$userS','$passS')");
					
					$_SESSION['user'] = $userS;
					$_SESSION['pass'] = $passS;
					
					header ('Location: profile.php'); 
					exit();
					}
			}
		}
	
	if ($loggedin)
	{
		header ('Location: profile.php'); 
		exit();
	}
	else	//value = user для того чтобы пользователь при неправильной тправки данных непереписывал
	{
		echo <<<_END
				<form method='post' action='index.php'
				onSubmit='return validateLog(this)'>
				<span>$error</span><span>Username</span>
				<input id = 'userLog' type='text' maxlength='16' name='userL'
				value='$userL' placeholder='$infoL'><br>
				<span>Password</span>
				<input id = 'passLog' type='password' maxlength='16' name='passL'
				value='$passL' placeholder='$infoL'><br>
				<input type='submit' value='Log in'>
				</form></div></header>		 
_END;
		
		echo"<div align='center'><span>Create an account</span>" .
			"<form method='post' action='index.php'" .
			"onSubmit='return validateSign(this)'>" .
			"<input type='text' maxlength='16' name='userS'" .
			"value='$userS' placeholder='Username'" .
			"onBlur='checkUserName(this)'><span id='info'>$infoS</span><br>" .
			"<input type='password' maxlength='16' name='passS'" .
			"value='$passS' placeholder='Password' ><br>" .
			"<input type='submit' value='Sign up'></form><div>";
	}

	require_once 'footer.php';
	
?>