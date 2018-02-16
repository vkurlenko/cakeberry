<?
/*******************************************/
/* диалоговое окно авторизации/регистрации */
/*******************************************/
?>

<script language="javascript">
$(document).ready(function(){

	$('.topBlockPrivateEnter .noauth, .itemUserActivate').click(function()
	{
		$("#dialog-form").dialog("open");
		return false;
	})

	$("#dialog-form").dialog({
			<?
			if(!isset($autoOpen)) $autoOpen = "false";	
			//$autoOpen = "true";			
			?>
			autoOpen: <?=$autoOpen?>,
			height: 390,
			width: 650,
			modal: true,			
			close: function() {
				$('.resultAuth, .resultReg').html('');
			}			
		});	
		
	var optionsAuth = {
	  target: ".resultAuth",	  
	  resetForm : false,
	  success : showResponse
	};
	
	function showResponse(responseText)
	{
		if(responseText == '1')
		{
			$('.resultAuth').html('');
			document.location.reload();
		}
		//else alert(responseText)
	}

	$(".submitAuth").click(function()
	{
		$('.resultAuth').html('');
		$("#formAuth").ajaxSubmit(optionsAuth);
		return false;
	})	  	
	
	
	
	var optionsReg = {
	  target: ".resultReg",	  
	  resetForm : true,
	  success : showResponseReg
	};
	
	function showResponseReg(responseText)
	{
		if(responseText == '1')
		{
			
			$('.resultReg').html('');
			$('#formReg').html('<p class="ui-state-error resultReg">Письмо с контрольной строкой отправлено на указанный email.</p>');
			//document.location.reload();
		}
		//else alert(responseText)
	}

	$(".submitReg").click(function()
	{
		$('.resultReg').html('');
		$("#formReg").ajaxSubmit(optionsReg);
		return false;
	})	  
})
</script>

<div id="dialog-form" class="dialogAuth" title="Личный вход">
	
	<p class="ui-state-error resultAuth"></p>
	<!-- BEGIN форма авторизации -->
	<form id="formAuth" action="/blocks/ajax.auth.php" method="post">		
      <table align="left" cellpadding=7>
          <tr>
              <td align="right"><label for="auth_user_mail">Электронная почта:</label></td>
              <td><input type="text" name="auth_user_mail" id="auth_user_mail" value="" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
          <tr>
              <td align="right"><label for="auth_user_mail">Пароль:</label></td>
              <td><input type="password" name="auth_user_pwd" id="auth_user_pwd" value="" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
          <tr>
              <td></td>
              <td>
                  <input type="submit" class="submitAuth" value="Войти">
                  <a class="linkRestorePwd" href="#">Напомнить пароль</a>
              </td>
          </tr>				
      </table>	
	</form>
	<!-- END форма авторизации -->
	<div style="clear:both"></div>
	<!-- BEGIN форма регистрации -->
	<form id="formReg" action="/blocks/ajax.reg.php" method="post">
    
    	<p class="ui-state-error resultReg"></p>
    	
		<p class="formRegText">Если у Вас до сих пор нет личного входа на сайте Cakeberry, то,
		пожалуйста, зарегистрируйтесь. Для этого необходимо ввести адрес Вашей эл.почты. </p>
		<table align="left" cellpadding=7>
			<tr>
				<td align="right"><label for="reg_user_mail">Электронная почта:</label></td>
				<td><input type="text" name="reg_user_mail" id="reg_user_mail" value="" class="text ui-widget-content ui-corner-all" /></td>
			</tr>				
			<tr>
				<td></td>
				<td>
					<input type="submit" class="submitReg"  value="Зарегистрироваться">						
				</td>
			</tr>				
		</table>	
	</form>
    <?
	/*}*/
	?>
	<!-- END форма регистрации -->
</div>
