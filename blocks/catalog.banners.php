<?

/***********************************************/
/* вывод баннеров на главной странице каталога */
/***********************************************/

if(isset($_VARS['env']['photo_alb_banners']) && intval($_VARS['env']['photo_alb_banners']))
{
	// id альбома с баннерами
	$alb_id = $_VARS['env']['photo_alb_banners'];
	
	// выборка всех баннеров из альбома
	$sql = "SELECT * FROM `".SITE_PREFIX."_pic_".$alb_id."`
			WHERE 1
			ORDER BY order_by ASC";
	$res = mysql_query($sql);
	
	$arr = array();
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_array($res))
		{
			$arr[] = array(
				'file' 	=> $row['id'].'.'.$row['file_ext'],
				'url'	=> $row['url']
			);
		}
	}	
	
	
	if(!empty($arr))
	{
		$dir = DIR_PIC.'/'.SITE_PREFIX.'_pic_'.$alb_id;
		
		$i = 0;
		foreach($arr as $k => $v)
		{
			if(file_exists(DIR_ROOT.'/'.$dir.'/'.$v['file']))
			{
				$f = getimagesize(DIR_ROOT.'/'.$dir.'/'.$v['file']);
				
				$clsAdd = '';
				
				if($f[0] > 565)
				{
					$cls = 'bannerBig';					
					$size = ' width="1154" height="149" ';
				}
				else 
				{
					$cls = 'bannerSmall';
					$size = ' width="570" height="150" ';
					
					$i++;
					if($i == 2)
					{
						$i = 0;
						$clsAdd = 'bannerLast';
					}
				}
					
				?><div class="<?=$cls.' '.$clsAdd?>"><a href="<?=$v['url']?>"><img src="<?=$dir.'/'.$v['file']?>" <?=$size?> /></a></div><?
			}
		}		
	}	
}
?>