<?
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;

include_once "vars.php";
include_once "functions.php";

include_once $_SERVER['DOCUMENT_ROOT'].'/modules/note/note.class.php';


$note = new NOTE();

$note -> tbl  = $_VARS['tbl_prefix']."_notes";

if(isset($_GET['delete_note']))
{
	$note -> product = $_GET['delete_note'];

	$note -> delNote();
}


header('Location:'.$_SERVER['HTTP_REFERER']);
?>