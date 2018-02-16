<?
// из vars.php
$arrDelivVar = $arrDelivVar;
$arrDelivRange = $arrDelivRange;

// адрес пункта самовывоза
$address = '';
if(isset($_VARS['env']['address']))
	$address = $_VARS['env']['address'];
?> 

<div style="clear:both"></div>
<style>
.var1, .var2{display:none}
.var2 span{margin-top:5px; display:block}
</style>
<script language="javascript">
	/*// способы доставки
	var arrDelivVar = new Array();	
	<?
	foreach($arrDelivVar as $k => $v)
	{
		?>
		arrDelivVar['<?=$k?>'] = <?=$v[1]?>;
		<?
	}
	?>
	
	// расстояние доставки
	var arrDelivRange = new Array();
	<?
	foreach($arrDelivRange as $k => $v)
	{
		?>
		arrDelivRange['<?=$k?>'] = <?=$v[1]?>;
		<?
	}
	?>*/
	
	$(document).ready(function()
	{
		/*// стоимость доставки минимальная
		var orderDelivPriceDef = <?=$orderDelivPriceDef?>;
		
		function calc()
		{
			var orderDelivPrice = 0;
			
			// стоимость вида доставки
			var orderDelivVar = arrDelivVar[$('.orderDelivVar').attr('value')];
			
			// стоимость расстояния доставки
			var orderDelivRange = arrDelivRange[$('.orderDelivRange').attr('value')];
			
			if(orderDelivVar != 0)
			{
				// если не самовывоз
				orderDelivPrice = orderDelivPriceDef + orderDelivVar + orderDelivRange;
			}
			// если самовывоз
			else orderDelivPrice = 0;	
			
			return orderDelivPrice;
		}
					
		
		// стоимость доставки
		$('.orderP9 span i').text(calc());
		
		// суммарная стоимость заказанных продуктов + стоимость доставки
		var orderSum = <?=$fullPrice?> + calc();
		$('.priceResult span i').text(orderSum);*/
		
		$('.orderDelivVar, .orderDelivRange, .orderTestVar').change(function()
		{
			/*// стоимость доставки
			$('.orderP9 span i').text(calc());
			
			// суммарная стоимость заказанных продуктов + стоимость доставки
			var orderSum = <?=$fullPrice?> + calc();
			$('.priceResult span i').text(orderSum);	*/	
			
			document.location.href = '/order/'+$(this).attr('name')+'='+$(this).attr('value');
		})		
		
		// если самовывоз, то покажем адрес пункта выдачи, 
		// иначе - расстояние доставки
		if($('.orderDelivVar').attr('value') == 'client')
			$('.var2').show();
		else
			$('.var1').show();
			
		$('.orderPayVarSelect input').click(function()
		{
			if($(this).attr('value') == 'sb')
				$('.clientName').show('fast')
			else
				$('.clientName').hide('fast')
//			alert($(this).attr('value'))
		})
		
		/* ВВОД НОМЕРА ТЕЛЕФОНА */
	//$('.inputCardNumber:eq(1)').attr('disabled', true)
		
	$('.orderCode, .orderPhone').keyup(function()
	{
		var thisLen = $(this).val().length;
		var maxLen  = $(this).attr('maxlength')
		
		if(thisLen == maxLen)
		{
			$(this).next('input').focus()
		}
	})
	/* /ВВОД НОМЕРА ТЕЛЕФОНА */
			
	})
</script>


