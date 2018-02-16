<?

/*	вывод календаря на год	*/
/*
switch($_PAGE['p_url'])
{
	case "actions_archive" : $news_cat = "actions"; $news_url = "actions"; break;
	default : $news_cat = "news"; $news_url = "news"; break;
}


// массив активных дат
$sql = "SELECT * FROM `".$_VARS['tbl_news']."`
		WHERE news_show = '1' 
		AND news_cat = '".$news_cat."'
		AND news_mark = '0' 
		ORDER BY news_date desc";
		
$res = mysql_query($sql);

$arrActiveDate = array();
$arrActiveDatLink = array();

while($row = mysql_fetch_array($res))
{
	$d = explode(' ', $row['news_date'], 2);
	$date = format_date($d[0], ".");
	
	$arrActiveDate[] = $date;
}
*/
$arrActiveDate = array();

if(userAuth())
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_notes`
			WHERE note_user = ".$_SESSION['user_id']."
			ORDER BY note_date DESC";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$date = explode('-', $row['note_date'], 3);
			//$arrActiveDate[] = $date[2].'.'.$date[1].'.'.$date[0];
			$arrActiveDate[] = $date[2].'.'.$date[1];
		}
	}
	//$arrActiveDate = array("03.05.2012", "22.05.2012", "12.10.2012");
}

//printArray($arrActiveDate);

$lastday = 31;

function monthCalendar($m, $year, $lastday)
{		
	global $weeks, $lastday, $arrActiveDate, $news_url;
	//echo $lastday;
	
	if(strlen($m) < 2) $m = "0".$m;
	
	$arrMonth = array(
		array("January", 	"Январь"), 
		array("February", 	"Февраль"), 
		array("March", 		"Март"), 
		array("April", 		"Апрель"), 
		array("May", 		"Май"), 
		array("June", 		"Июнь"), 
		array("July", 		"Июль"), 
		array("August", 	"Август"), 
		array("September", 	"Сентябрь"), 
		array("October", 	"Октябрь"), 
		array("November", 	"Ноябрь"), 
		array("December", 	"Декабрь")
	);
	
	$arrWeek = array("пн", "вт", "ср", "чт", "пт", "сб", "вс");
	
	$dayofmonth = date('t', strtotime ("10 ".$arrMonth[$m - 1][0]." ".$year));
	
	// Счётчик для дней месяца
	$day_count = 1;	
	
	// 1. Первая неделя
	$num = 0; // порядковый номер недели
	$prev_days = array();
	for($i = 0; $i < 7; $i++)
	{	
		// Вычисляем номер дня недели для числа
		$dayofweek = date('w', mktime(0, 0, 0, date($m), $day_count, date($year)));					  
						  
		// Приводим числа к формату 1 - понедельник, ..., 6 - суббота
		$dayofweek = $dayofweek - 1;
		if($dayofweek == -1) $dayofweek = 6;
		
		if($dayofweek == $i)
		{
			// Если дни недели совпадают, заполняем массив $week числами месяца
			
			if(strlen($day_count) < 2) $daynum = "0".$day_count;
			else $daynum = $day_count;
			
			$thisDay = $daynum.".".$m.".".$year;
			$d = $daynum.".".$m;
			//if($thisDay == date('d').".".date('m').".".date('Y')) 
			if($d == date('d').".".date('m')) 
			{
				//if(in_array($thisDay, $arrActiveDate)) 
				if(in_array($d, $arrActiveDate)) 
					$week[$num][$i] = "<td class='today active'><a href='#'>".$day_count."</a></td>";
				else 
					$week[$num][$i] = "<td class='today' id='".$thisDay."'>".$day_count."</td>";
			}
			//elseif(in_array($thisDay, $arrActiveDate))
			elseif(in_array($d, $arrActiveDate))
			{
				$week[$num][$i] = "<td class='active' id='".$thisDay."'><a id='".$day_count."' href='#'>".$day_count."</a></td>";
			}
			else 
			{
				$week[$num][$i] = "<td id='".$thisDay."'>".$day_count."</td>";
			}
		  
			$day_count++;
		}
		else
		{
			if($lastday > 0)
			{
				$prev_days[] = $lastday - $i;				
			}
		}
		
		// печатаем числа предыдущего месяца
		if(!empty($prev_days))
		{
			$prev = array_reverse($prev_days);
			for($d = 0; $d < count($prev); $d++)
			{
				//$week[$num][$d] = "<td style='color:#999'>".$prev[$d]."</td>";
			}
		}
		// /печатаем числа предыдущего месяца
	}
	
	// 2. Последующие недели месяца
	while(true)
	{
		$num++;
		for($i = 0; $i < 7; $i++)
		{
			if(strlen($day_count) < 2) $daynum = "0".$day_count;
			else $daynum = $day_count;
			
			$thisDay = $daynum.".".$m.".".$year;
			$thisDayLink = $year."-".$m."-".$daynum;
			$d = $daynum.".".$m;
			//if($thisDay == date('d').".".date('m').".".date('Y')) 
			if($d == date('d').".".date('m')) 
			{
				//if(in_array($thisDay, $arrActiveDate)) 
				if(in_array($d, $arrActiveDate)) 
					$week[$num][$i] = "<td id='".$thisDay."' class='today active'><a  href='#'>".$day_count."</a></td>";
				else 
					$week[$num][$i] = "<td id='".$thisDay."' class='today'>".$day_count."</td>";
			}
			//elseif(in_array($thisDay, $arrActiveDate))
			elseif(in_array($d, $arrActiveDate))
			{
				$week[$num][$i] = "<td id='".$thisDay."' class='active'><a  href='#'>".$day_count."</a></td>";
			}
			else 
			{
				$week[$num][$i] = "<td id='".$thisDay."'>".$day_count."</td>";
			}
			
			$day_count++;
			// Если достигли конца месяца - выходим из цикла
			if($day_count > $dayofmonth) 
			{
				// печатаем числа следующего месяца
				$lastday = $day_count - 1;
				if($i < 7)
				{
					$index = $i+1;
					$day = 1;
					while($index < 7) 
					{
						$week[$num][$index++] = "<td style='color:#999' id='".$thisDay."'>".$day++."</td>";
					}
				}
				// /печатаем числа следующего месяца
				break;
			}
		}
		
		// Если достигли конца месяца - выходим из цикла
		if($day_count > $dayofmonth) 
		{
			// печатаем числа следующего месяца
			$lastday = $day_count - 1;
			if($i < 7)
			{
				$index = $i+1;
				$day = 1;
				while($index < 7) 
				{
					//$week[$num][$index++] = "<td style='color:#999'>".$day++."</td>";
					$week[$num][$index++] = "";
				}
			}	
			// /печатаем числа следующего месяца			
			break;
		}
	}
	
	// 3. Выводим содержимое массива $week в виде календаря
	// Выводим таблицу
	?>
	<div class="month">
		<table border=0>
		<tr>
			<th colspan=7><?=$arrMonth[$m - 1][1]."<!-- ".$year."-->"?></th>
		</tr>
		<!--<tr>
			<?
			for($w = 0; $w < count($arrWeek); $w++)
			{
				?><td class="weekDayName"><?=$arrWeek[$w]?></td><?
			}
			?>
		</tr>-->
		<?
		for($i = 0; $i < count($week); $i++)
		{
			?>
			<tr>
			<?
			for($j = 0; $j < 7; $j++)
			{
				if(!empty($week[$i][$j]))
				{
					echo $week[$i][$j];
				}
				else
				{
					?><td>&nbsp;</td><?
				}
				
			}
			?></tr><?
		} 
		if(count($week) < 6)
		{
			?><tr><td colspan=7>&nbsp;</td></tr><?
		}	
		?>
		</table>
	</div>
	<?
}


?>			
			
			<div class="sliderCalendar">
			
				<h2>Мой календарь</h2>
				
				<a class='prev browse left'></a>

					<div class="scrollable4">
				
						<div class='items'>
						
							<div class="item">
								<?
								$year  = date('Y');  
					
								$weeks = array();
								$j = 0;
								//$month_last = 13;
								$month_last = date("m") + 8;
								for($i = date("m"); $i < $month_last; $i++)
								{
									$j++;
									if($j == 5) 
									{
										echo '</div>
										
										
										<div class="item">';
										$j = 1;
									}								
									?>
										<div class="sliderCalendarItem">
										<?
										$m = $i;
										$y = $year;
										if($i > 12)
										{
											$y = $year + 1;											
											$m = $i - 12;
										}
										monthCalendar($m, $y, $lastday);
										?>
										</div>									
									<?
									// переход на следующий год
									
								} 
								?>
							</div>
								
						
						</div>
					
					</div>
					
				<a class='next browse right'></a>
				<div style="clear:both"></div>		
				
				<div class="sliderCalendarFooter">Отмечайте в календаре нужные даты, пишите комментарии к ним, и система будет напоминать вам об их приближении.</div>
					
			
			</div>