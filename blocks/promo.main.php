<?
if(trim($_PAGE['p_content']) !== '')
{

	if(@isset($_VARS['env']['promo_title']))
		$t = '<h2>'.$_VARS['env']['promo_title'].'</h2>';

?>
<div class="sliderCatalog promoMain">

	<!--<h2>Заголовок</h2>-->
	<?=@$t?>
	
	:::content:::
	
</div>
<?
}
?>