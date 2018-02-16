<?
/*****************************************************/
/* удаление неподтвердивших регистрация через 3 суток*/
/*****************************************************/

$d = 3; 		// кол-во суток	
$arr = array(); // массив id просроченных записей

$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_users`
		WHERE user_register = '0'";	
$res = mysql_query($sql);

if($res && mysql_num_rows($res) > 0)
{
	while($row = mysql_fetch_assoc($res))
	{
		$t1 = strtotime($row['user_reg_date']);
		$t2 = strtotime(date('Y-m-d H:i:s'));
		$v = $t2 - $t1;
		
		if($v > 86400 * $d)
		{
			$arr[] = $row['id'];
		}
	}
}

if(!empty($arr))
{
	$i = 0;
	$str = '';
	foreach($arr as $k)
	{
		$str .= 'id = '.$k;
		$i++;
		if($i < count($arr))
			$str .= ' OR ';
	}
	
	$sql = "DELETE FROM `".$_VARS['tbl_prefix']."_users`
			WHERE ".$str;
	$res = mysql_query($sql);
}
/******************************************************/
/* /удаление неподтвердивших регистрация через 3 суток*/
/******************************************************/
?>