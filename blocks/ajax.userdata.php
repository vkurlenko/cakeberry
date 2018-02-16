<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

include_once "vars.php";
include_once "functions.php";

$arrData = array(
	'user_name' 	=> array('Имя', 			true),
	'user_patr' 	=> array('Фамилия', 		true),
	'user_surn' 	=> array('Отчество', 		true),
	'user_bd_d'		=> array('День рождения', 	true),
	'user_bd_m'		=> array('Месяц рождения', 	true),
	'user_bd_y'		=> array('Год рождения', 	true),
	'user_addr_1'	=> array('Адрес доставки 1',false),
	'user_addr_2'	=> array('Адрес доставки 2',false),
	'user_phone_c' 	=> array('Телефон (код)', 	true, 'INT'),
	'user_phone_n' 	=> array('Телефон (номер)', true, 'INT'),
	'user_mail'  	=> array('Email', 			true, 'EMAIL'),
	'user_pwd'  	=> array('Пароль', 			false),
	'user_pwd_2'  	=> array('Повтор пароля', 	false),
	'user_card_number'	=> array('Номер карты', false),
	'user_subscribe'	=> array('Получать новости', false)
);

//printArray($_POST);

// если пришла форма
if(!empty($_POST) && isset($_POST['userData']))
{
	$error = array();
	// проверить на заполнение обязательных полей
	foreach($arrData as $k => $v)
	{
		if($v[1] == true)
		{
			if(!isset($_POST[$k]) || trim($_POST[$k]) == '' || trim($_POST[$k]) == '-')
			{
				$error[] = "Необходимо заполнение всех обязательных полей ($k).";
				break;
			}
		}
	}
	
	// проверка формата данных
	foreach($arrData as $k => $v)
	{
		if(isset($v[2]))
		{
			if(!validData($v[2], $_POST[$k]))
			{
				switch($k)
				{
					case 'user_phone_c' : $error[] = "Код телефона может содержать только цифры."; break;
					case 'user_phone_n' : $error[] = "Номер телефона может содержать только цифры."; break;  
					case 'user_mail'    : $error[] = "Неверный формат email."; break; 
					default : break;
				}				
			}
		}
	}
	
	// проверка изменения пароля
	if(isset($_POST['user_pwd']) && trim($_POST['user_pwd']) != '')
	{
		if(!isset($_POST['user_pwd_2']) || $_POST['user_pwd_2'] != $_POST['user_pwd'])
		{
			$error[] = "Пароли не совпадают."; 
		}
		else
		{
			if(strlen($_POST['user_pwd']) < 8)
			{
				$error[] = "Длина пароля не может быть менее 8 знаков."; 
			}
		}
	}
	
	if($_POST['user_bd_d'] == 0 || $_POST['user_bd_m'] == 0 || $_POST['user_bd_y'] == 0)
	{
		$error[] = "Выбрана неверная дата.";
	}
	
	
	
	
	if(!empty($error))	
	{
		foreach($error as $k)
		{
			echo $k.'<br>';
		}
	}
	else
	{
		// внесем изменения в БД
		/*'user_name' 	=> array('Имя', 			true),
		'user_patr' 	=> array('Фамилия', 		true),
		'user_surn' 	=> array('Отчество', 		true),
		'user_bd_d'		=> array('День рождения', 	true),
		'user_bd_m'		=> array('Месяц рождения', 	true),
		'user_bd_y'		=> array('Год рождения', 	true),
		'user_addr_1'	=> array('Адрес доставки 1',true),
		'user_addr_2'	=> array('Адрес доставки 2',false),
		'user_phone_c' 	=> array('Телефон (код)', 	true, 'INT'),
		'user_phone_n' 	=> array('Телефон (номер)', true, 'INT'),
		'user_mail'  	=> array('Email', 			true, 'EMAIL'),
		'user_pwd'  	=> array('Пароль', 			false),
		'user_pwd_2'  	=> array('Повтор пароля', 	false)*/
		
		$user_bd 	= $_POST['user_bd_y'].'-'.$_POST['user_bd_m'].'-'.$_POST['user_bd_d'];
		$user_phone = $_POST['user_phone_c'].$_POST['user_phone_n'];
		isset($_POST['user_subscribe']) ? $user_subscribe = '1' : $user_subscribe = '0';
		
		
		
			$arr = array_diff($_POST['user_addr_1'], array(''));
			$_POST['user_addr_1'] = serialize($arr);
		
		
		
		$sql = "UPDATE `".$_VARS['tbl_prefix']."_users`
				SET 
					user_name = '".$_POST['user_name']."', 
					user_patr = '".$_POST['user_patr']."', 
					user_surn = '".$_POST['user_surn']."',
					user_birth_day    = '".$user_bd."', 
					user_addr_1 = '".$_POST['user_addr_1']."', 
					user_addr_2 = '".$_POST['user_addr_2']."', 
					user_phone  = '".$user_phone."', 
					user_mail   = '".$_POST['user_mail']."',
					user_card_number  = '".implode('',$_POST['user_card_number'])."',
					user_subscribe = '".$user_subscribe."' ";
					if($_POST['user_pwd'] != '') 
						$sql .= ", user_pwd = '".$_POST['user_pwd']."'";
				 	$sql .= "
				WHERE id = ".$_SESSION['user_id'];
		$res = mysql_query($sql);
		
		
		if($res) 
			echo true;
		else 
			echo 'Ошибка записи данных в БД. ('.$sql.')';
			
			
			
		/* обновим базу подписчиков рассылки */
		if($user_subscribe == '1')
		{
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_subscribe`
					WHERE subscribe_mail = '".$_POST['user_mail']."'";
					
			$res = mysql_query($sql);
			
			if($res && mysql_num_rows($res) == 0)
			{
				$sql = "INSERT INTO `".$_VARS['tbl_prefix']."_subscribe`
						(subscribe_mail, subscribe_reg_date)
						VALUES('".$_POST['user_mail']."', '".date("Y-m-d H:i:s")."')";
						
				$res = mysql_query($sql);
			}
		}
		else
		{
			$sql = "DELETE FROM `".$_VARS['tbl_prefix']."_subscribe`
					WHERE subscribe_mail = '".$_POST['user_mail']."'";
					
			$res = mysql_query($sql);
		}
		/* /обновим базу подписчиков рассылки */
		
	}
}


/*foreach($errorMsg as $k)
{
	echo $k.'<br>';
}*/

?>