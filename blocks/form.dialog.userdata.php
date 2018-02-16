<?
/*********************************/
/* диалоговое окно личные данные */
/*********************************/
?>
<script language="javascript">
$(document).ready(function(){

	$('.dialogUsedata').click(function()
	{
		$("#dialog-form-userdata").dialog("open");
		return false;
	})

	$("#dialog-form-userdata").dialog({
			autoOpen: false,
			height	: 600,
			width	: 650,
			modal	: true,			
			close	: function() {
				$('.resultUserdata').html('');
			}			
		});	
		
	var optionsUserdata = {
	  target: ".resultUserdata",	  
	  resetForm : false,
	  success : showResponse
	};
	
	function showResponse(responseText)
	{
		if(responseText == '1')
		{
			$('.resultUserdata').html('');
			document.location.reload();
		}
		//else alert(responseText)
	}

	$(".submitUserdata").click(function()
	{
		$('.resultUserdata').html('');
		$("#formUserdata").ajaxSubmit(optionsUserdata);
		return false;
	}) 
})
</script>

<div id="dialog-form-userdata" class="dialogUserdata" title="Личные данные">
	
	<p class="ui-state-error resultUserdata"></p>
	<!-- BEGIN форма авторизации -->
	<form id="formUserdata" action="/blocks/ajax.userdata.php" method="post">	
		<input type="hidden" name="userData" value="userData" />	
      <table align="center" cellpadding=7>
          <tr>
              <td align="right"><label for="user_patr">Фамилия:</label></td>
              <td align="right"><input type="text" name="user_patr" id="user_patr" value="<?=$user_patr?>" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  <tr>
              <td align="right"><label for="user_name">Имя:</label></td>
              <td align="right"><input type="text" name="user_name" id="user_name" value="<?=$user_name?>" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  <tr>
              <td align="right"><label for="user_surn">Отчество:</label></td>
              <td align="right"><input type="text" name="user_surn" id="user_surn" value="<?=$user_surn?>" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  
		  <tr>
              <td align="right"><label for="user_phone_c">Телефон:</label></td>
              <td align="right"><label>+7&nbsp;</label>
				<input type="text" name="user_phone_c" id="user_phone_c" value="<?=$user_phone_c?>" style="width:45px" maxlength=3 class="text ui-widget-content ui-corner-all" />
				<input type="text" name="user_phone_n" id="user_phone_n" value="<?=$user_phone_n?>" style="width:165px; margin-left:10px; display:block; float:right" maxlength=7 class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  <tr>
              <td align="right"><label for="user_mail">Email:</label></td>
              <td align="right"><input type="text" name="user_mail" id="user_mail" value="<?=$user_mail?>" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  <tr>
              <td align="right"><label for="user_pwd">Пароль:</label></td>
              <td align="right"><input type="password" name="user_pwd" id="user_pwd" value="<?=$user_pwd?>" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  <tr>
              <td align="right"><label for="user_pwd_2">Пароль еще раз:</label></td>
              <td align="right"><input type="password" name="user_pwd_2" id="user_pwd_2" value="<?=$user_pwd?>" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  <tr>
              <td align="right"><label for="user_bd">Дата рождения:</label></td>
              <td align="right">
			  	<?
				$def_d = $def_m = $def_y = 0;
				if($user_bd != '')
				{
					$date = explode('-', $user_bd, 3);
					if(is_array($date))
					{
						$def_d = $date[2];
						$def_m = $date[1];
						$def_y = $date[0];
					}					 
				}
				?>
			  	<select name="user_bd_d">
					<option value="0">день</option>
					<?
					echo printOption('D', $def_d);
					?>
				</select>
				<select name="user_bd_m">
					<option value="0">месяц</option>
					<?
					echo printOption('M', $def_m);
					?>
				</select>
				<select name="user_bd_y">
					<option value="0">год</option>
					<?
					echo printOption('Y', $def_y);
					?>
				</select>
				
			  </td>
          </tr>
		 
		  		
		<?
		$arr = unserialize($user_addr_1);
		$i = 0;
		$n = 'user_addr_1';
		if(!empty($arr))
		{					
			foreach($arr as $k1 => $v1)
			{
				?>
				<tr valign="top">
					<td align="right" ><label for="<?=$n.'['.$i.']'?>">Адрес доставки <?=($i + 1)?>:</label></td>
					<td align="right"><textarea name="<?=$n.'['.$i.']'?>"><?=$v1?></textarea></td>
				</tr>
				<?
				$i++;
			}
		}
		?> 
		<tr valign="top">
			<td align="right"><label for="<?=$n.'['.$i.']'?>">Адрес доставки <?=($i + 1)?>:</label></td>
			<td align="right"><textarea name="<?=$n.'['.$i.']'?>"></textarea></td>
		</tr>
             
         
		  
         <tr>
              <td align="right"><label for="user_card_number">Essence Card:</label></td>
              <td>
			  <?
			  for($i = 0; $i < 4; $i++)
			  {
			  	?>
				<input type="text" style="text-align:center" name="user_card_number[]" value="<?=$user_card_number[$i]?>" size=4 maxlength=4>                  
				<?
			  }
			  ?>
                  
              </td>
          </tr>	
		   <tr>
              <td align="right"><label for="user_subscribe">Получать новости:</label></td>
			  <?
			  $user_subscribe == '1' ? $ch = 'checked' : $ch = '';
			  ?>
              <td align="left"><input type="checkbox" name="user_subscribe" <?=$ch?> /> </td>
          </tr>
          <tr>
              <td></td>
              <td>
                  <input type="submit" class="submitUserdata" value="Сохранить">                  
              </td>
          </tr>				
      </table>	
	</form>
	<!-- END форма авторизации -->
	
</div>
