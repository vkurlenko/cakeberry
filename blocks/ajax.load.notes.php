<?
session_start();
include_once $_SERVER['DOCUMENT_ROOT']."/config.php";
include_once $_SERVER['DOCUMENT_ROOT']."/functions.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/functions_sql.php" ;
include_once $_SERVER['DOCUMENT_ROOT']."/cms9/modules/framework/class.image.php";

include_once "vars.php";
include_once "functions.php";

include_once $_SERVER['DOCUMENT_ROOT'].'/modules/note/note.class.php';



//echo $_POST['date'];

if(userAuth())
{

	$note = new NOTE();

	$note -> tbl  = $_VARS['tbl_prefix']."_notes";
	$note -> user = $_SESSION['user_id'];
	
	
	if(isset($_POST['date']) && is_array(explode('.', $_POST['date'], 3)))
	{
		$date = explode('.', $_POST['date'], 3);
		//$note -> day = $date[2].'-'.$date[1].'-'.$date[0];
		$note -> day = $date[1].'-'.$date[0];
		
		$note -> data = $note -> getUserNotes();		
		?>
		
		<?
		foreach($note -> data as $k)
		{
			?>
			<div class="noteHead"><?=$_POST['date']?><a href="/blocks/ajax.delete.note.php?delete_note=<?=$k['id']?>">удалить событие</a></div>
			<div class="noteItem">					
				<?
				if($k['note_title'] != '') 
				{
					?><div class="noteTitle"><?=$k['note_title']?></div><?
				}
				
				if($k['note_text'] != '')
				{
					?><div class="noteText"><?=$k['note_text']?></div><?
				}			
				
				
				if($k['note_item'] > 0)
				{
					$note -> product_tbl = $_VARS['tbl_prefix']."_catalog";
					$note -> product 	 = $k['note_item'];
					
					$product_data = $note -> getProductInfo();						

					?>
					
					<div class="noteProd">Торт: <a href="/item_card/<?=$k['note_item']?>/"><?=$product_data['item_name']?></a></div>
					
					<div class="prodImg">
						<?						
						$img = $note -> getProductImg();
						?>
						<a href="/item_card/<?=$k['note_item']?>/"><?=$img?></a>
					</div>
					<?
				}
				?>
			</div>
			<div style="clear:both"></div>
			<?
		}
	}	
}

?>