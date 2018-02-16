<?
include_once "blocks/vars.php";
include_once "blocks/mail.inc.php";

// если пришла форма авторизации
if(!empty($_POST) && 
	isset($_POST['auth_user_mail']) && 
	isset($_POST['auth_user_pwd']))
{
	$auth_user_mail = trim($_POST['auth_user_mail']);
	$auth_user_pwd  = trim($_POST['auth_user_pwd']);
	$errorMsg  = array();
	
	// ищем в БД запись с указанными данными
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
			WHERE user_mail = '".$auth_user_mail."'
			AND user_pwd = '".$auth_user_pwd."'
			AND user_register = '1'
			AND user_block = '0'
			LIMIT 0,1";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		// авторизация прошла успешно
		$row = mysql_fetch_array($res);
		$_SESSION['user_id'] = $row['id'];	
		$_SESSION['user_card'] = $row['user_card'];

		// здесь обновим запись в БД (дата последнего посещения сайта)
		$sql = "UPDATE `".$_VARS['tbl_prefix']."_users`
				SET user_last_visit = '".date('Y-m-d')." ".date('H:m:s')."'
				WHERE id = ".$_SESSION['user_id'];
		$res = mysql_query($sql);		
	}
	else
	{
		// авторизация не прошла (возврат формы)
		$autoOpen   = "true";
		$errorMsg[] = $arrMsg['badAuth'];
	}
	//$errorMsg[] = $arrMsg['tryAuth'];
}
elseif(!empty($_POST) && isset($_POST['reg_user_mail']))
{
	// если пришел запрос на регистрацию
	
	$reg_user_mail = trim($_POST['reg_user_mail']);
	$rand = mt_rand(0, 1000000000); // контрольная строка
	
	// здесь отправка письма с контрольной строкой
	//$errorMsg[] = $arrMsg['tryReg'];
	
	$to 	= array(
			'0'=> array('name' => 'Посетителю сайта','email' => $reg_user_mail)
	);
	$cc 	= array();
	$bcc 	= array();
	$read 	= array();
	$reply 	= array();

	$sender = 'cakeberry@cakeberry.ru';
	$senderName = 'Администратор';
	$subject = 'Регистрация на сайте cakeberry';
	$message = '<html>
			<head>
			  <title></title>
			</head>
			<body>
				<a href="http://'.$_SERVER['HTTP_HOST'].'/mypage/'.$rand.'/">http://'.$_SERVER['HTTP_HOST'].'/mypage/'.$rand.'/</a>
			</body>
			</html>';
	$obj = new sendMail($to, $sender, $subject, $message, $cc, $bcc,$senderName, $read, true, $reply, true);
	
	$reg_send = $obj->sendEmail(); // результат отправки контрольного письма
	
}
?>