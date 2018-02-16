<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

include_once "vars.php";
include_once "functions.php";
include_once "mail.inc.php";

if(!empty($_POST) && isset($_POST['reg_user_mail']))
{
	// если пришел запрос на регистрацию	
	$reg_user_mail = trim($_POST['reg_user_mail']);
	$errorMsg  = array();
	
	if(validData('EMAIL', $reg_user_mail))
	{		
		
		// проверим существует ли пользователь с таким email
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
				WHERE user_mail = '".$reg_user_mail."'";
		$res = mysql_query($sql);
		if($res && mysql_num_rows($res) > 0)
		{
			$errorMsg[] = 'Пользователь с таким email уже существует.';
		}
		else
		{
			$rand = mt_rand(0, 1000000000); // контрольная строка
			
			// внесем в БД учетную запись нового пользователя
			$now = date('Y-m-d').' '.date('H:i:s');
			$sql = "INSERT INTO `".$_VARS['tbl_prefix']."_users`
					SET user_mail 	= '".$reg_user_mail."',
					user_login 		= '".$reg_user_mail."',
					user_pwd	 	= '".$rand."',
					user_reg_date 	= '".$now."',
					user_last_visit = '".$now."'";
			$res = mysql_query($sql);
			/*$errorMsg[] = 'Учетная запись зарегистрирована.';				
			else */
			
			if(!$res) 
			{
				$errorMsg[] = 'Ошибка создания учетной записи.';	
			}
			else
			{
				// здесь отправка письма с контрольной строкой			
				$to 	= array(
						'0'=> array('name' => 'Посетителю сайта','email' => $reg_user_mail)
				);
				$cc 	= array();
				$bcc 	= array();
				$read 	= array();
				$reply 	= array();
			
				$sender 	= 'cakeberry@cakeberry.ru';
				$senderName = 'Cakeberry';
				$subject 	= 'Регистрация на сайте cakeberry.ru';
				$message 	= '<html>
						<head></head>
						<body>
							Для подтверждения регистрации и активации своей учетной записи перейдите по ссылке:<br>
							<a href="http://'.$_SERVER['HTTP_HOST'].'/confirm/'.$rand.'/">http://'.$_SERVER['HTTP_HOST'].'/confirm/'.$rand.'/</a>
						</body>
						</html>';
						
				$obj = new sendMail($to, $sender, $subject, $message, $cc, $bcc, $senderName, $read, true, $reply, true);
				
				$reg_send = $obj->sendEmail(); // результат отправки контрольного письма
				
				if($reg_send)
				{
					//$errorMsg[] = 'Письмо с контрольной строкой отправлено на указанный email '.$reg_user_mail.'.';
				}
				else
				{
					$errorMsg[] = 'Ошибка отправки письмо с контрольной строкой.';
				}
			}		
		}		
	}
	else
	{
		$errorMsg[] = 'Неверный формат email.';
	}
	
	if(!empty($errorMsg))
	{
		foreach($errorMsg as $k)
		{
			echo $k.'<br>';
		}
	}
	else echo true;	
}