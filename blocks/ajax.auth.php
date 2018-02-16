<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

include_once "vars.php";
include_once "functions.php";


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
				SET user_last_visit = '".date('Y-m-d')." ".date('H:i:s')."'
				WHERE id = ".$_SESSION['user_id'];
		$res = mysql_query($sql);
		
		echo true;
		exit;
		
	}
	else
	{
		// авторизация не прошла (возврат формы)
		$autoOpen   = "true";
		$errorMsg[] = $arrMsg['badAuth'];
		//echo false;
	}
	//$errorMsg[] = $arrMsg['tryAuth'];
}

foreach($errorMsg as $k)
{
	echo $k.'<br>';
}

?>