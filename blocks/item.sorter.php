<?
/*******************************/
/* сортировка списка продуктов */
/*******************************/


	// модификация URL (удаление фильтра сортировки)
	$this_url = "/".$_PAGE['p_url']."/";
	if(isset($_GET['param2'])) 
	{
		$this_url .= $_GET['param2']."/";
	}
	else $this_url .= "0/";
	if(isset($_GET['param3'])) $this_url .= $_GET['param3']."/";
	if(isset($_GET['sort'])) 
	{
		$_SESSION['item_sort'] 	= $_GET['sort'];
		$_SESSION['item_order'] = $_GET['dir'];
	}
	
	$arrSort = array(
		"item_price_from" 	=> "по цене",
		"item_name" 		=> "по наименованию",
		"item_rate" 		=> "по рейтингу",
		"item_order"		=> "по умолчанию"
	);
	
	$i = 0;
	$li = '';
	foreach($arrSort as $k => $v)
	{
		$i++;
		$cls = "";
		if(	(isset($_SESSION['item_sort']) && $_SESSION['item_sort'] == $k)	|| 
			(!isset($_SESSION['item_sort']) && $i == 1))
		{
			$cls = "active";
			
			if(isset($_SESSION['item_order']) && $_SESSION['item_order'] == "asc") $dir = "desc";
			else $dir = "asc";
			
			$url = "sort=".$k."&dir=".$dir;
		}						
		else $url = "sort=".$k."&dir=asc";
		
		$li .= "<li class='$cls'><a href='$this_url$url'>$v</a></li>";			
	}
	?>	
	
<div class="sorter">
	<span class="itemParamTitle">Сортировать: </span>
	<div style='float:left; position:relative; z-index:6'>
		<ul class="itemParam">				
			<?=$li?>
			<div style="clear:both"></div>											
		</ul><img class="arrowDown" src="/img/tpl/arrow_down.png" width="9" height="5" />
	</div>
	
</div>
