<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

include_once "vars.php";
include_once "functions.php";


if(!empty($_POST) && isset($_POST['itemId']))
{
	$arrUserData = userData($_SESSION['user_id']);
	
	$faqUserName = $arrUserData['user_patr'].' '.$arrUserData['user_name'].' '.$arrUserData['user_surn'];
	$faqDate     = date('Y-m-d').' '.date('h:i:s');
	
	// проверим, оставлял ли пользователь уже отзыв об этом продукте
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_faq`
			WHERE faqUserName = '".$faqUserName."'
			AND faqPerson = '".$_POST['itemId']."'";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		echo "Вы уже оставляли отзыв об этом продукте.";
		exit;
	}
			
	
	$sql = 'INSERT INTO `'.$_VARS['tbl_prefix'].'_faq`
			(faqType, faqUserName, faqUserMail, faqQuestion, faqPerson, faqSign, faqDate)
			VALUES("ref_salon", "'.$faqUserName.'", "'.$arrUserData['user_mail'].'", "'.trim($_POST['userMsg']).'", "'.$_POST['itemId'].'", "'.$_POST['userRate'].'", "'.$faqDate.'")';
	$res = mysql_query($sql);
	
	$sql = "UPDATE `".$_VARS['tbl_prefix']."_faq` 
			SET faqOrder = ".mysql_insert_id()."
			WHERE id = ".mysql_insert_id();
	$res = mysql_query($sql);
	
	if($res) 
	{
		echo "Спасибо за отзыв";
		setRating($_POST['itemId']);
	}
	else 
	{
		echo "Ошибка.";
		echo $sql;
	}
}
else 
{
	echo "Ошибка.";
	echo '<pre>';
	print_r($_POST);
	echo '</pre>';
}
?>
