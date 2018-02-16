<?
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once "vars.php";
include_once "mail.inc.php";

/*echo '<pre>';
print_r($_POST);
echo '</pre>';*/
// если пришла форма авторизации
if(!empty($_POST) && (isset($_POST['form_name']) && $_POST['form_name'] == 'call_form'))
{
	$errorMsg = array();	
	
	?>
	<script language="javascript">
	function dialogHide()
	{
		$('#formCall').hide();
	}
	
	function dialogShow()
	{
		$('#formCall').show();
	}
	
	$(document).ready(function()
	{
		$('.dialogCallRetry').click(function()
		{
			$( "#dialog-form-call").dialog('close').dialog('open');
			dialogShow()
		})
		
		$('.dialogCallClose').click(function()
		{
			$( "#dialog-form-call").dialog('destroy');
		})
	})
	</script>
	
	<?
	
	if(	isset($_POST['call_code']) 	&& trim($_POST['call_code']) 	!= '' &&
		isset($_POST['call_phone']) && trim($_POST['call_phone']) 	!= '' &&
		isset($_POST['call_name']) 	&& trim($_POST['call_name']) 	!= ''
		)
	{
		if(!preg_match('([0-9])', $_POST['call_code'])) 
			$errorMsg[] = "Код телефона может содержать только цифры.";
			
		if(!preg_match('([0-9])', $_POST['call_phone'])) 
			$errorMsg[] = "Номер телефона может содержать только цифры.";
	
		// печать ошибок обработки формы
		if(!empty($errorMsg))
		{
			?>			
			<p class="ui-state-error ui-state-error-call">
			<?
			foreach($errorMsg as $k)
			{
				echo $k.'<br>';
			}
			?>
			<script language="javascript">
			$(document).ready(function()
			{
				dialogHide()
			})
			</script>
			<input type="button" class="dialogCallRetry" value="Хорошо" />
			</p>
			<?
		}
		else
		{
			$call_code = trim($_POST['call_code']);
			$call_phone = trim($_POST['call_phone']);
			$call_name = trim($_POST['call_name']);
			
			$to 	= array(
					'0'=> array('name' => 'Администратору сайта','email' => $_VARS['env']['mail_admin'])
			);
			$cc 	= array();
			$bcc 	= array();
			$read 	= array();
			$reply 	= array();
	
			$sender = 'cakeberry@cakeberry.ru';
			$senderName = $call_name;
			$subject = 'Поступил запрос на обратный звонок от  посетителя сайта';
			$message = '<html>
					<head>
					 </head>
					<body>
						От посетителя сайта '.$call_name.' поступил запрос на обратный звонок.<br />
						Номер телефона : +7 '.$call_code.' '.$call_phone.'
					</body>
					</html>';
			$obj2 = new sendMail($to, $sender, $subject, $message, $cc, $bcc,$senderName, $read, true, $reply, true);
			//echo "$to, $sender, $subject, $message, $cc, $bcc, $senderName, $read, true, $reply, true";
			$call_send = $obj2->sendEmail(); // результат отправки письма	
			
			if($call_send)
			{				
				?>
				<script language="javascript">
				$(document).ready(function()
				{
					dialogHide()
				})
				</script>
				<p class="ui-state-error ui-state-error-call">Ваш запрос успешно отправлен.<br />
				<input type="button" class="dialogCallClose" value="Закрыть" /></p><?
			}
			else
			{
				?>
				<script language="javascript">
				$(document).ready(function()
				{
					dialogHide()
				})
				</script>
				<p class="ui-state-error ui-state-error-call">Ошибка отправки запроса.<br />
				<input type="button" class="dialogCallRetry" value="Хорошо" /></p><?
			}
			//echo $call_send;
		}
	}
	else
	{
		?>
		<script language="javascript">
		$(document).ready(function()
		{
			dialogHide()
		})
		</script>
		<p class="ui-state-error ui-state-error-call">
			Все поля формы обязательны для заполнения.
			<br />
			<input type="button" class="dialogCallRetry" value="Хорошо" />
		</p><?
	}
}

?>