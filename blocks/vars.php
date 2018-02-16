<?
function getDataToArray($t)
{
	$arr = array();

	$sql = "SELECT * FROM `".$t."`
			WHERE elem_show = '1'
			ORDER BY elem_order ASC";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		while($row = mysql_fetch_assoc($res))
		{
			$arr[$row['elem_key']] = array($row['elem_value'], intval($row['elem_descr']));
		}
	}
	
	return $arr;
}





$_arrMonth = array('все', 'январь', 'февраль', 'март', 'апрель', 'май', 'июнь', 
					'июль', 'август', 'сентябрь', 'октябрь', 'ноябрь', 'декабрь');

// стоимость доставки по умолчанию
$orderDelivPriceDef = 0;


// способ доставки и стоимость

/*$arrDelivVar = array(
	"curier" => array("курьер", 	$_VARS['env']['price_curier']),
	"client" => array("самовывоз", 	$_VARS['env']['price_client'])	
);*/
$arrDelivVar = getDataToArray(SITE_PREFIX."_deliv_variant");




// расстояние доставки и стоимость

/*$arrDelivRange = array(
	"tarif1" => array("в пределах Третьего кольца", $_VARS['env']['tarif1']	),
	"tarif2" => array("в пределах МКАД", 			$_VARS['env']['tarif2']	),
	"tarif3" => array("в пределах МО", 				$_VARS['env']['tarif3']	)	
);*/
$arrDelivRange = getDataToArray(SITE_PREFIX."_deliv_range");





// способ дегустации и стоимость

/*$arrTestVar = array(
	"out" => array("выездная", 	$_VARS['env']['degust1']),
	"in"  => array("в салоне", 	$_VARS['env']['degust2'])	
);*/
$arrTestVar = getDataToArray(SITE_PREFIX."_test_variant");




// способы оплаты
$arrPayVar = array(
	"curier" 	=> "Наличными курьеру",
	"online" 	=> "Онлайн-оплата",
	"sb" 		=> "Безналичный расчет (для юридических лиц)"
);

// сообщения об ошибках
$arrMsg = array(
	"badAuth" => "Ошибка авторизации. Неверный адрес электронной почты или пароль.",
	"tryAuth" => "Попытка авторизации",
	"tryReg" => "Попытка регистрации"
);

// темы сообщений обратной связи
$arrThemeFB = array(
	'выбор темы',
	'благодарность',
	'жалоба',
	'просьба'
);
?>