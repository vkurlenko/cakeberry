<?
// создадим таблицу учета заказов
$sql = "CREATE TABLE `".$_VARS['tbl_prefix']."_order`
		(
			id 				INT(11) NOT NULL AUTO_INCREMENT,
			order_num		TEXT,
			client_id		INT,
			client_name 	TEXT,
			client_contact 	TEXT,
			order_list		TEXT,
			sum_full		TEXT,
			sum_payed		INT NOT NULL DEFAULT 0,
			order_status	ENUM('0', '1', '2', '3', '4', '5'),
			order_date		DATETIME,
			PRIMARY KEY (id)
		)";
		
$res = mysql_query($sql);

class ORDER
{
	public $tbl;	// имя таблицы учета заказов
	
	public $id; 					// id заказа
	public $order_num		= '';	// номер заказа
	public $client_id		= 0;	// id клиента 
	public $client_name		= '';	// имя клиента
	public $client_contact	= '';	// контактные данные клиента
	public $order_list		= '';	// текст заказа
	public $sum_full		= '0';	// сумма заказа полная
	public $sum_payed		= 0;	// внесенная предоплата
	public $order_status 	= '0';	// статус заказа (0 - новый, 1 - выполнен, 2 - не оплачен, 3 - внесена предоплата, 4 - оплачен полностью, 5 - отменен)
	public $order_date 		= '';	// дата заказа
	public $order_by		= 'order_date';	// сортировка списка заказов по дате
	public $order_dir		= 'DESC';	// сортировка списка заказов по дате
	
	
	// запись заказа в БД
	public function addOrder()
	{
	
		$sql = "INSERT INTO `".$this -> tbl."`
				(
					order_num,
					client_id,
					client_name,
					client_contact,
					order_list,
					sum_full,
					sum_payed,
					order_status,
					order_date
				)
				VALUES
				(
					'".$this -> order_num."',
					".$this -> client_id.",
					'".$this -> client_name."',
					'".$this -> client_contact."',
					'".$this -> order_list."',
					'".$this -> sum_full."',
					".$this -> sum_payed.",
					'".$this -> order_status."',
					NOW()				
				)";	
		$res = mysql_query($sql);
		
		if(!$res)
			echo $sql;
		else
		{
			$this -> id = mysql_insert_id();
		}
		
		return $res;
	}
	
	
	// удаление заказа из БД
	public function delOrder()
	{
		$sql = "DELETE FROM `".$this -> tbl."`
				WHERE id = ".$this -> id;
				
		$res = mysql_query($sql);
		
		if(!$res)
			echo $sql;
	}
	
	
	// изменение статуса заказа
	public function setOrderStatus()
	{
		$sql = "UPDATE `".$this -> tbl."`
				SET order_status = '".$this -> order_status."'
				WHERE id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		//if(!res)
			//echo $sql;
	}
	
	// изменение статуса заказа
	public function setSumPayed()
	{
		$sql = "UPDATE `".$this -> tbl."`
				SET sum_payed = ".$this -> sum_payed."
				WHERE id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		//if(!res)
			//echo $sql;
	}
	
	
	// прочитаем заказ по его id 
	public function getOrder()
	{
		$arr = array();
		
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE id = ".$this -> id;
		
		$res = mysql_query($sql);
		
		if($res)
		{
			$row = mysql_fetch_assoc($res);
			$arr = $row;
		}
		
		return $arr;
	}
	
	
	
	// считывание всех заказов из БД
	public function getAllOrders()
	{
		$arr = array();
		
		$where = 1;
		
		if(isset($this -> user_id))
			$where = "client_id = ".$this -> user_id;
		
		$sql = "SELECT * FROM `".$this -> tbl."`
				WHERE ".$where."
				ORDER BY ".$this -> order_by." ".$this -> order_dir;
		//echo $sql;
		$res = mysql_query($sql);
		
		if($res)
		{
			while($row = mysql_fetch_assoc($res))
			{
				$arr[] = $row;
			}
		}
		
		return $arr;		
	}
}

	

?>