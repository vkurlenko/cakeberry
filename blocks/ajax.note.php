<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

include_once "vars.php";
include_once "functions.php";


if(!empty($_POST)/* && isset($_POST['itemId'])*/)
{
	//printArray($_POST);
	
	$arrUserData = userData($_SESSION['user_id']);
	
	$error = array();
	$note_item = 0;
	
	if($_POST['note_date_d'] == 0 || $_POST['note_date_m'] == 0 || $_POST['note_date_y'] == 0)
	{
		$error[] = "Неверно указана дата события.";
	}
	
	// проверим не является ли выбранный продукт родительской категорией
	if(isset($_POST['itemId']) && $_POST['itemId'] > 0) 
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
				WHERE item_parent = ".$_POST['itemId'];
		$res = mysql_query($sql);
		if($res && mysql_num_rows($res) > 0) $error[] = "Нельзя выбирать категорию продуктов.";
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
		$note_date = $_POST['note_date_y'].'-'.$_POST['note_date_m'].'-'.$_POST['note_date_d'];
		isset($_POST['itemId']) ? $note_item = $_POST['itemId'] : $note_item = -1;
		$note_user = $_SESSION['user_id'];
		$note_mail = $arrUserData['user_mail'];
		$note_title = htmlspecialchars($_POST['note_title']);
		$note_text  = htmlspecialchars($_POST['note_text']);
		
		
			
		
		$sql = "INSERT INTO `".$_VARS['tbl_prefix']."_notes`
				(note_date,	note_user, 	note_mail, 	note_item, note_title,	note_text)
				VALUES('".$note_date."', ".$note_user.", '".$note_mail."', ".$note_item.", '".$note_title."', '".$note_text."')";
		$res = mysql_query($sql);
		
		if($res) echo 'Напоминание сохранено.';
		else
		{
			echo 'Ошибка записи в БД';
			echo $sql;
		}
	}
}
?>