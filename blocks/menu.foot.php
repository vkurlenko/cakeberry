<?
/***************/
/* нижнее меню */
/***************/

$fMenu = '';

// если карточка товара
if($_PAGE['p_url'] == 'item_card' && isset($_GET['param2']))
{
	$sql = "SELECT * FROM `".SITE_PREFIX."_catalog`
			WHERE id = ".$_GET['param2'];
	$res = mysql_query($sql);
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		$html = $row['item_text_2'];		
	}
	else
		$html = '';
}
// другая страница
else
	$html = $_PAGE['p_add_text_1'];
	
	

if(trim($html) != '')
{
	$fMenu = trim($html);	
}
else
{
	// если для данной страницы не задано нижнее меню,
	// то берем с главной страницы
	$sql = "SELECT * FROM `".SITE_PREFIX."_pages`
			WHERE p_url = 'main'
			ORDER BY id ASC
			LIMIT 0,1";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_array($res);
		$fMenu = trim($row['p_add_text_1']);
	}
}


if($fMenu != '')
{
	?>
	<script language="javascript">
	$(document).ready(function()
	{
		var ulSize = $('.menuFoot ul').size();
		
		$('.menuFoot ul').eq(ulSize - 1).addClass('ulFootLast');
		
		$('.menuFoot ul').each(function()
		{
			$(this).find('li').eq(0).addClass('first');
		})
	})
	</script>
	<?
	echo $fMenu;
}

?>


<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>

<?php 

?>