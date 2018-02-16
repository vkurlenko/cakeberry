<?
class MENU
{
	public $p_parent_id = 0;
	
	/*
	**********************************
	Simple one-level page menu,
	
	RETURNS: 
	
	Array([page_id], [page_url], [page_title]);
	
	EXAMPLE:
	
	$arrMenu = new MENU;
	$arrMenu -> p_parent_id = 5;
	$arrMenu = $arrMenu -> menuSimple();
	
	**********************************
	*/
	
	public function menuSimple()
	{
		$a = array();

		$sql = "SELECT * FROM `".SITE_PREFIX."_pages`
				WHERE p_show = '1'
				AND p_main_menu = '1'
				AND p_parent_id = ".$this -> p_parent_id."
				ORDER BY p_order ASC";
		$res = mysql_query($sql);
		
	
		if($res && mysql_num_rows($res) > 0)
		{
			while($row = mysql_fetch_array($res))
			{
				$url = '';

				if(trim($row['p_redirect']) != '')
					$url = trim($row['p_redirect']);
				else 
					$url = trim($row['p_url']);
					
				$a[] = array(
					'id' 		=> $row['id'], 
					'p_url' 	=> $url, 
					'p_title' 	=> $row['p_title']);
			}
		}
		
		return $a;
	}
	
	public function menuIsChild()
	{
		
	}
	
	
	
	
}
?>