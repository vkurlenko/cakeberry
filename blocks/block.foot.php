<div class="copyright">Cakeberry. Кондитерская философия &copy; Все права защищены. 2012 - <?=date('Y')?></div>

<ul>
	<li><a href="#">Каталог тортов</a></li>
	<li><a href="#">Торты на заказ</a></li>
	<li><a href="#">Десерты и пирожные</a></li>
	<li><a href="#">Оплата и доставка</a></li>
	<li><a href="/sitemap/">Карта сайта</a></li>
	<li><a href="#">Ссылки</a></li>
</ul>
<style>
	.fb_iframe_widget span{display:block !important; overflow:hidden}
</style>
<div class="buttonLike"><div class="fb-like" href="http://<?=$_SERVER['HTTP_HOST']?>" data-send="false" data-layout="button_count" data-width="142" data-show-faces="false" data-colorscheme="light" data-action="like"></div><!--<a href="#"><img src="/img/pic/button_like.png" width="142" height="20" /></a>--></div>

<?
// рассылка
include "blocks/mail.subscribe.php";

// удаление пользователя, не подтвердившего регистрацию
include "blocks/user.delete.php";

// рассылка напоминаний
//include "blocks/mail.note.php";
//echo $_SERVER['DOCUMENT_ROOT'];
//  /home/vinci-1/cakeberry.ru/docs			
			
//include "blocks/block.service.php";
?>
