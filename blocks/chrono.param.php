<?
/* var */
$arrEventType = array(
	'all'		=> 'все',
	"news" 		=> "события",
	"actions" 	=> "статьи"
);

$arrMonthRus = array("все", 
	"январь", "февраль", "март", "апрель", "май", "июнь", "июль", "август", "сентябрь",
	"октябрь", "ноябрь", "декабрь"
	);
	
$arrEventMonth = array(0);
$arrEventYear  = array(0);

/* prepair */
$sql = "SELECT news_date FROM `".$_VARS['tbl_prefix']."_news`
		WHERE news_show = '1'";
$res = mysql_query($sql);

while($row = mysql_fetch_array($res))
{
	/* 2012-05-05 13:06:00 */
	$date = explode("-", $row['news_date']);
	
	if(!in_array($date[1], $arrEventMonth))	
		$arrEventMonth[] = $date[1];
	
	if(!in_array($date[0], $arrEventYear)) 
	$arrEventYear[] = $date[0];
}

sort($arrEventMonth);
ksort($arrEventYear);

//printArray($arrEventMonth);
?>
				<div class="chronoParam">
					<form action="" method="post">
					<div class="chronoParamItem">
						Материалы
						<select name="itemType">
							<?
							foreach($arrEventType as $k => $v)
							{
								$sel = "";
								if(isset($_POST['itemType']) && $_POST['itemType'] == $k) $sel = "selected";
								?><option value="<?=$k?>" <?=$sel?>><?=$v?></option><?
							}
							?>							
						</select>
					</div>
					<div class="chronoParamItem">
						Месяц
						<select name="itemMonth">
							<?
							foreach($arrEventMonth as $k)
							{
								$sel = "";
								if(isset($_POST['itemMonth']) && $_POST['itemMonth'] == $k) $sel = "selected";
								?>
								<option value="<?=$k?>" <?=$sel?>><?=$arrMonthRus[$k * 1]?></option>
								<?
							}
							?>							
						</select>
					</div>
					<div class="chronoParamItem">
						Год
						<select name="itemYear">
						<?
							foreach($arrEventYear as $k)
							{
								$sel = "";
								
								if(isset($_POST['itemYear']) && $_POST['itemYear'] == $k) 
									$sel = "selected";
								
								if($k == 0)
									$y = 'все';
								else
									$y = $k;
								?>
								<option value="<?=$k?>" <?=$sel?>><?=$y?></option>
								<?
							}
							?>	
						</select>
						
					</div>
					<div class="chronoParamItem">&nbsp;
						<input type="submit" value="Найти">
					</div>
					
					
					</form>
				</div>