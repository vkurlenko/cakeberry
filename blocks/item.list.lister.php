<?
/*****************************/
/* листалка страниц каталога */
/*****************************/

if(!isset($pages_num)) 
	$pages_num = 1;

if($pages_num > 0)
{
	?>
	<div class="pageLister pageLister1">Страницы: 
	<?
	for($i = 0; $i < $pages_num; $i++)
	{
		if((!isset($_GET['param3']) && $i == 0) || (isset($_GET['param3']) && $_GET['param3'] == $i))
		{
			?><span><?=$i+1?></span><?
		}
		else
		{
			if(!isset($_GET['param2'])) 
				$_GET['param2'] = 0; // !!!заглушка!!!
			
			?><a href="/<?=$_PAGE['p_url']?>/<?=$_GET['param2']?>/<?=$i?>/"><?=$i+1?></a><?
		}
	}
	?>
	</div>
	<?
}
?>