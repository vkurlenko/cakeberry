<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Новостной листок</title>
<meta http-equiv="keywords" content="Новостной листок" />
<meta http-equiv="description" content="Новостной листок" />
</head>
<body style="font-family: tahoma;font-size: 13px;color: #333;">
<div id="mainDiv" style="width: 100%;margin: 0 auto;padding: 0;background: #fff;">
  <!-- BEGIN верхний блок -->
  <div class="topBlock" style="height: 70px;padding: 0 25px;"> 
  	<a href="/" class="logoImg"><img style="margin-top: 25px;" src="http://<?=$_SERVER['HTTP_HOST']?>/img/tpl/logo.png" width="236" height="31" /></a>
    <div class="topBlockPhone" style="float: right;color: #999;font-size: 11px;margin-left: 120px;margin-top: 28px !important;">
		<span class="phoneTitle" style="display: block;float: left;font-size: 11px;margin-top: 7px;">Наш телефон в Москве:</span><span style="font-size: 18px;color: #999;display: block;float: right;margin-left: 20px;margin-top: 1px;">+7&nbsp;495&nbsp;
			<span class="phoneNum" style="font-size: 18px;color: #906;display: inline;margin-left: 0;margin-top: 0;">7439834</span>
		</span>
	</div>
  </div>
  <!-- END верхний блок -->
  <!-- BEGIN главное меню -->
  <?
  $stUl = 'style="display: block;padding: 0 15px 0 15px;margin: 0;background: #906;"';
  $stLi = 'style="display: block;padding: 0;margin: 0 20px 0 0;float: left;list-style: none;"';
  $stA  = 'style="color: #FFFFFF;text-decoration: none;display: block;padding: 5px 10px;"';
  ?>
  <ul class="menuMain" <?=$stUl?>>
    <li class="first" <?=$stLi?>>
		<a class=""  <?=$stA?> href="http://<?=$_SERVER['HTTP_HOST']?>/catalog_m/">Каталог тортов</a>
	</li>
    <li class="" <?=$stLi?>>
		<a class=""  <?=$stA?> href="http://<?=$_SERVER['HTTP_HOST']?>/cakefororder/">Торты на заказ</a>
	</li>
    <li class="" <?=$stLi?>>
		<a class=""  <?=$stA?> href="http://<?=$_SERVER['HTTP_HOST']?>/detskie_torty/">Детские торты</a>
	</li>
    <li class="" <?=$stLi?>>
		<a class=""  <?=$stA?>href="http://<?=$_SERVER['HTTP_HOST']?>/wedding_cakes/">Свадебные торты</a>
	</li>
    <li class="" <?=$stLi?>>
		<a class=""  <?=$stA?>href="http://<?=$_SERVER['HTTP_HOST']?>/pay_and_deliv/">Оплата и доставка</a>
	</li>
    <li class="" <?=$stLi?>>
		<a class=""  <?=$stA?>href="http://<?=$_SERVER['HTTP_HOST']?>/gallery/">Галерея</a>
	</li>
    <li class="" <?=$stLi?>>
		<a class=""  <?=$stA?>href="http://<?=$_SERVER['HTTP_HOST']?>/about/">О нас</a>
	</li>
    <li class="prelastLi" <?=$stLi?>>
		<a class=""  <?=$stA?>href="http://<?=$_SERVER['HTTP_HOST']?>/chrono/">Новости</a>
	</li>
    <li class="lastLi" <?=$stLi?>>
		<a class="activeA"  <?=$stA?>href="http://<?=$_SERVER['HTTP_HOST']?>/feedback/">Обратная связь</a>
	</li>
    <div style="clear:both"></div>
  </ul>
  <!-- END  главное меню -->
  <div class="innerDiv bgAbout" style="padding: 0 24px;border-bottom: 1px solid #dbdbdb;position: relative;">
    <div style="clear:both"></div>
    <!-- BEGIN контент -->
    <div class="content" style="padding-top: 25px;" >
		<? 
		$stH1 = 'style="font-family: Times New Roman, Times, serif;font-size: 48px;font-style: italic;padding: 0;margin: 0;font-weight: normal;line-height: 0.7em;"';
		?>
      <h1 <?=$stH1?>>Новостной листок</h1>
      
	  <?
	  if(isset($_GET['param2']) && $_GET['param2'] == 'delete' && isset($_GET['param3']) && intval($_GET['param3']))
	  {
	  		$sql = "DELETE FROM `".$_VARS['tbl_prefix']."_subscribe`
					WHERE id = ".$_GET['param3'];
					
			$res = mysql_query($sql);
			
			if($res && mysql_affected_rows() > 0)
			{
				echo '<div style="padding:20px">Вы успешно отписались от рассылки.<br>
				<a href="/">Перейти на сайт.</a></div>';
			}
			else
			{
				?>:::content:::<?
			}
	  }
	  else
	  {
	  	?>:::content:::<?
	  }
	  ?>
	  
	  
	  
	  
	</div>
    <!-- END контент -->
    <!-- BEGIN нижнее меню -->
    <div class="menuFoot menuFootS">
      <p>&nbsp;</p>
      <div style="clear:both"></div>
    </div>
    <!-- END  нижнее меню -->
	
	<!--<div style="padding-bottom:20px"><a href="http://<?=$_SERVER['HTTP_HOST']?>/subscribe/delete/">Отказаться от рассылки Cakeberry</a></div>-->
  </div>
  
  
  
  <!-- BEGIN подвал -->
  <div class="footer" style="padding: 11px 24px 11px 30px;">
    <div class="copyright" style="float: left;color: #666;font-size: 10px;margin: 3px 15px 0 0;">Cakeberry. Кондитерская философия &copy; Все права защищены. 2012 - 2014</div>
    <ul style="display: block;float: left;margin: 3px 0 0 0;padding: 0;">
	<? 
		$st = 'style="display: block;float: left;list-style: none;margin: 0 15px 0 0;padding: 0;font-size: 10px;"';
		$stA = 'style="color: #666;text-decoration: none;font-size: 10px;"';
	?>
      <li <?=$st?>><a <?=$stA?> href="#">Каталог тортов</a></li>
      <li <?=$st?>><a <?=$stA?> href="#">Торты на заказ</a></li>
      <li <?=$st?>><a <?=$stA?> href="#">Десерты и пирожные</a></li>
      <li <?=$st?>><a <?=$stA?> href="#">Оплата и доставка</a></li>
      <li <?=$st?>><a <?=$stA?> href="#">Карта сайта</a></li>
      <li <?=$st?>><a <?=$stA?> href="#">Ссылки</a></li>
    </ul>
    <div style="clear:both"></div>
  </div>
  <!-- END подвал -->
</div>
</body>
</html>
