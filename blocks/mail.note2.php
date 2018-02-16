<?

//mail('vkurlenko@yandex.ru', 'test cron 2', 'note2');

//$f = fopen('log', 'w+');
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* отправка напоминаний на почту */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
//fwrite($f, 'подключаем файлы \n');

include_once "/home/vinci-1/cakeberry.ru/docs/config.php";
//fwrite($f, 'подключили config \n');

include_once "/home/vinci-1/cakeberry.ru/docs/functions.php" ;
//fwrite($f, 'подключили functions \n');

include_once "/home/vinci-1/cakeberry.ru/docs/functions_sql.php" ;
//fwrite($f, 'подключили functions_sql \n');

include_once "/home/vinci-1/cakeberry.ru/docs/blocks/mail.inc.php";
//fwrite($f, 'подключили mail.inc.php \n');





/* удалим старые напоминания */
/*$sql = "DELETE FROM `".$_VARS['tbl_prefix']."_notes`
		WHERE note_date < NOW()
		OR (note_rem_3 = '1' AND note_rem_1 = '1')";*/	
		
/*$sql = "DELETE FROM `".$_VARS['tbl_prefix']."_notes`
		WHERE note_date < NOW()";
			
$res = mysql_query($sql);*/
/* /удалим старые напоминания */



$today 	= date('Y-m-d');
$arr 	= array();
$arr2 	= array();
$site 	= 'cakeberry.ru';//$_SERVER['HTTP_HOST'];

$period = array(14, 7, 3); // за сколько дней до события напоминать

// дата напоминания
$preday = array(
	date('Y-m-d', time() + ($period[0] * 24 * 60 * 60)),
	date('Y-m-d', time() + ($period[1] * 24 * 60 * 60)),
	date('Y-m-d', time() + ($period[2] * 24 * 60 * 60))
	);
	
//printArray($preday);

/* найдем заметки со сроком исполнения за 14, 7, 3 дней  */
$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_notes`
		WHERE 	(note_rem_3 = '0' AND (note_date = '$today' + INTERVAL 14 DAY))
		OR 		(note_rem_2 = '0' AND (note_date = '$today' + INTERVAL 7 DAY)) 
		OR 		(note_rem_1 = '0' AND (note_date = '$today' + INTERVAL 3 DAY))
		LIMIT 	0,5";
//echo $sql;

//fwrite($f, $sql);

$res = mysql_query($sql);

if($res && mysql_num_rows($res) > 0)
{
	while($row = mysql_fetch_assoc($res))	
	{
		$arr[] = $row;
	}
}
/* /найдем заметки со сроком исполнения за Х дней */





/* отправим письма-напоминания */
foreach($arr as $k => $v)
{
	$to 	= array(
			'0'=> array(
				'name' 	=> $v['note_mail'],
				'email' => $v['note_mail']
				)
	);
	$cc 	= array();
	$bcc 	= array();
	$read 	= array();
	$reply 	= array();
	
	$sender = 'cakeberry@cakeberry.ru';
	$senderName = 'cakeberry.ru';
	$subject = 'Напоминание на '.$v['note_date'].' "'.$v['note_title'].'"';
	$message = '<html>
			<head></head>
			<body>
				Дата события: '.$v['note_date'].'<br />
				Заголовок: '.$v['note_title'].'<br>
				Текст заметки: '.$v['note_text'].'<br>';
				
	if($v['note_item'] > 0)
		$message .= 'Торт на сайте: <a href="http://'.$site.'/item_card/'.$v['note_item'].'/">http://'.$site.'/item_card/'.$v['note_item'].'/</a>';
	
	$message .= '					
			</body>
			</html>';
			
			
	//fwrite($f, $message);
			
	$obj = new sendMail($to, $sender, $subject, $message, $cc, $bcc, $senderName, $read, true, $reply, true);
	
	$note_send = $obj -> sendEmail(); // результат отправки письма
	
	// если письмо-напоминание успешно отправлено, то сделаем пометку в БД
	if($note_send)
	{		
		if($v['note_rem_3'] == '0' && $v['note_date'] == $preday[0])
			$period = 'note_rem_3';
			
		elseif($v['note_rem_2'] == '0' && $v['note_date'] == $preday[1])
			$period = 'note_rem_2';
			
		elseif($v['note_rem_1'] == '0' && $v['note_date'] == $preday[2])
			$period = 'note_rem_1';
		
		$arr2[] = array(
			'id' 		=> $v['id'],
			'note_date'	=> $v['note_date'],
			'period' 	=> $period
		);
	}	
}
/* /отправим письма-напоминания */





/* пометим заметки как отправленные */

if(!empty($arr2))
{
	foreach($arr2 as $k => $v)
	{
		
		$a = explode('-', $v['note_date']);
		$a[0]++;
		$new_date = implode('-', $a);
		
		if($v['period'] == 'note_rem_1')
		{
			// перенос даты на следующий год
			$sql = "UPDATE `".$_VARS['tbl_prefix']."_notes`
					SET note_rem_3 = '0',
					note_rem_2 = '0',
					note_rem_1 = '0',
					note_date = '$new_date'
					WHERE id = ".$v['id'];
		}
		else
		{
			$sql = "UPDATE `".$_VARS['tbl_prefix']."_notes`
					SET ".$v['period']." = '1'
					WHERE id = ".$v['id'];
		}
		
		//fwrite($f, $sql);
		
		$res = mysql_query($sql);
	}
}
/* /пометим заметки как отправленные */

	

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* /отправка напоминаний на почту */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
?> 