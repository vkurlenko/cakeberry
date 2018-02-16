<?
include_once 'blocks/functions.php';
include_once "blocks/mail.inc.php";

// если пришла контрольная строка
if(isset($_GET['param2']) && intval($_GET['param2']))
{
	// найдем такую строку в БД
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
			WHERE user_pwd = '".$_GET['param2']."'";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		
		$user_id 	= $row['id'];
		$user_mail 	= $row['user_mail'];
		
		// создадим пароль
		$user_pwd = pwdGen(8);
		
		// внесем изменения в личные данные
		$sql = "UPDATE `".$_VARS['tbl_prefix']."_users`
				SET user_pwd = '".$user_pwd."',
				user_name	= '-', 	
				user_patr	= '-', 	
				user_surn	= '-',
				user_addr_1 = '-',
				user_addr_2 = '-',
				user_phone	= '-|-',
				user_calend = '".serialize(array())."',
				user_pay 	= '".serialize(array())."',
				user_fav_item = '".serialize(array())."',
				user_register = '1',
				user_block = '0'
				WHERE id = ".$user_id;
				
		$res = mysql_query($sql);
		
		
		
		if($res) 
		{
		
			// отправка пароля на email		
			$to 	= array(
					'0'=> array('name' => 'Посетителю сайта','email' => $user_mail)
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
						Для <a href="http://'.$_SERVER['HTTP_HOST'].'/mypage/">входа на сайт</a> используйте следующие учетные данные:<br>
						email: '.$user_mail.'<br>
						пароль: '.$user_pwd.'
						
					</body>
					</html>';
					
			$obj = new sendMail($to, $sender, $subject, $message, $cc, $bcc, $senderName, $read, true, $reply, true);
			
			$reg_send = $obj->sendEmail(); // результат отправки контрольного письма
			
			if($reg_send)
			{
				?>
				<p>Ваша учетная запись активирована. <br>
				Пароль выслан на электронную почту, указанную при регистрации.</p>
				<?
			}
			else
			{
				?><p>Ошибка отправки пароля.</p><?
			}		
			
		}
		else
		{
			?><p>Ошибка активации учетной записи.</p><?
		}
	
	}
	else
	{
		?><p>Контрольная строка не найдена.</p><?
	}
}
else
{
	?><p>Контрольная строка не найдена.</p><?
}
?>