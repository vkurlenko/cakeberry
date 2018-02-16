<?
error_reporting(E_ALL);

/************************************/
/*				Define 				*/
/************************************/

define('SITE_PREFIX', 'cbr');

define('DIR_ROOT'	, dirname(__FILE__));
define('HOST'		, $_SERVER['HTTP_HOST']);
define('SL'			, '/');
define('NL'			, "\n");
define('BR'			, "<br>");


/******* Directory ********/

define('DIR_CSS'	, SL.'css');
define('DIR_IMG'	, SL.'img');
define('DIR_BLOCKS'	, DIR_ROOT.SL.'blocks');
define('DIR_PIC'	, SL.'pic_catalogue');
define('DIR_JS'		, SL.'js');


$class_cfg = array(
	'cakeberry' 	=> 'z:/home/interface/class',
	'cakeberry.ru'	=> DIR_ROOT.'/class'
);



define('DIR_CLASS'	, $class_cfg[HOST]);

/******* /Directory ********/


/******* Mysql ******/

include_once DIR_CLASS.'/class.db.php';

$db_cfg = array(
	'cakeberry' => array(
		'localhost', 			// db host
		SITE_PREFIX, 			// db name
		SITE_PREFIX.'_user', 	// db user
		SITE_PREFIX.'_pwd'		// db_pwd
	),
	'cakeberry.ru' => array(
		'vinci-1.mysql', 			// db host
		'vinci-1_cakeberry', 			// db name
		'vinci-1_mysql2', 	// db user
		'h0h4uhnk'		// db_pwd
	)
);


DB::db_set($db_cfg);
DB::db_connect();
DB::db_set_names();

/******* /Mysql ******/


/************************************/
/*				/ Define			*/
/************************************/



$_SERVER['DOC_ROOT'] = DIR_ROOT;

$_VARS = array();
$_VARS['cms_dir']			= "cms9"; 	// папка с CMS 
$_VARS['cms_modules']		= "modules";// функциональные модули  
$_VARS['cms_pic_in_page']	= 100; 		// кол-во выводимых картинок в менеджере картинок


$_VARS['multi_lang']		= false;	// многоязычный сайт

$_VARS['mail_admin']		= "victor@vincinelli.com"; 	// e-mail администратора


$_VARS['tbl_prefix']		= SITE_PREFIX;  								// префикс для уникальных таблиц CMS
$_VARS['tbl_pages_name']	= $_VARS['tbl_prefix']."_pages";		// имя таблицы страниц
$_VARS['tbl_cms_users']		= $_VARS['tbl_prefix']."_cms_users";	// имя таблицы разделов
$_VARS['tbl_template_name'] = $_VARS['tbl_prefix']."_templates";	// имя таблицы шаблонов 
$_VARS['tbl_photo_alb_name']= $_VARS['tbl_prefix']."_pic_catalogue";// имя таблицы фотоальбомов
$_VARS['tbl_photo_name']	= $_VARS['tbl_prefix']."_pic_";			// префикс таблицы фотоальбома
$_VARS['tbl_news']			= $_VARS['tbl_prefix']."_news";			// имя таблицы новостей
$_VARS['tbl_iblocks']		= $_VARS['tbl_prefix']."_iblocks";		// имя таблицы инфоблоков

$_VARS['tpl_dir']			= "templates/".$_VARS['tbl_prefix'];	// папка с шаблонами
$_VARS['photo_alb_dir']		= "pic_catalogue"; 						// папка с фотоальбомами
$_VARS['photo_alb_sub_dir'] = $_VARS['tbl_prefix']."_pic_";
$_VARS['audio_alb_dir']		= "files/audio"; 						// папка с аудио-файлами
$_VARS['video_alb_dir']		= "files/video"; 						// папка с видео-файлами


$_VARS['news_category']	= array(
	"news_action" => array("Акции")
);

$_VARS['news_limit']	= 3; // кол-во выводимых последних новостей в списке (не архив)

$_VARS['banners_place'] = array(	
	"banner_line_1" => "пара баннеров (линейка 1)",
	"banner_line_2" => "большой баннер (линейка 2)",
	"banner_line_3" => "пара баннеров (линейка 3)",
	"banner_line_4" => "большой баннер (линейка 4)"
);

$_VARS['catalog_photo_alb']	= 12;
$_VARS['item_prefix'] = "item";

// иконки cms
$_ICON = array(
	"down"	=> "/cms9/icon/down2.png",
	"up" 	=> "/cms9/icon/up.png",
	"del" 	=> "/cms9/icon/del.png",
	"edit" 	=> "/cms9/icon/hdsave.png",
	"next" 	=> "/cms9/icon/add file.png",
	"next_empty"=> "/cms9/icon/file.png",
	"main_menu"	=> "/cms9/icon/actions.png",
	"image"		=> "/cms9/icon/image.png",
	"user_ok"	=> "/cms9/icon/accept.png",
	"user_block"=> "/cms9/icon/delete.png",
	"add_item"	=> "/cms9/icon/addd.png",
	"tpl_index"	=> "/cms9/icon/flag_green.png",
	"tpl_def"	=> "/cms9/icon/flag_blue.png",
	"redo"		=> "/cms9/icon/redo.png",
	"lock"		=> 	"/cms9/icon/protectred.png",
	"money"		=> 	"/cms9/icon/creditcard.png",
	"tick"		=> 	"/cms9/icon/tick.png",
	"users1"	=> 	"/cms9/icon/users1.png"	,
	"pictures"  => 	"/cms9/icon/pictures.png"

	
);
/*~~~~~~~~~ /параметры CMS ~~~~~~~~~~~*/

// группы пользователей CMS 
$_VARS['arrGroups'] = array(
	"admin" 	=> array("Администраторы"),
	"manager" 	=> array("Менеджеры"),	
	"editor"	=> array("Контент-менеджеры"),
	"finans"	=> array("Бухгалтеры")
);


// статусы заказа
$_VARS['order_status'] = array(
	'raw' 		=> 'не обработан', 
	'confirmed'	=> 'подтвержден', 
	'accepted'	=> 'принят', 
	'shipped'	=> 'отгружен', 
	'paid'		=> 'оплачен'
);

// типовые формы
$_VARS['item_constr'] 	= array(
	"circle" => "круглая", 
	"square" => "квадратная"
	);
	
$_VARS['arrColor1']		= array(
	"white" => "белый", 
	"cream" => "кремовый", 
	"choko" => "шоколадный"
	);
	
$_VARS['arrColor2']	= array(
	"white" => "белый", 
	"cream" => "кремовый", 
	"choko" => "шоколадный"
	);

/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*~~~ переменные, редактируемые через cms ~~~*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
$_VARS['env'] = array();

// по умолчанию
$_VARS['env']['photo_alb_other'] = 1; // фотоальбом "Разное"

// переопределение из БД
$sql = "select * from `".$_VARS['tbl_prefix']."_presets` where 1";
$res = mysql_query($sql);
if($res)
{
	while($row = mysql_fetch_array($res))
	{
		$_VARS['env'][$row['var_name']] = $row['var_value'];
	}
}
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*~~~/переменные, редактируемые через cms ~~~*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

?>