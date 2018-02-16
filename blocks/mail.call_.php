<?
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once "vars.php";
include_once "mail.inc.php";


// если пришла форма авторизации
if(!empty($_POST) && 
	isset($_POST['call_code']) 	&& trim($_POST['call_code']) 	!= '' &&
	isset($_POST['call_phone']) && trim($_POST['call_phone']) 	!= '' &&
	isset($_POST['call_name']) 	&& trim($_POST['call_name']) 	!= ''
	)

{

	$errorMsg = array();

	if(!preg_match('([0-9])', $_POST['call_code'])) $errorMsg[] = "Код телефона может содержать только цифры.";
	if(!preg_match('([0-9])', $_POST['call_phone'])) $errorMsg[] = "Номер телефона может содержать только цифры.";

	// печать ошибок обработки формы
	if(!empty($errorMsg))
	{
		foreach($errorMsg as $k)
		{
			?><p class="ui-state-error"><?=$k?></p><?
		}
	}
	else
	{
		$call_code = trim($_POST['call_code']);
		$call_phone = trim($_POST['call_phone']);
		$call_name = trim($_POST['call_name']);
		
		$to 	= array(
				'0'=> array('name' => 'Администратору сайта','email' => $_VARS['env']['mail_admin'])
		);
		$cc 	= array();
		$bcc 	= array();
		$read 	= array();
		$reply 	= array();

		$sender = 'cakeberry@cakeberry.ru';
		$senderName = $call_name;
		$subject = 'Поступил запрос на обратный звонок от  посетителя сайта';
		$message = '<html>
				<head>
				 </head>
				<body>
					От посетителя сайта '.$call_name.' поступил запрос на обратный звонок.<br />
					Номер телефона : +7 '.$call_code.' '.$call_phone.'
				</body>
				</html>';
		$obj2 = new sendMail($to, $sender, $subject, $message, $cc, $bcc,$senderName, $read, true, $reply, true);
		//echo "$to, $sender, $subject, $message, $cc, $bcc, $senderName, $read, true, $reply, true";
		$call_send = $obj2->sendEmail(); // результат отправки письма	
		
		if($call_send)
		{
			?><p class="ui-state-error">Ваш запрос успешно отправлен.</p><?
		}
		else
		{
			?><p class="ui-state-error">Ошибка отправки запроса.</p><?
		}
		//echo $call_send;
	}
}
else
{
	?><!--<p class="ui-state-error">Все поля обязательны для заполнения.</p>--><?
}

?>