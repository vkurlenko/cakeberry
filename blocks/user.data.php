<div class="userData">
	<h2>Личные данные</h2>
	<?
	$user_id 	= 0;
	$user_name 	= "";
	$user_patr 	= "";
	$user_surn 	= "";
	$user_bd	= "";
	$user_addr= "";
	$user_addr_2= "";
	$user_phone = "";
	$user_phone_c = "";
	$user_phone_n = "";
	$user_mail  = "";
	$user_card	= false;
	$user_card_number = str_split('0000000000000000', 4);
	$user_discount = 0;
	$user_pwd  = "";
	$user_subscribe = '1';
	
	// если пользователь авторизован
	if(userAuth())
	{
		$user_id = $_SESSION['user_id'];

		// прочитаем его личные данные
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
				WHERE id = ".$user_id;
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$_SESSION['user_access'] = true;
		
			$row = $user_data = mysql_fetch_array($res);
	
			$user_name_full	= $row['user_patr']." ".$row['user_name']." ".$row['user_surn'];			
			$user_name 		= $row['user_name'];
			$user_patr 		= $row['user_patr'];
			$user_surn 		= $row['user_surn'];
			$user_bd		= $row['user_birth_day'];
			$user_bd_str	= $row['user_birth_day'] == '0000-00-00' ? '' : format_date_to_str($row['user_birth_day'], $sel_2 = " ", $year = true);
			$user_addr		= unserialize($row['user_addr_1']);
			$user_addr_1	= $row['user_addr_1'];
			$user_phone		= $row['user_phone'];
			$user_mail  	= $row['user_mail'];
			$user_pwd  		= $row['user_pwd'];
			$user_card		= $row['user_card'] == true ? "" : "нет";
			$user_subscribe = $row['user_subscribe'];
			
			
			$user_card_number	= str_split($row['user_card_number'], 4);
			
			/* скидка для владельца карты */
			$user_discount = 0;
			
			if($row['user_card'] == '1')
			{
				// скидка для всех владельцев карт
				if(isset($_VARS['env']['essense_discount']))
					$user_discount = intval($_VARS['env']['essense_discount']);

				// если есть персональная скидка
				if(intval($row['user_discount']) > 0)
					$user_discount = intval($row['user_discount']);
			}
			/* /скидка для владельца карты */
			
			
			$user_discount 	= $user_discount > 0 ? $user_discount." %" : "нет";	
			
			
			/*if(is_array(explode('|', $user_phone, 2)))	
			{
				$arr = explode('|', $user_phone, 2);
				if(!isset($arr[1]))
					$arr[1] = '';
				$user_phone_c = $arr[0];
				$user_phone_n = $arr[1];
			}*/	
			
			
			$arr = array(
				'code' 	=> substr($user_phone, 0, 3),
				'num'	=> substr($user_phone, 3)
			);	
			$user_phone_c = $arr['code'];
			$user_phone_n = $arr['num'];						 
		}	
	}	
	
	?>
						
	ФИО: <?=$user_name_full?><br />
	ДР: <?=$user_bd_str?><br />
	<br />
	<?
	$i = 1;
	foreach($user_addr as $k => $v)
	{
		?>Адрес доставки <?=$i++?>: <?=$v?><br /><?
	}
	?>
	
	
	Телефон: +7 (<?=$user_phone_c?>) <?=$user_phone_n?><br />
	<br />
	Essence Card: <?=$user_card?> <? if($row['user_card'] == true) echo implode('-', $user_card_number);?><!--<span>(обновляются автоматически)</span>--><br />
	
	Скидка постоянного клиента: <?=$user_discount?> <!--<span>(обновляются автоматически)</span>--><br />
	<a class="dialogUsedata" href="#">Внести изменения</a>	
</div>

<?
if($row['user_card'] == true && $row['user_discount'] > 0)
{
	?><div class="userCard"><img src="/img/pic/essencecard.jpg" width="305" height="204" /></div><?
}
?>
