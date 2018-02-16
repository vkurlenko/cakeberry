<?
include_once $_SERVER['DOCUMENT_ROOT'].'/config.php';
include_once DIR_CLASS.SL.'class.db.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/functions.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/functions_sql.php';
include_once $_SERVER['DOCUMENT_ROOT'].'/modules/shop/shop.class.php';


/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* преобразование числа в строку */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
class NumToText
{
   var $Mant = array(); // описания мантисс
   // к примеру ('рубль', 'рубля', 'рублей')
   // или ('метр', 'метра', 'метров')
   var $Expon = array(); // описания экспонент
   // к примеру ('копейка', 'копейки', 'копеек')

   function NumToText()
   {
   }

   // установка описания мантисс
   function SetMant($mant)
   {
      $this->Mant = $mant;
   }

   // установка описания экспонент
   function SetExpon($expon)
   {
      $this->Expon = $expon;
   }

   // функция возвращает необходимый индекс описаний разряда
   // ('миллион', 'миллиона', 'миллионов') для числа $ins
   // например для 29 вернется 2 (миллионов)
   // $ins максимум два числа
   function DescrIdx($ins)
   {
      if(intval($ins/10) == 1) // числа 10 - 19: 10 миллионов, 17 миллионов
      return 2;
      else
      {
         // для остальных десятков возьмем единицу
         $tmp = $ins%10;
         if($tmp == 1) // 1: 21 миллион, 1 миллион
         return 0;
         else if($tmp >= 2 && $tmp <= 4)
         return 1; // 2-4: 62 миллиона
         else
         return 2; // 5-9 48 миллионов
      }
   }

   // IN: $in - число,
   // $raz - разряд числа - 1, 1000, 1000000 и т.д.
   // внутри функции число $in меняется
   // $ar_descr - массив описаний разряда ('миллион', 'миллиона', 'миллионов') и т.д.
   // $fem - признак женского рода разряда числа (true для тысячи)
   function DescrSot(&$in, $raz, $ar_descr, $fem = false)
   {
      $ret = '';

      $conv = intval($in / $raz);
      $in %= $raz;

      $descr = $ar_descr[ $this->DescrIdx($conv%100) ];

      if($conv >= 100)
      {
         $Sot = array('сто', 'двести', 'триста', 'четыреста', 'пятьсот',
         'шестьсот', 'семьсот', 'восемьсот', 'девятьсот');
         $ret = $Sot[intval($conv/100) - 1] . ' ';
         $conv %= 100;
      }

      if($conv >= 10)
      {
         $i = intval($conv / 10);
         if($i == 1)
         {
            $DesEd = array('десять', 'одиннадцать', 'двенадцать', 'тринадцать',
            'четырнадцать', 'пятнадцать', 'шестнадцать', 'семнадцать',
            'восемнадцать', 'девятнадцать' );
            $ret .= $DesEd[ $conv - 10 ] . ' ';
            $ret .= $descr;
            // возвращаемся здесь
            return $ret;
         }
         $Des = array('двадцать', 'тридцать', 'сорок', 'пятьдесят', 'шестьдесят',
         'семьдесят', 'восемьдесят', 'девяносто' );
         $ret .= $Des[$i - 2] . ' ';
      }

      $i = $conv % 10;
      if($i > 0)
      {
         if( $fem && ($i==1 || $i==2) )
         {
            // для женского рода (сто одна тысяча)
            $Ed = array('одна', 'две');
            $ret .= $Ed[$i - 1] . ' ';
         }
         else
         {
            $Ed = array('один', 'два', 'три', 'четыре', 'пять',
            'шесть', 'семь', 'восемь', 'девять' );
            $ret .= $Ed[$i - 1] . ' ';
         }
      }
      $ret .= $descr;
      return $ret;
   }

