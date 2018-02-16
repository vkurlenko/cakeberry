<?
/***********************************/
/* диалоговое окно заказать звонок */
/***********************************/?>
<div id="dialog-form-call" class="dialogCall" title="Заказать звонок">
	
	<!-- BEGIN форма  -->
	<P class='result'></P>
	<form id="formCall" action="/blocks/mail.call.php" method="post">		
      <table align="center" cellpadding=7>
          <tr>
              <td align="right"><label for="call_name">Ваше имя:</label></td>
              <td><input type="text" name="call_name" id="call_name" value="<?=$arrForm['userName']?>" style="display:block; float:right" class="text ui-widget-content ui-corner-all" /></td>
          </tr>
		  <tr>
              <td align="right"><label for="call_phone">Ваш номер телефона:</label></td>
              <td>
				<label>+7&nbsp;</label>
				<input type="text" name="call_code" id="call_code" value="<?=$arrForm['userCode']?>" style="width:45px" class="text ui-widget-content ui-corner-all" />
				<input type="text" name="call_phone" id="call_phone" value="<?=$arrForm['userNum']?>" style="width:165px; margin-left:10px; display:block; float:right" class="text ui-widget-content ui-corner-all" />
			</td>
          </tr>
          
          <tr>
              <td></td>
              <td>
                  <input class='formSubmit' type="submit" value="Отправить">                  
              </td>
          </tr>				
      </table>	
	</form>
	<!-- END форма  -->	   
	
</div>
