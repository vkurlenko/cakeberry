<?
/*************************/
/* навигационная цепочка */
/*************************/

include_once 'vars.php';



/* рекурсивное построение дерева сайта */
function getParent($thisParentId)
{
	global $_VARS, $arrNav;
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pages`
			WHERE id = ".$thisParentId;
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$arrNav[] = array("/".$row['p_url']."/", $row['p_title']);
		getParent($row['p_parent_id']);
	}
	else return false;
}
/* /рекурсивное построение дерева сайта */


// если массив-структура навигационной цепочки не задана, 
// то строим дерево согласно структуры сайта 
if(!isset($arrNav))
{
	$arrNav = array();
	
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pages`
			WHERE id = ".$_PAGE['id'];
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);

	if($res && mysql_num_rows($res) > 0)
	{
		$arrNav[] = array("/".$row['p_url']."/", $row['p_title']);
		getParent($row['p_parent_id']);
	}


	$arrNav = array_reverse($arrNav);
}

/********************************/
/* достроим цепочку для галереи */
/********************************/
if($_PAGE['p_url'] == 'gallery')
{
	//$arrNav = galleryNav();
}
/*********************************/
/* /достроим цепочку для галереи */
/*********************************/

/*********************************/
/* достроим цепочку для каталога */
/*********************************/

if($_PAGE['p_url'] == 'catalog')
{
	if(isset($_GET['param2']))
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
				WHERE id = ".$_GET['param2'];
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_array($res);
			$arrNav[] = array('', $row['item_name']);			
		}
	}
}

if($_PAGE['p_url'] == 'item_card')
{
	// удалим ссылку на Карточку товара
	unset($arrNav[count($arrNav) - 1]);

	if(isset($_GET['param2']))
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_catalog`
				WHERE id = ".$_GET['param2']."
				OR id = (
					SELECT item_parent FROM `".$_VARS['tbl_prefix']."_catalog`
					WHERE id = ".$_GET['param2'].")
				ORDER BY item_parent ASC";
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_array($res))
			{
				if($row['id'] == $_GET['param2']) 
					$arrNav[] = array('', $row['item_name_2']);	
				else 
					$arrNav[] = array('/catalog/'.$row['id'].'/', $row['item_name']);	
						
			}			
		}
	}
}

/**********************************/
/* /достроим цепочку для каталога */
/**********************************/

?>
<div class="nav">
	<a class="navCurrent" href="/"><img src="/img/tpl/icon_nav_current.png" width="12" height="13"></a>
	<?
	$i = 0;
	if(!empty($arrNav))
	{		
		foreach($arrNav as $k)
		{
			$i++;
			if($i == count($arrNav))
			{
				?>
				<img class="navArrow" src="/img/tpl/arrow_nav.png"><span><?=$k[1]?></span>
				<?
			}
			else
			{
				if($k[0] != '')
				{
					?>
						<img class="navArrow" src="/img/tpl/arrow_nav.png"><a href="<?=$k[0]?>"><?=$k[1]?></a>
					<?	
				}
				else
				{
					?>
						<img class="navArrow" src="/img/tpl/arrow_nav.png"><span><?=$k[1]?></span>
					<?
				}			
			}
		}		
	}
	?>
</div>
