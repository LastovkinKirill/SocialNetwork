<?php

	require_once 'header.php';
		
	if (!$loggedin) die();
	
	$avatar = "usersphoto/default.jpg";

	if ($_FILES)
	{
		$saveto = "usersphoto/$user.jpg";
		savePhoto($saveto);
	}
	
	if (file_exists("usersphoto/$user.jpg"))
		 $avatar = "usersphoto/$user.jpg";
	
	echo <<<_END
			<div>
				<img src=$avatar>
				<form method='post' action='profile.php' enctype='multipart/form-data'>
				Image: <input type='file' name='image'>
				<input type='submit' value='Upload'>
				</form>
			</div>
_END;

	$userName = $sex = $maleCheck = $femaleCheck = $birthday = $curCountry = $aboutMe = "";
	
	if (isset($_POST['save']))
	{
		$sex = $_POST['sex'];
		$birthday = $_POST['birthday'];
		$curCountry = preg_replace('/\s\s+/', ' ',sanitizeString($_POST['curCountry']));
		$aboutMe = preg_replace('/\s\s+/', ' ',sanitizeString($_POST['aboutMe']));
	
		$result = queryMysqlBase("SELECT * FROM profiles WHERE user='$user'");
		
		if ($result->num_rows)
			queryMysqlBase("UPDATE profiles SET sex='$sex', birthday='$birthday', country = '$curCountry', about='$aboutMe' where user='$user'");
		else queryMysqlBase("INSERT INTO profiles VALUES('$user', '$sex', '$birthday', '$curCountry', '$aboutMe')");
	}
	
	$result = queryMysqlBase("SELECT * FROM profiles WHERE user='$user'");
	
	if ($result->num_rows)
		{
			$row = $result->fetch_array(MYSQLI_ASSOC);
			$sex = $row['sex'];
			$birthday = stripslashes($row['birthday']);
			$curCountry = stripslashes($row['country']);
			$aboutMe = stripslashes($row['about']);
			if ($sex == 'm')
				$maleCheck = "checked";
			else 
				$femaleCheck = "checked";
		}		
	
	if (isset($_POST['edit']))
		echo <<<_END
			<div>
				<form method='post' action='profile.php'>
				<p style='display:inline-block;'>Username: </p><span>$user</span><br>
				<p style='display:inline-block'>Sex: </p><input type='radio' name='sex' value='m' $maleCheck>Male
				<input type='radio'  name='sex' value='f' $femaleCheck>Female<br>
				<p style='display:inline-block'>birthday: </p><input type='date' name='birthday' value='$birthday'><br>
				<p style='display:inline-block'>Curent country: </p><input type='text' name='curCountry' value='$curCountry' maxlength = '30'><br>
				<p style='display:inline-block'>About me: </p><textarea name='aboutMe' cols='50' rows='3'>$aboutMe</textarea><br>
				<input type='submit'  name='save' value='Save'></form>
			</div>
_END;
	else		
		echo <<<_END
				<div>
					<p style='display:inline-block;'>Username: </p><span>$user</span><br>
					<p style='display:inline-block'>Sex: </p><span>$sex</span><br>
					<p style='display:inline-block'>birthday: </p><span>$birthday</span><br>
					<p style='display:inline-block'>Curent country: </p><span>$curCountry</span><br>
					<p style='display:inline-block'>About me: </p><div>$aboutMe</div><br>
					<form method='post' action='profile.php'>
					<input type='submit'  name='edit' value='Edit'></form>
				</div>
_END;

	/*
	if ($result->num_rows)
	{
		$row = $result->fetch_array(MYSQLI_ASSOC);
		$birthday = stripslashes($row['birthday']);
		$curCountry = stripslashes($row['country']);
		$aboutMe = stripslashes($row['about']);
		if ($row['sex'] == 'm')
			$maleCheck = "checked";
		else 
			$femaleCheck = "checked";
		
		echo <<<_END
			<div>
				<p style='display:inline-block;'>Nickname: </p><span>$user</span><br>
				<p style='display:inline-block'>Curent country: </p><span>$curCountry</span><br>
				<p style='display:inline-block'>languages: </p><span>$lang</span><br>
				<p style='display:inline-block'>About me: </p><div>$aboutMe</div><br>
				<form method='post' action='profile.php'>
				<input type='submit'  name='edit' value='Edit'></form>
			</div>
_END;
	}
	else
		echo <<<_END
			<div>
				<form method='post' action='profile.php'>
				<p style='display:inline-block;'>UserName: </p><span>$user</span><br>
				<p style='display:inline-block'>Sex: </p><input type='radio' name='sex' value=m' $femaleCheck $>Male
				<input type='radio'  name='sex' value='f' $femaleCheck>Female<br>
				<p style='display:inline-block'>birthday: </p><input type='date' name='lang' value='$birthday'><br>
				<p style='display:inline-block'>Curent country: </p><input type='text' name='curCountry' value='$curCountry'><br>
				<p style='display:inline-block'>About me: </p><input type='text' name='aboutMe' value='$aboutMe'><br>
				</form>
				<form method='post' action='profile.php'>
				<input type='submit'  name='save' value='Save'></form>
			</div>
_END;
		
	
	if (isset($_POST['edit']))
		echo <<<_END
			<div>
				<form method='post' action='profile.php'>
				<p style='display:inline-block;'>UserName: </p><input type='text' name='userName' value='$userName'><br>
				<p style='display:inline-block'>Sex: </p><input type='radio' name='sex' value=m' $femaleCheck $>Male
				<input type='radio'  name='sex' value='f' $femaleCheck>Female<br>
				<p style='display:inline-block'>birthday: </p><input type='date' name='lang' value='$birthday'><br>
				<p style='display:inline-block'>Curent country: </p><input type='text' name='curCountry' value='$curCountry'><br>
				<p style='display:inline-block'>About me: </p><input type='text' name='aboutMe' value='$aboutMe'><br>
				</form>
				<form method='post' action='profile.php'>
				<input type='submit'  name='save' value='Save'></form>
			</div>
_END;
		else
		{
			$nickName = sanitizeString($_POST['nickName']);
			$curCountry = sanitizeString($_POST['curCountry']);
			$lang = sanitizeString($_POST['lang']);
			$aboutMe = sanitizeString($_POST['aboutMe']);
			
			echo <<<_END
			<div>
				<p style='display:inline-block;'>Nickname: </p><span>$nickName</span><br>
				<p style='display:inline-block'>Curent country: </p><span>$curCountry</span><br>
				<p style='display:inline-block'>languages: </p><span>$lang</span><br>
				<p style='display:inline-block'>About me: </p><div>$aboutMe</div><br>
				<form method='post' action='profile.php'>
				<input type='submit'  name='edit' value='Edit'></form>
			</div>
_END;
		}
			*/


	require_once 'footer.php';
?>