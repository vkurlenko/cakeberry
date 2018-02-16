<?
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once DIR_CLASS.SL.'class.db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/cms9/modules/framework/class.mail.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/shop/shop.class.php';
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

$order = new ORDER();
$res = $order -> getPayResult(); // результат выполнения транзакции

// 
$order -> tbl 		= $_VARS['tbl_prefix'].'_order';
$order -> id  		= $_REQUEST["InvId"];
$order -> sum_payed = $_REQUEST["OutSum"];
$s = $order -> setSumPayed();	

echo $res;
?> 