   // IN: $sum - число, например 1256.18
   function Convert($sum)
   {
      $ret = '';

      // имена данных перменных остались от предыдущей версии
      // когда скрипт конвертировал только денежные суммы
      $Kop = 0;
      $Rub = 0;

      $sum = trim($sum);
      // удалим пробелы внутри числа
      $sum = str_replace(' ', '', $sum);

      // флаг отрицательного числа
      $sign = false;
      if($sum[0] == '-')
      {
         $sum = substr($sum, 1);
         $sign = true;
      }

      // заменим запятую на точку, если она есть
      $sum = str_replace(',', '.', $sum);

      $Rub = intval($sum);
      $Kop = $sum*100 - $Rub*100;

      if($Rub)
      {
         // значение $Rub изменяется внутри функции DescrSot
         // новое значение: $Rub %= 1000000000 для миллиарда
         if($Rub >= 1000000000)
         $ret .= $this->DescrSot($Rub, 1000000000,
         array('миллиард', 'миллиарда', 'миллиардов')) . ' ';
         if($Rub >= 1000000)
         $ret .= $this->DescrSot($Rub, 1000000,
         array('миллион', 'миллиона', 'миллионов') ) . ' ';
         if($Rub >= 1000)
         $ret .= $this->DescrSot($Rub, 1000,
         array('тысяча', 'тысячи', 'тысяч'), true) . ' ';

         $ret .= $this->DescrSot($Rub, 1, $this->Mant) . ' ';

         // если необходимо поднимем регистр первой буквы
         //$ret[0] = chr( ord($ret[0]) + ord('A') - ord('a') );
         // для корректно локализованных систем можно закрыть верхнюю строку
         // и раскомментировать следующую (для легкости сопровождения)
		 // echo mb_substr($ret, 0, 1);
          //$ret = mb_strtoupper(mb_substr($ret, 0, 1)).mb_substr($ret, 1);
		 
      }
      if($Kop < 10)
      $ret .= '0';
      $ret .= $Kop . ' ' . $this->Expon[ $this->DescrIdx($Kop) ];

      // если число было отрицательным добавим минус
      if($sign)
      $ret = '-' . $ret;
      return $ret;
   }
}

class ManyToText extends NumToText
{
   function ManyToText()
   {
      $this->SetMant( array('рубль', 'рубля', 'рублей') );
      $this->SetExpon( array('копейка', 'копейки', 'копеек') );
   }
}

class MetrToText extends NumToText
{
   function MetrToText()
   {
      $this->SetMant( array('метр', 'метра', 'метров') );
      $this->SetExpon( array('сантиметр', 'сантиметра', 'сантиметров') );
   }
}
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/* /преобразование числа в строку */
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/


function datetostr($str)
{
	$arr = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
	$datestr = explode(' ', $str);
	$d = explode('-', $datestr[0]);
	
	return $d[2].' '.$arr[intval($d[1])].' '.$d[0];
	
	
}



$arrBlank = array(
	'cmp'			=> 'ООО "Эссенция"',
	'cmpAddress'	=> '119071, Москва, Ленинский проспект, 15',
	'cmpINN'		=> '7725751110',
	'cmpKPP'		=> '772501001',
	'cmpBank'		=> 'Банк ВТБ 24 (ЗАО)',
	'cmpInvoice'	=> '40702810000000039518',
	'cmpBIK'		=> '44525716',
	'cmpDirector'	=> 'Шердани Н.З.',
	'cmpAccountant'	=> 'Шердани Н.З.',	
	
	'bankInvoice'	=> '30101810100000000716'/*,
	
	'invoiceNum'	=> 'B-007-14/DE-001-14/1',
	'invoiceDate'	=> '07 апреля 2014',	
	'clientName'	=> 'ИП Чернов В. К.',
	'clientPayer'	=> 'ИП Чернов В. К.'*/
);

$arrItems = array();



