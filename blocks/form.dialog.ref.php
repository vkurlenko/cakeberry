<?
/***********************************/
/* диалоговое окно заказать звонок */
/***********************************/

if(userAuth() && isset($_GET['param2']))
{
	// если пользователь авторизован
	$arrUserData = userData($_SESSION['user_id']);
	
	// id продукта
	$itemId = $_GET['param2'];
	
	// проверим покупал ли пользователь этот продукт
	if(is_array(unserialize($arrUserData['user_pay'])))
	{
		$arr = unserialize($arrUserData['user_pay']);
		
		// если да, то форма отзыва
		if(in_array($itemId, $arr))
		{
		
			$faqUserName = $arrUserData['user_patr'].' '.$arrUserData['user_name'].' '.$arrUserData['user_surn'];
			
			// проверим, оставлял ли он уже отзыв о продукте
			$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_faq`
					WHERE faqUserName = '".$faqUserName."'
					AND faqPerson = '".$itemId."'";
			$res = mysql_query($sql);
			
			if($res && mysql_num_rows($res) > 0)
			{
				?>
				<div id="dialog-form-ref" class="dialogRef" title="Оставить отзыв">
					<p class="ui-state-error">Вы уже оставили отзыв об этом продукте</p>
				</div>
				<?
			}
			else
			{
				?>
			<div id="dialog-form-ref" class="dialogRef" title="Оставить отзыв">			
			
				<!-- BEGIN форма  -->
				<P class='result ui-state-error'></P>
				<form id="formRef" action="/blocks/ajax.ref.php" method="post">	
				<input type="hidden" name="itemId" value="<?=$itemId?>" />	
				  <table align="center" cellpadding=7>
					  
					  <tr>
						  <td align="right"><label for="userMsg">Ваш отзыв о продукте:</label></td>
						  <td>
							<textarea name="userMsg"></textarea>
						  </td>
					  </tr>
					  <tr>
						  <td align="right"><label for="userRate">Оцените продукт:</label></td>
						  <td>
							<select name="userRate">
								<?
								for($i = 5; $i >= 0; $i--) 
								{
									$sel = '';
									if($i == 5) $sel = 'selected';
									?><option value="<?=$i?>" <?=$sel?>><?=$i?></option><?
								}
								?>
							</select>
						  </td>
					  </tr>
					  
					  <tr>
						  <td></td>
						  <td>
							  <input class='formSubmitRef' type="submit" value="Отправить">                  
						  </td>
					  </tr>				
				  </table>	
				</form>
				<!-- END форма  -->	
			</div>
				<?
			}		
		}
	}	
}

?>