<?
	// значения формы по умолчанию
	$user_data = array(
		'orderDelivDay'			=> 0,
		'orderDelivMonth'		=> 0,
		'orderDelivTime'		=> '0',
		'orderClientName' 		=> '',
		'orderClientAddress' 	=> '',
		'orderClientMail' 		=> '',
		'orderClientPhoneCode' 	=> '',
		'orderClientPhoneNum'	=> '',
		'orderClientComment'	=> '',
		'orderClientCard' 		=> str_split('0000000000000000', 4),
		'orderClientDiscount' 	=> 0
	);
	
	foreach($user_data as $k => $v)
	{
		if(isset($_POST[$k])) $user_data[$k] = $_POST[$k];		
	}
	
	// если пользователь авторизован
	if(!empty($_SESSION['user_id']))
	{
		$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
				WHERE id = ".$_SESSION['user_id'];
		$res = mysql_query($sql);
		
		if($res && mysql_num_rows($res) > 0)
		{
			$row = mysql_fetch_array($res);
			
			/*$phone = explode('|', $row['user_phone'], 2);
			if(!isset($phone[1]))
				$phone[1] = '';*/
			
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
			
			$arrP = array(
				'code' 	=> substr($row['user_phone'], 0, 3),
				'num'	=> substr($row['user_phone'], 3)
			);	
				
			
			$user_data = array(
				'orderDelivDay'			=> 0,
				'orderDelivMonth'		=> 0,
				'orderDelivTime'		=> '17:00',
				'orderClientName' 		=> $row['user_patr'].' '.$row['user_name'].' '.$row['user_surn'],
				'orderClientAddress' 	=> $row['user_addr_1'],
				'orderClientMail' 		=> $row['user_mail'],
				'orderClientPhoneCode' 	=> $arrP['code'],
				'orderClientPhoneNum'	=> $arrP['num'],
				'orderClientComment'	=> '',
				'orderClientCard' 		=> str_split($row['user_card_number'], 4),
				'orderClientDiscount' 	=> $user_discount,
				'orderClientIsCard'		=> $row['user_card']
			);		
		}
	}
?>


