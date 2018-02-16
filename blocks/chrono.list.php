<?
/**************************/
/* список событий хроники */
/**************************/
$itemMonth = $itemYear = '%';
	$itemCat   = '';
if(!empty($_POST))
{
	
	
	if(isset($_POST['itemMonth']) && $_POST['itemMonth'] != 0)
		$itemMonth = $_POST['itemMonth'];
		
	if(isset($_POST['itemYear']) && $_POST['itemYear'] != 0)
		$itemYear = $_POST['itemYear'];
		
	if(isset($_POST['itemType']) && $_POST['itemType'] != 'all')
	{
		$itemCat = "AND news_cat = '".$_POST['itemType']."'";
	}


	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_news`
			WHERE news_show = '1'
			AND news_date LIKE '".$itemYear."-".$itemMonth."%'
			".$itemCat."
			ORDER BY news_date DESC";
}
else 
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_news`
			WHERE news_show = '1'
			AND news_date LIKE '".$itemYear."-%'
			ORDER BY news_date DESC";
}
		
$res = mysql_query($sql);

if($res && mysql_num_rows($res) > 0)
{
	while($row = mysql_fetch_array($res))
	{
		?>
		<div class="chronoItem">
			
			<?
			if($row['news_img'] > 0)
			{
				?>	
				<a href="/event/<?=$row['id']?>/"><?
				$img = new Image();
				$img -> imgCatalogId 	= $_VARS['env']['photo_alb_news'];
				$img -> imgId 			= $row['news_img'];
				$img -> imgAlt 			= $row['news_title'];
				$img -> imgWidthMax 	= 135;
				$img -> imgHeightMax 	= 100;	
				$img -> imgMakeGrayScale= false;
				$img -> imgGrayScale 	= false;
				$img -> imgTransform	= "crop";
				$html = $img -> showPic();
				echo $html;
				?></a>
				<?
			}
			else
			{
				?><div class="noimg">&nbsp;</div><?
			}
			?>
			
			<div class="chronoItemBody">
				<div class="chronoItemHead">
					<?
					switch($row['news_cat'])
					{
						case "actions" : $chronoItemType = "Статья"; break;
						default : $chronoItemType = "Событие"; break;
					}
					?>
					<span class="chronoItemType"><?=$chronoItemType?>:</span>
					<h3><?=$row['news_title']?></h3>
				</div>
				
				<p><?=$row['news_text_1']?></p>
					
				<a href="/event/<?=$row['id']?>/">Подробнее</a>&nbsp;&nbsp;&nbsp;&nbsp;/&nbsp;&nbsp;&nbsp;&nbsp;<span class="chronoItemDate"><?=format_date_to_str($row['news_date'], $sel_2 = " ", $year = true)?></span>					
			</div>
			<div style="clear:both"></div>
		</div>
		<div style="clear:both"></div>
		<?
	}
}
else
{
	echo '<p>Нет статей за указанный период</p>';
}


?>				
				
				
				<div style="clear:both"></div>