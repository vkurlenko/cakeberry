<?
			
				
				$arrDate = array();
				$arrM = array('0');
				$arrY = array('9999');
				
				$sqlF = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic_catalogue`
						WHERE alb_mark = 'gallery'
						ORDER BY alb_update DESC";
						
				//echo $sqlF;
						
				$sqlV = "SELECT * FROM `".$_VARS['tbl_prefix']."_video`
						WHERE video_show = '1'
						ORDER BY video_create DESC";
						
				if($_SESSION['gallery']['galleryType'] == 'alb' || $_SESSION['gallery']['galleryType'] == '')
				{
					$res = mysql_query($sqlF);
					if($res && mysql_num_rows($res) > 0)
					{
						while($row = mysql_fetch_array($res))
						{
							if(!in_array($row['alb_update'], $arrDate) && $row['alb_update'] != '0000-00-00')
							{
								$arrDate[] = $row['alb_update'];
							}							
						}
					}
				}
				
				if($_SESSION['gallery']['galleryType'] == 'video' || $_SESSION['gallery']['galleryType'] == '')
				{
					$res = mysql_query($sqlV);
					if($res && mysql_num_rows($res) > 0)
					{
						while($row = mysql_fetch_array($res))
						{
							if(!in_array($row['video_create'], $arrDate) && $row['video_create'] != '0000-00-00')
							{
								$arrDate[] = $row['video_create'];
							}
						}
					}
				}
				
				foreach($arrDate as $k)
				{
					$a = explode('-', $k, 3);
					if(!in_array($a[1], $arrM))	$arrM[] = $a[1];
					if(!in_array($a[0], $arrY))	$arrY[] = $a[0];
				}
				
				sort($arrM);
				rsort($arrY);
							
				
				//printArray($arrDate);
				?>
				
				<div class="chronoParam">
					<form action="" method="post">
					<div class="chronoParamItem">
						Материалы
						<select name="galleryType">
							<?
							$arrT = array(
								'' 		=> 'все',
								'alb' 	=> 'фото',
								'video' => 'видео'
								);
							
							foreach($arrT as $k => $v)
							{
								$sel = '';
								if($_SESSION['gallery']['galleryType'] == $k) $sel = 'selected';
								?><option <?=$sel?> value="<?=$k?>"><?=$v?></option><?
							}
							
							?>
							
						</select>
					</div>
					<div class="chronoParamItem">
						Месяц
						<select name="galleryM">
							<?
							foreach($arrM as $k)
							{
								$sel = '';
								if($_SESSION['gallery']['galleryM'] == $k) 
									$sel = 'selected';
								?><option <?=$sel?> value="<?=$k?>"><?=$_arrMonth[intval($k)]?></option><?
							}
							?>							
						</select>
					</div>
					<div class="chronoParamItem">
						Год
						<select name="galleryY">
							<?
							foreach($arrY as $k)
							{
								$sel = '';
								if($_SESSION['gallery']['galleryY'] == $k) 
									$sel = 'selected';
									
								$k == 9999 ? $strY = 'все' :  $strY = $k;
								?><option <?=$sel?> value="<?=$k?>"><?=$strY?></option><?
							}
							?>	
						</select>
					</div>
					<div class="chronoParamItem">
						&nbsp;
						<input type="submit" name="gallerySubmit" value="OK">
					</div>
					</form>
				</div>
