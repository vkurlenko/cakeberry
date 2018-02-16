<?
/* воспроизведение видео */

$video_html 	= '';
$video_title 	= '';
$video_descr 	= '';
$video_date 	= '';
$video_W = 640;
$video_H = 360;

if(isset($_GET['param3']))
{
	$video_id 		= $_GET['param3'];

	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_video`
			WHERE id = ".$video_id;			
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		$video_html 	= htmlspecialchars_decode($row['video_html']);
		
		// подставляем размеры
		$pattern[0] = '(width="\d+")';
		$replace[0] = 'width="'.$video_W.'"';
		$pattern[1] = '(height="\d+")';
		$replace[1] = 'height="'.$video_H.'"';
		
		$video_html = preg_replace($pattern, $replace, $video_html);
		
		
		$video_title 	= $row['video_title'];
		$video_descr 	= $row['video_descr'];
		$video_date 	= $row['video_create'];
	}
	else 
	{
		?><p>Видео не найдено.</p><?
	}
}
else 
{
	?><p>Видео не найдено.</p><?
}
?>

<div class="galleryVideo">
	<!--<img src="/img/pic/video.jpg" width="640" height="360" />--><?=$video_html ?>					
</div>
<div class="galleryViewDescr">
	<div>
		<span class="galleryItemCategory">Видео:</span>&nbsp;<!--ДЕНЬ РОЖДЕНИЯ ВАСИЛИСЫ ПРЕМУДРОЙ--><span class="galleryItemTitle"><?=$video_title?></span>
		<!--
		<p>Коржи для большинства тортов изготовляются из бисквитного или песочного теста, 
		классические торты представляют собой комбинацию из тонкого песочного коржа (для более стабильного основания) 
		и одного или нескольких бисквитных коржей.</p>
		-->
		<p><?=$video_descr?></p>
		<span class="galleryItemDate"><!--12 апреля 2012--><?=@format_date_to_str($video_date, $sel_2 = " ", $year = true )?></span>
	</div>
</div>