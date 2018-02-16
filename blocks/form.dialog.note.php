<?
/***********************************/
/* диалоговое окно напоминание     */
/***********************************/

if(userAuth())
{
	// если пользователь авторизован
	$arrUserData = userData($_SESSION['user_id']);
	?>
	<div id="dialog-form-note" class="dialogNote" title="Напоминание">			
	
		<!-- BEGIN форма  -->
		<P class='resultNote ui-state-error'></P>
		
		<div class="notesList"></div>
		
		<form id="formNote" action="/blocks/ajax.note.php" method="post" style="display:none">	
		
		  <table align="center" cellpadding=7>
			  <tr>
              <td align="right"><label for="note_date_d">Дата события:</label></td>
              <td>
			  	<?
				$def_d = $def_m = $def_y = 0;	
				if($_PAGE['p_url'] == 'mypage')			
				{
					$def_d = date('d');
					$def_m = date('m');
					$def_y = date('Y');
					//$def_m = $def_y = 0;
				}
				
				?>
			  	<select name="note_date_d" class="note_date_d">
					<option value="0">день</option>
					<?
					echo printOption('D', $def_d);
					?>
				</select>
				<select name="note_date_m" class="note_date_m">
					<option value="0">месяц</option>
					<?
					echo printOption('M', $def_m);
					?>
				</select>
				<?
				$thisY = date('Y');
				?>
				<select name="note_date_y" class="note_date_y">
					<option value="0">год</option>
					<option value="<?=(intval($thisY)+1)?>"><?=(intval($thisY)+1)?></option>
					<?
					echo printOption('Y', $def_y, $thisY);
					?>
				</select>
				
			  </td>
          </tr>
			  <tr>
				  <td align="right"><label for="note_title">Заголовок напоминания:</label></td>
				  <td>
					<input type="text" name="note_title" id="note_title" value="" class="text ui-widget-content ui-corner-all" />
				  </td>
			  </tr>
			  <tr valign="top">
				  <td align="right"><label for="note_text">Текст:</label></td>
				  <td><textarea name="note_text"></textarea></td>
			  </tr>	
			  <tr valign="top">
				  <td align="right"><label for="note_text">Торт:</label></td>
				  <td class="prod">				  	
				  <?
				  if($_PAGE['p_url'] == 'mypage')
				  {
				  	$elem = new FormElement();					
					$elem -> fieldProperty = array(
						"name"	=> "itemId", 	
						"title" => "Привязка к объекту", 	
						"type"	=> "selectParentIdCatalog", 		
						"value" => 0,
						"thisId"=> 9999999,
						"table" => array("table_name" => $_VARS['tbl_prefix']."_catalog", "parent_field" => "item_parent", "order_by" => "item_order", "order_dir" => "ASC", "item_title" => "item_name_2"));
												
					$elem -> createFormElem();	
				  }
				  else	
				  {
				  	?><em id="itemName"><label>&laquo;&raquo;</label></em><input type="hidden" name="itemId" id="itemId" value="" /><?
				  }	  
				  ?>
				  </td>
			  </tr>		  
			  <tr>
				  <td></td>
				  <td>
					  <input class='formSubmitNote' type="submit" value="Создать">                  
				  </td>
			  </tr>				
		  </table>	
		</form>
		<!-- END форма  -->	
	</div>
<?			
}
?>
