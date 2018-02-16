<?
include 'blocks/functions.php';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<?
HTML::insertMeta();
?>

<link href="/css/style.css" rel="stylesheet" />

<script language="javascript" type="text/javascript" src="/js/jquery.1.7.1.min.js"></script>
<script language="javascript" type="text/javascript" src="/js/jquery.form.js"></script>

<?
// jquery UI library
include "blocks/inc.js.ui.php";
?>
<script language="javascript" type="text/javascript" src="/js/script.js"></script>
<script language="javascript">
$(document).ready(function(){
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* диалоговое окно заказать звонок */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	$('.letCall').click(function()
	{
		$( "#dialog-form-call").dialog( "open" );
		return false;
	})

	$( "#dialog-form-call").dialog({			
		<?
		if(!isset($autoOpen)) $autoOpen = "false";	
		//$autoOpen = "true";			
		?>
		autoOpen: <?=$autoOpen?>,
		height: 365,
		width: 650,
		modal: true,			
		close: function()
		{
			$('.result').html('');
		}
	});	
	  
	var options = {
	  target: ".result",
	  resetForm : true
	};

	$(".formSubmit").click(function()
	{
		$('.result').html('');
		$("#formCall").ajaxSubmit(options);
		
		return false;
	})	  
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	/* /диалоговое окно заказать звонок */
	/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
	
	$('.feedbackConfirm').click(function()
	{
		$('#formUserMsg').submit();
		return false;
	})
	
})
</script>

</head>

<body>

	<div id="mainDiv">
	
		<!-- BEGIN верхний блок -->		
		<div class="topBlock">
			<? 
			include "blocks/block.top.php";			
			?>
		</div>		
		<!-- END верхний блок -->
		
		
		<!-- BEGIN главное меню -->				
		<?
		include "blocks/menu.main.php";
		?>			
		<!-- END  главное меню -->
		
		
		
		
		<div class="innerDiv">
			
			<?
			include "blocks/block.nav.php";
			?>
					
				
			<!-- BEGIN контент -->	
			
			<div class="content">
			
				<h1>:::pageTitle:::</h1>	
				
				<?
				/* автоподстановка значений полей форм */
				
				if(userAuth())
				{
					// если пользователь авторизован
					$arrUserData = userData($_SESSION['user_id']);
					//printArray($arrUserData);
					
					$userPhone = explode('|', $arrUserData['user_phone'], 2);
					
					$arrForm = array(
						'userName' 	=> $arrUserData['user_patr'].' '.$arrUserData['user_name'].' '.$arrUserData['user_surn'],
						'userCode' 	=> $userPhone[0],
						'userNum' 	=> $userPhone[1],
						'userAddr' 	=> $arrUserData['user_addr_1'],
						'userMail' 	=> $arrUserData['user_mail'],
						'userTheme'	=> '',
						'userMsg' 	=> ''
					);						
				}	
				else
				{
					$arrForm = array(
						'userName' 	=> '',
						'userCode' 	=> '',
						'userNum' 	=> '',
						'userAddr' 	=> '',
						'userMail' 	=> '',
						'userTheme'	=> '',
						'userMsg' 	=> ''
					);
				}
				/* /автоподстановка значений полей форм */	
				
				
				/* проверка и отправка формы обратной связи*/
				if(!empty($_POST) && isset($_POST['formName']) && $_POST['formName'] == 'userMsg')
				{
					$text = '';	// текст письма
					
					// массив сообщений об ошибках
					$error = array(
						0 => array('Все поля обязательны для заполнения.', false),
						1 => array('Неверный формат email.', false),
						2 => array('Номер телефона может содержать только цифры.', false),
						3 => array('Ошибка отправки сообщения.', false),
					); 
					
					foreach($arrForm as $k => $v)
					{
						if(trim($_POST[$k]) == '') 
						{
							$error[0][1] = true;							
						}
						elseif($k == 'userMail')
						{
							if(!validData('EMAIL', $_POST[$k])) $error[1][1] = true;
						}
						elseif($k == 'userCode' || $k == 'userNum')
						{
							if(!validData('INT', $_POST[$k])) $error[2][1] = true;
						}
						
						$arrForm[$k] = $_POST[$k];						
					}				
				}
				/* /проверка и отправка формы обратной связи*/
					
				?>
							
				
				<?
				include "blocks/mail.call.php";	
				include "blocks/form.dialog.call.php";	
				?>
				<div class="feedbackCall">					
					<?
					$arr = formatPhone();
					$p = substr($arr['numPhone'], 0, 3).' '.substr($arr['numPhone'], 3, 2).' '.substr($arr['numPhone'], 5, 2);
					?>
					<span class="title">Звоните:</span><span class="phoneNumber"><?=$arr['codeCountry']?>&nbsp;<?=$arr['codeRegion']?>&nbsp;<?=$p?></span> <a class="letCall" href="#">Заказать звонок</a>				
				</div>
				
				<?
				$valid = false;
				
				if(!empty($error))
				{
					$valid = true;
					foreach($error as $k => $v)
					{
						if($v[1] == true)
						{
							?><p><?=$v[0]?></p><?
							$valid = false;
						}
					}
					
					
					if($valid == true)
					{
						$to 	= array(
								'0'=> array('name' => 'Администратору сайта','email' => $_VARS['env']['mail_admin'])
						);
						$cc 	= array();
						$bcc 	= array();
						$read 	= array();
						$reply 	= array();
				
						$sender = 'cakeberry@cakeberry.ru';
						$senderName = $arrForm['userName'];
						$subject = 'Поступило сообщение от  посетителя сайта';
						$message = '<html>
								<head>
								 </head>
								<body>
									Имя: '.$arrForm['userName'].'.<br />
									Номер телефона: +7 '.$arrForm['userCode'].' '.$arrForm['userNum'].'<br />
									Email: '.$arrForm['userMail'].'<br />
									Адрес: '.$arrForm['userAddr'].'<br />
									Тема: '.$arrThemeFB[$arrForm['userTheme']].'<br>
									Сообщение: '.$arrForm['userMsg'].'
								</body>
								</html>';
						$obj = new sendMail($to, $sender, $subject, $message, $cc, $bcc,$senderName, $read, true, $reply, true);
						echo "$to, $sender, $subject, $message, $cc, $bcc,$senderName, $read, true, $reply, true";
						$send = $obj->sendEmail(); // результат отправки письма	
						
						if(!$send) 
						{
							?><p>Ошибка отправки сообщения.</p><?
							$valid = false;
						}
						else
						{
							?><p>Ваше сообщение отправлено, спасибо!</p><?
						}
						
					}
					
					
				}
				
				if(!$valid)
				{
					// форма обратной связи
					include "blocks/form.feedback.msg.php";
				}					
				
				?>
				
				
			
			</div>					
			<!-- END контент -->			
			
			
			<!-- BEGIN нижнее меню -->
			
			<div class="menuFoot menuFootS">
			
				<?
				include "blocks/menu.foot.php";
				?>
				
				<div style="clear:both"></div>		
			
			</div>
			
			<!-- END  нижнее меню -->
			
		</div>
		
		<!-- BEGIN подвал -->
		
		<div class="footer">
			<?
			include "blocks/block.foot.php";
			?>
			
			<div style="clear:both"></div>
			
		</div>
		
		<!-- END подвал -->
		
			
	
	</div>
 
</body>
</html>
