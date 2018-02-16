<?
/*include DIR_CLASS.'/class.site.map.php';
			
$html = new SITE_MAP;

$html -> table_name 	= SITE_PREFIX.'_pages';
$html -> parent_field 	= 'p_parent_id';
$html -> order_by_field = 'p_order';
$html -> page_title 	= 'p_title';


$code = $html -> selectLevel(0, 0);*/

//$arr = $html -> getArrSiteMap(0, 0);

//printArray($arr);

include DIR_CLASS.'/class.menu.php';

$arr = array();

$menu = new MENU;

$menu -> mainMenu = false;
$arr = $menu -> menuSimple();


?>
<ul>
<?
foreach($arr as $k1 => $v1)
{

	if($v1['p_show'] == '1' && $v1['p_site_map'] == '1')
	{
	?><li><a href="/<?=$v1['p_url']?>/"><?=$v1['p_title']?></a>
	<?
		if(!empty($v1['p_child']))
		{
			?>
			<ul>
			<?
			foreach($v1['p_child'] as $k2 => $v2)
			{
				if($v2['p_show'] == '1' && $v2['p_site_map'] == '1')
				{
					?>
					<li><a href="/<?=$v2['p_url']?>/"><?=$v2['p_title']?></a>
					
					<?
					if(!empty($v2['p_child']))
					{
						?>
						<ul>
						<?
						foreach($v2['p_child'] as $k3 => $v3)
						{
							if($v3['p_show'] == '1' && $v3['p_site_map'] == '1')
							{
								?><li><a href="/<?=$v3['p_url']?>/"><?=$v3['p_title']?></a>
								
								</li><?
							}
						}
						?>
						</ul>
						<?
					}
					?>
					</li>
					<?
				}
			}
			?>
			</ul>
			<?
		}
	?>
	</li>
	<?
	}
}
?>
</ul>