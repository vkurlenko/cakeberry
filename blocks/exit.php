<?
session_start();
include "../functions.php";

//printArray($_SESSION);

// переменные сессии, удаляемые при выходе
/*$arrVars = array(
	'user_id',
	'user_access'
);*/

$arrVars = array(
	'cms_user_login',
	'cms_user_pwd',
	'cms_user_group',
	'cms_user_access'
);
	
foreach($_SESSION as $k => $v)	
{
	if(!in_array($k, $arrVars))
		unset($_SESSION[$k]);
}



header("Location:".$_SERVER['HTTP_REFERER']);
?>