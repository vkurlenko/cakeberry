<?
$arr = array();
foreach($arrItem as $k => $v)
{
	foreach($v as $m)
	{
		$arr[] = $m;
	}
}
//echo $sql;
$arrItem = $arr;
//printArray($arr);

?>

			<div class="sliderGallery">
			
							
				<a class='prev browse left'></a>

					<div class="scrollable3">
				
						<div class='items'>	
							<div class="item"><?
							$i = 0;							
							$d = 0;
							$n = 0;
							?><div class="sliderGalleryItem"><?
							foreach($arrItem as $k => $v)
							{
								$i++;									
								$d++;
								$n++;
								
								if(
									(isset($_GET['param2']) && $v[0] == $_GET['param2'] && isset($_GET['param3']) && $v[1] == $_GET['param3']) ||
									!isset($_GET['param3']) && $n == 1) $cls = 'active';
								else $cls = 'noactive';
								?><a class="<?=$cls?>" href="/gallery/<?=$v[0]?>/<?=$v[1]?>/"><?=$v[2]?><?
								if($v[0] == 'video')
								{
									?><div class="iconVideo">&nbsp;</div><?
								}
								?></a><? 
								
								
								
								if($d > 1)
								{
									?></div><?
									if($i > 16)
									{
										?></div><div class="item"><?
										$i = 0;
									}
									?><div class="sliderGalleryItem"><?
									$d = 0;
								}								
							}
							?></div></div>
							
						
						</div>
					
					</div>
					
				<a class='next browse right'></a>
				<div style="clear:both"></div>				
					
			
			</div>