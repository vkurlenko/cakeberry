<form id="formUserMsg" action="" method="post" enctype="multipart/form-data">
	<input type="hidden" name="formName" value="userMsg" />
<div class="feedbackForm">
	<span class="title">Пишите:</span>
	
	<table cellspacing="10">
		<tr>
			<td>Тема сообщения:</td>
			<td>
				<select name="userTheme">
					<?
					$i = 0;
					foreach($arrThemeFB as $k)
					{
						?><option value="<?=$i++?>"><?=$k?></option><?
					
					}
					?>
					
				</select>
			</td>
		</tr>
		<tr>
			<td>ФИО:</td><td><input name="userName" type="text" value="<?=$arrForm['userName']?>" /></td>
		</tr>
		<tr>
			<td>Адрес:</td><td><input name="userAddr" type="text" value="<?=$arrForm['userAddr']?>" /></td>
		</tr>
		<tr>
			<td>E-mail:</td><td><input name="userMail" type="text" value="<?=$arrForm['userMail']?>" /></td>
		</tr>
		<tr>
			<td>Телефон: <span>+7</span></td>
			<td><input type="text" name="userCode" class="phoneCode" maxlength=5 value="<?=$arrForm['userCode']?>" /><input type="text" name="userNum" class="phoneNum" maxlength=7 value="<?=$arrForm['userNum']?>"/></td>
		</tr>
		<tr>
			<td valign="top">Сообщение:</td>
			<td><textarea name="userMsg"><?=$arrForm['userMsg']?></textarea></td>
		</tr>
		
	</table>
	
	<div class="buttonConfirm feedbackConfirm">
		<a href="#">Отправить</a>
	</div>
	
	<div style="clear:both"></div>
	
</div>

</form>