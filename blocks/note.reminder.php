<?
/*******************************/
/*  email рассылка напоминаний */
/*******************************/
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

include_once "vars.php";
include_once "functions.php";
include_once "mail.inc.php";

$period = array(3, 7, 14); // за сколько дней до события напоминать

// дата напоминания
$preday = array(
	date('Y-m-d', time() + ($period[0] * 24 * 60 * 60)),
	date('Y-m-d', time() + ($period[1] * 24 * 60 * 60)),
	date('Y-m-d', time() + ($period[2] * 24 * 60 * 60))
	);
	
printArray($preday);


// найдем приближающиеся события
$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_notes`
		WHERE note_date = '".$preday[0]."' OR 
			note_date = '".$preday[1]."' OR 
			note_date = '".$preday[2]."'";
		
$res = mysql_query($sql);

$arrNotes = array();

if($res && mysql_num_rows($res) > 0)
{
	while($row = mysql_fetch_array($res))
	{
		$arrNotes[$row['note_mail']][] = array($row['note_title'], $row['note_text'], $row['note_item']);	
	}
}

//printArray($arrNotes);

foreach($arrNotes as $k => $v)
{
	$text = '';
	$i = 1;
	foreach($v as $m => $note)
	{
		$text .= '<p><strong>'.$i++.'.</strong> ';
		$text .= '<strong>'.$note[0].'</strong><br>'.$note[1].'<br>';
		
		$noteProd = '';
		if($note[2] > 0)
		{
			$sql = "SELECT item_name, item_parent FROM `".$_VARS['tbl_prefix']."_catalog`
					WHERE id = ".$note[2];
			$res_p = mysql_query($sql);
			
			if($res_p && mysql_num_rows($res_p) > 0)
			{
				$row_p = mysql_fetch_array($res_p);
				$noteProd = $row_p['item_name'];
			}
			$text .= 'Торт: <a href="http://'.$_SERVER['HTTP_HOST'].'/item_card/'.$note[2].'/">'.$noteProd.'</a>';		
		}
		$text .= '</p>';
		
	}
	$to 	= array(
			'0'=> array('name' => '','email' => $k)
	);
	$cc 	= array();
	$bcc 	= array();
	$read 	= array();
	$reply 	= array();

	$sender = 'cakeberry@cakeberry.ru';
	$senderName = "Напоминатель";
	$subject = 'На дату '.$preday.' у Вас имеются напоминания';
	$message = '<html>
			<head>
			 </head>
			<body>
				На дату '.$preday.' у Вас имеются напоминания:
				'.$text.'
			</body>
			</html>';
	
	$obj = new sendMail($to, $sender, $subject, $message, $cc, $bcc, $senderName, $read, true, $reply, true);
		
	$call_send = $obj->sendEmail(); // результат отправки письма
}
?>