if(isset($_GET['id']))
{
	$tbl1 = $_VARS['tbl_prefix']."_order_blank";
	$tbl2 = $_VARS['tbl_prefix']."_order";
	$sql = "SELECT * FROM $tbl1, $tbl2
			WHERE $tbl1.order_id = ".$_GET['id']." AND $tbl2.id = ".$_GET['id'];
	$res = mysql_query($sql);
	//echo $sql;
	if($res && mysql_num_rows($res) > 0)
	{
		$row = mysql_fetch_assoc($res);
		//printArray($row);
		
			
		$arrBlank['invoiceNum']  = $row['order_num'];
		$arrBlank['invoiceDate'] = datetostr($row['order_date']);
		$arrBlank['clientName']  = $row['client_urname'];
		$arrBlank['clientPayer'] = $row['client_payer'];
				
		$arrItems = unserialize($row['order_arr_goods']);
		$mt = new ManyToText();	
		$ts = $mt -> Convert($row['sum_full']);
		
		$arrTotal = array(
			'total' 	=> $row['sum_full'],
			'totalNDS'	=> 0,
			'totalSum'	=> $row['sum_full'],
			'totalSumString' => $ts //'Тридцать тысяч'
		);	
	}	
}
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>СЧЕТ № <?=$arrBlank['invoiceNum']?> от  <?=$arrBlank['invoiceDate']?>г.</title>
<style>
#orderBlank{width:800px; border:1px solid black; padding:20px}
</style>
<script language="javascript">
	window.print();
</script>
</head>

<body>
	<div id="orderBlank" class="">
		
		<p><?=$arrBlank['cmp']?></p>
		
		<p>Адрес: <?=$arrBlank['cmpAddress']?></p>
	
		<table width="100%" border="1" cellspacing="0">
			<!--<caption></caption>-->
			<tr>
				<td>ИНН <?=$arrBlank['cmpINN']?></td><td>КПП <?=$arrBlank['cmpKPP']?></td>
				<td rowspan=2 valign="bottom">Сч. №</td><td rowspan=2 valign="bottom"><?=$arrBlank['cmpInvoice']?></td>				
			</tr>
			
			<tr>
				<td colspan="2">Получатель<br /><?=$arrBlank['cmp']?></td>				
			</tr>
			
			<tr>
				<td colspan="2" rowspan="2">Банк получателя<br /><?=$arrBlank['cmpBank']?></td>
				<td>БИК</td><td><?=$arrBlank['cmpBIK']?></td>
			</tr>
			
			<tr>
				<td>Сч. №</td><td><?=$arrBlank['bankInvoice']?></td>
			</tr>
		</table>
		
		<p align="center"><strong>СЧЕТ № <?=$arrBlank['invoiceNum']?> от  <?=$arrBlank['invoiceDate']?>г.</strong></p>
		
		<p>
		Заказчик: <?=$arrBlank['clientName']?><br />	
		Плательщик: <?=$arrBlank['clientPayer']?>	
		</p>
		
		<table width="100%" border="1" cellspacing="0">
			<tr align="center">
				<td>№</td><td>Наименование товара</td><td>Единица измерения</td><td>Количество</td><td>Цена</td><td>Сумма</td>
			</tr>
			
			<?
			$i = 1;
			foreach($arrItems as $k => $v)
			{
			?>
			<tr>
				<td><?=$i++?></td>
				<td><?=$v['itemName']?></td>
				<td align="center">шт.</td>
				<td align="center"><?=$v['itemNum']?></td>
				<td align="right"><?=$v['itemPrice']?></td>
				<td align="right"><?=$v['itemSum']?></td>
			</tr>
			<?
			}
			?>			
			<tr>
				<td colspan="5" align="right"><strong>Итого (с учетом скидки):</strong></td><td><strong><?=$arrTotal['total']?></strong></td>
			</tr>
			<tr>
				<td colspan="5" align="right"><strong>Без налога (НДС):</strong></td><td><strong><?=$arrTotal['totalNDS']?></strong></td>
			</tr>
			<tr>
				<td colspan="5" align="right"><strong>Всего к оплате:</strong></td><td><strong><?=$arrTotal['totalSum']?></strong></td>
			</tr>		
		</table>
		
		<p>
		
		Всего наименований <?=$i-1?>, на сумму <?=$arrTotal['totalSum']?> рублей , НДС не облагается<br />
		<strong><?=$arrTotal['totalSumString']?> , НДС не облагается	</strong>		<br />
		<em>Оригинал счета будет передан при доставке заказа.</em>			
		
		</p>
		
		<p>Руководитель предприятия_____________________ (<?=$arrBlank['cmpDirector']?>)</p>
		<p>Главный бухгалтер____________________________ (<?=$arrBlank['cmpAccountant']?>)</p>
		
		
	</div>


</body>
</html>