<form name="orderForm" id="orderForm" action="" method="post" enctype="multipart/form-data">
	<div class="orderDelivery">
		<div class="orderDeliveryImg"><img src="/img/pic/car.png" width="111"  /></div>
		<div class="orderP6">
			Вид доставки<br />
			<select name="orderDelivVar" class="orderDelivVar">
				<!--<option value="">---</option>-->
				<?
				// установим способдоставки по умолчанию 
				// (первый элемент массива)
				if(!isset($_SESSION['orderDelivVar']))
				{
					foreach($arrDelivVar as $k => $v)
					{
						$_SESSION['orderDelivVar'] = $k;
						break;	
					}					
				}
				
				foreach($arrDelivVar as $k => $v)
				{
					$sel = '';
					if(isset($_SESSION['orderDelivVar']) && $_SESSION['orderDelivVar'] == $k) 
						$sel = 'selected';
					?><option value="<?=$k?>" <?=$sel?>><?=$v[0]?></option>
					<?				
				}
				?>			
			</select>
		</div>
		<div class="orderP7 var1">
				
			Расстояние<br />
			<select name="orderDelivRange" class="orderDelivRange">
				<!--<option value="">---</option>-->
				<?
				foreach($arrDelivRange as $k => $v)
				{
					$sel = '';
					if(isset($_SESSION['orderDelivRange']) && $_SESSION['orderDelivRange'] == $k) $sel = 'selected';
					?><option value="<?=$k?>" <?=$sel?>><?=$v[0]?></option>
					<?				
				}
				?>			
			</select>
		</div>
		
		<div class="orderP7 var2">
				
			Адрес пункта самовывоза<br />
			<span><?=$address?></span>
		</div>
		
		<div class="orderP8">&nbsp;</div>
		<?
		// считаем стоимость доставки
		$delivPrice = 0;
		
		if(isset($_SESSION['orderDelivVar']) && $_SESSION['orderDelivVar'] == 'curier' && (!isset($user_data['orderClientIsCard']) || $user_data['orderClientIsCard'] == '0'))
		{
		
			//if((isset($user_data['orderClientIsCard']) && $user_data['orderClientIsCard'] == '0') || )
			
			$delivPrice += $arrDelivVar['curier'][1];
			
			if(isset($_SESSION['orderDelivRange']))
			{
				$delivPrice += $arrDelivRange[$_SESSION['orderDelivRange']][1];
			}		
		}
		?>
		<div class="orderP9">Цена: <span><i><?=$delivPrice?></i> Р</span><!--<input type="hidden" name="orderDelivPrice" class="orderDelivPrice" value="0">--></div>
		<div style="clear:both"></div>
		
		<?
		// считаем стоимость заказа с учетом доставки
		$fullPrice += $delivPrice;	
		?>		
		
	</div>
	
	<!---->
	<div class="orderTest">
		<div class="orderDeliveryImg"><img src="/img/pic/test.png" width="109" height="69" /></div>
		<div class="orderP6">
			Предварительная дегустация начинок<br />
			<select name="orderTestVar" class="orderTestVar">
				<!--<option value="">---</option>-->
				<?
				// установим способ доставки по умолчанию 
				// (первый элемент массива)
				if(!isset($_SESSION['orderTestVar']))
				{
					foreach($arrTestVar as $k => $v)
					{
						$_SESSION['orderTestVar'] = $k;
						break;	
					}					
				}
				
				foreach($arrTestVar as $k => $v)
				{
					$sel = '';
					if(isset($_SESSION['orderTestVar']) && $_SESSION['orderTestVar'] == $k) 
						$sel = 'selected';
					?><option value="<?=$k?>" <?=$sel?>><?=$v[0]?></option>
					<?				
				}
				?>			
			</select>
		</div>
		<div class="orderP7">&nbsp;</div>
		<div class="orderP8">&nbsp;</div>
		<?
		// считаем стоимость дегустации
		$testPrice = 0;
		if(isset($_SESSION['orderTestVar'])  && (!isset($user_data['orderClientIsCard']) || $user_data['orderClientIsCard'] == '0'))
		{
			$testPrice += $arrTestVar[$_SESSION['orderTestVar']][1];				
		}
		?>
		<div class="orderP9">Цена: <span><i><?=$testPrice?></i> Р</span><!--<input type="hidden" name="orderDelivPrice" class="orderDelivPrice" value="0">--></div>
		<div style="clear:both"></div>
		
		<?
		// считаем стоимость заказа с учетом доставки
		$fullPrice += $testPrice;	
		?>
		
		<div class="priceResult">Общая стоимость заказа: <span><i><?=number_format($fullPrice, 0, ',', ' ')?></i> Р</span></div>
	</div>
	<!---->
	
	<?
	//////////////
	?>
	
	<div class="orderForm">
		<div class="orderP10">
			<table cellspacing="0" cellpadding="0">
				<tr>
					<td>Дата доставки:</td>
					<td class="data">
						<select name="orderDelivDay">
							<option value="0">день</option>
							<?
							if(isset($_SESSION['orderDelivDay']))
								$user_data['orderDelivDay'] = $_SESSION['orderDelivDay'];
							?> 
							<?=printOption("D", $user_data['orderDelivDay'])?>
						</select>
						<select name="orderDelivMonth">
							<option value="0">месяц</option>
							<?
							if(isset($_SESSION['orderDelivMonth']))
							{
								$arrMonth = array('', 'января', 'февраля', 'марта', 'апреля', 'мая', 'июня', 
												'июля', 'августа', 'сентября', 'октября', 'ноября', 'декабря');
								foreach($arrMonth as $k => $v)
								{
									if($v == $_SESSION['orderDelivMonth'])
										$user_data['orderDelivMonth'] = $k;
								}				
								
							}								
							?> 
							<?=printOption("M", $user_data['orderDelivMonth'])?>
						</select>
					</td>
				</tr>
				<tr>
					<td>Время доставки:</td>
					<td class="data">
						<?
							if(isset($_SESSION['orderDelivTime']))
								$user_data['orderDelivTime'] = $_SESSION['orderDelivTime'];
							?> 
						<select name="orderDelivTime">
							<option value="0:00">время</option>
							<?=printOption("T", $user_data['orderDelivTime'])?>
						</select>
					</td>
				</tr>
				<?
				if(isset($_SESSION['orderClientName']))
					$user_data['orderClientName'] = $_SESSION['orderClientName'];
				?> 
				<tr>
					<td>ФИО:</td><td class="data"><input name="orderClientName" type="text" value="<?=$user_data['orderClientName']?>" /></td>
				</tr>
				
				<tr>
					<td>Адрес:</td>
					<td class="data">
					
						<?
						$arrAddr = unserialize($user_data['orderClientAddress']);
						
						/*if(isset($_SESSION['orderClientAddress']))
							$user_data['orderClientAddress'] = $_SESSION['orderClientAddress'];*/
						
						if(is_array($arrAddr) && !empty($arrAddr))
						{
							?>
							<select class="selectAddr" name="orderClientAddress">
							<?
							foreach($arrAddr as $k => $v)
							{
								?><option value="<?=$v?>"><?=$v?></option><?
							}
							?>
							</select>
							<?
						}
						else
						{
							if(empty($arrAddr))
								$user_data['orderClientAddress'] = '';
							elseif(isset($_SESSION['orderClientAddress']))
								$user_data['orderClientAddress'] = $_SESSION['orderClientAddress'];
							
							?> 
							<input name="orderClientAddress" type="text" value="<?=$user_data['orderClientAddress']?>" />
							<?
						}
						?>
					</td>
				</tr>
				<?
				if(isset($_SESSION['orderClientMail']))
					$user_data['orderClientMail'] = $_SESSION['orderClientMail'];
				?>
				<tr>
					<td>E-mail:</td><td class="data"><input name="orderClientMail" type="text" value="<?=$user_data['orderClientMail']?>" /></td>
				</tr>
				<?
				if(isset($_SESSION['orderClientPhoneNum']))
				{
					$user_data['orderClientPhoneCode'] = substr($_SESSION['orderClientPhoneNum'], 0, 3);
					$user_data['orderClientPhoneNum'] = substr($_SESSION['orderClientPhoneNum'], 3);
				}					
				?>
				<tr>
					<td>Телефон: <span>+7</span></td>
					<td class="data"><input name="orderClientPhoneCode" type="text" value="<?=$user_data['orderClientPhoneCode']?>" class="orderCode" maxlength="3" /><input name="orderClientPhoneNum" class="orderPhone" type="text" value="<?=$user_data['orderClientPhoneNum']?>" maxlength="7" /></td>
				</tr>
			</table>
		</div>
		
		<div class="orderP11">
			<span>Ваши комментарии к заказу (если есть):</span>
			<?
			if(isset($_SESSION['orderClientComment']))
				$user_data['orderClientComment'] = $_SESSION['orderClientComment'];
			?>
			<textarea name="orderClientComment"><?=$user_data['orderClientComment']?></textarea>
		</div>
		
		<div class="orderP12">
			Essence Card (если есть):<br />
			<?
			if(isset($_SESSION['orderClientCard']))
				$user_data['orderClientCard'] = str_split(str_replace(' ', '', $_SESSION['orderClientCard']), 4);
			?>
			<!--<input name="orderClientCard" type="text" value="<?=$user_data['orderClientCard']?>" />-->
			<?
			//printArray($user_data['orderClientCard']);
			for($i = 0; $i < 4; $i++)
			{
				?><input type="text" name="orderClientCard[<?=$i?>]" value="<?=strval($user_data['orderClientCard'][$i])?>" size="4" maxlength="4" />
				<?
			}
			?> 
		</div>
		
		<div class="priceResult2">
			Скидка постоянного клиента: <?
			
			if(isset($_SESSION['user_access']) && $_SESSION['user_access'] == true)
			{
				echo $user_data['orderClientDiscount'].'%';
			}
			else
			{
				?><a class="itemUserActivate" href="#">активировать через личный вход</a><?
			}		
			
			
			// считаем стоимость заказа с учетом скидки
			$fullPriceDiscount = ceil($goodsPrice - ($goodsPrice / 100) * intval($user_data['orderClientDiscount']));
			$fullPriceDiscount += $delivPrice + $testPrice;
			?>
			<span class="priceResult2Text">Стоимость заказа<? if(isset($_SESSION['user_access']) && $_SESSION['user_access'] == true) echo ' с учетом вашей скидки';?>: <span><?=number_format($fullPriceDiscount, 0, ',', ' ')?> Р</span></span>
			<input name="orderFullPriceDiscount" type="hidden" value="<?=$fullPriceDiscount?>" />
		</div>
		
		<div style="clear:both"></div>
	</div>
	
	<div class="orderPayVar">
	
		<div class="orderPayVarSelect">
			Выберите способ оплаты:
			<div>
			<?
			$default = 'curier';
			if(isset($_SESSION['orderPayVar']))
			{
				foreach($arrPayVar as $k => $v)
				{
					if($v == $_SESSION['orderPayVar'])
						$default = $k;
				}
			}
			
			
				
				
			
			foreach($arrPayVar as $k => $v)
			{
				$i++;
				$sel = "";
				if($k == $default) 
					$sel = "checked";
				?><input type="radio" id="arrPayVar<?=$i?>" name="orderPayVar" value="<?=$k?>" <?=$sel?> /><label for="arrPayVar<?=$i?>"><?=$v?></label><div style="clear:both"></div><?
			}
			?>	
			</div>	
		</div>
		
		<div class="clientName" style="">
			Наименование организации: <br /><input type="text" name="clientName" placeholder="Наименование организации"  />
		</div>
		
		<p>Все заказы выполняются на основании 100% предоплаты</p>
		
		<div style="clear:both"></div>
	</div>
	
	<div class="buttonConfirm orderConfirm">
		<?
		if(isset($_SESSION['order_mode']) && $_SESSION['order_mode'] == 'edit')
			$str = 'Сохранить изменения';
		else
			$str = 'Подтвердить заказ';
		?>
		<a href="#"><?=$str?></a>
	</div>
	<div style="clear:both"></div>
</form>				