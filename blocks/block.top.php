<?
/* авторизация пользователя */
//include_once "blocks/auth.php";
/* /авторизация пользователя */
include_once DIR_BLOCKS.'/functions.php';
$arr = formatPhone();

?>
<a href="/" class="logoImg"><img src="/img/tpl/logo.png" width="236" height="31" /></a>
<div class="topBlockPhone"><span class="phoneTitle">Наш телефон в Москве:</span>&nbsp;<span><?=$arr['codeCountry']?>&nbsp;<?=$arr['codeRegion']?>&nbsp;<span class="phoneNum"><?=$arr['numPhone']?></span></span></div>

<div class="topBlockCenter">
	<div class="topBlockPayonline"><span><a href="/pay_and_deliv/">Оплачивайте заказы онлайн:</a> </span>
		<a href="/pay_and_deliv/"><img src="/img/tpl/icon_sb.png" width="19" height="20" /></a>
		<a href="/pay_and_deliv/"><img src="/img/tpl/icon_mc.png" width="31" height="20" /></a>
		<a href="/pay_and_deliv/"><img src="/img/tpl/icon_visa.png" width="36" height="20" /></a></div>
	<?
	if(isset($_SESSION['user_id']) && $_SESSION['user_id'] != 0)
	{
		// если авторизация прошла
		$_SESSION['user_access'] = true;
		?><div class="topBlockPrivateEnter"><a href="/mypage/">Моя страница</a>&nbsp;&nbsp;&nbsp;<a href="/blocks/exit.php">Выход</a></div><?
	}
	else
	{
		// если нет авторизации
		$_SESSION['user_access'] = false;
		?><div class="topBlockPrivateEnter"><a class="noauth" href="/mypage/">Личный вход</a></div><?	
		include "blocks/form.dialog.auth.php";	
	}
	?>
</div>
						
			