<?php 
  $dbhost  = 'localhost';    
  $dbbase  = 'messenger';   
  $dbchatsPosts = 'chatsposts';
  $dbuser  = 'kirill';   
  $dbpass  = '1234';   
  
  $connBase = new mysqli($dbhost, $dbuser, $dbpass, $dbbase);    
  if ($connBase->connect_error) die($connBase->connect_error);
  
  $connChaPos = new mysqli($dbhost, $dbuser, $dbpass, $dbchatsPosts);    
  if ($connChaPos->connect_error) die($connChaPos->connect_error);
    
  function createTableChaPos($name, $query)      
  {
    queryMysqlChaPos("CREATE TABLE IF NOT EXISTS $name($query)"); 
    echo "Table '$name' created or already exists.<br>"; 
  }

  function queryMysqlBase($query)
  {
    global $connBase;      
    $result = $connBase->query($query);
    if (!$result) die($connBase->error); 
    return $result;
  }
  
  function queryMysqlChaPos($query)
  {
    global $connChaPos;      
    $result = $connChaPos->query($query);
    if (!$result) die($connChaPos->error); 
    return $result;
  }
  
  function sanitizeString($var) //удаляет потенциально вредный код или теги из информации, введенной пользователем
  {
    global $connBase;
    $var = strip_tags($var); 
    $var = htmlentities($var); 
    $var = stripslashes($var);
    return $connBase->real_escape_string($var);
  }
  
  function destroySession() 
  {
	$_SESSION=array();      

    if (session_id() != "" || isset($_COOKIE[session_name()])) 
      setcookie(session_name(), '', time()-2592000, '/');      

    session_destroy();

	header ('Location: index.php'); 
	exit();
  }
  
 
	function savePhoto($saveto)	
	{	
		// переместить изображение по адресуу saveto
		move_uploaded_file($_FILES['image']['tmp_name'], $saveto);
		
		$typeok = TRUE;
		
		// проверка типа
		switch($_FILES['image']['type'])
			{
			// создаем переменную ф которой будет хранится указатель на фото
			case "image/gif": $src = imagecreatefromgif($saveto); break;
			case "image/jpeg":
			//Как обычный, так и прогрессивный JPEG-формат
			case "image/pjpeg": $src = imagecreatefromjpeg($saveto); break;
			case "image/png": $src = imagecreatefrompng($saveto); break;
			default: $typeok = FALSE; break;
			}
		
		if ($typeok)
		{
			list($w, $h) = getimagesize($saveto);
			
			//вычисляются новые размеры, которые приведут к созданию нового изображения
			//с таким же соотношением сторон, но с размерами, не превышающими 100 пикселов.
			$max = 200;
			$tw = $w;
			$th = $h;
			
			if ($w > $h && $max < $w)
				{
				$th = $max / $w * $h;
				$tw = $max;
				}
			elseif ($h > $w && $max < $h)
				{
				$tw = $max / $h * $w;
				$th = $max;
				}
			elseif ($max < $w)
				{
				$tw = $th = $max;
				}
				
			$tmp = imagecreatetruecolor($tw, $th);// создаем пустое место для нового фото, с новыми размерами
			//конвертирование фотографии из большой в маленькую
			imagecopyresampled($tmp, $src, 0, 0, 0, 0, $tw, $th, $w, $h);
			imageconvolution($tmp, array(array(-1, -1, -1),
                array(-1, 16, -1), array(-1, -1, -1)), 8, 0);
			imagejpeg($tmp, $saveto); //сохранение изображения по адрессу
			imagedestroy($tmp);
			imagedestroy($src);
		}
	}
?>