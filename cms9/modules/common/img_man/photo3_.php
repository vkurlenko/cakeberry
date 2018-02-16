<?
include $_SERVER['DOCUMENT_ROOT']."/cms9/modules/framework/class.image.php";
include_once $_SERVER['DOCUMENT_ROOT'].'/cms9/modules/framework/class.html.php';

session_start();
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/
/*~~~ CMS МЕНЕДЖЕР КАРТИНОК ~~~*/
/*~~~~~~~~~~~~~~~~~~~~~~~~~~~~~*/

/*echo "<pre>";
print_r($_POST);
echo "</pre>";*/

check_access(array("admin", "editor"));

//error_reporting(E_ALL);

$arrExt = array("jpg", "png", "gif");

if(isset($_POST['pub'])) 
	$last_type = $_POST['pub'];
	
if(!isset($last_type)) 
	$last_type = 0;


if(isset($_GET['in_page']))
	$_SESSION['in_page'] = $_GET['in_page'];

if(!isset($_SESSION['in_page']))
	$in_page = 50; //$_VARS['cms_pic_in_page'];
else
	$in_page = $_SESSION['in_page'];
	
//$arr = $_VARS['cms_tumb_types'];

if(isset($name)) 
	$name = str_replace("'","#039;",$name);
	
if(!isset($zhanr)) 
	$zhanr = "";

$parent_folder	= "";


$parent_folder = $_VARS['photo_alb_dir']."/";

$tbl	= $_VARS['tbl_photo_name'].$zhanr;


/*	список всех альбомов	*/
$sql = "SELECT * FROM `".$_VARS['tbl_photo_alb_name']."`
		WHERE 1
		ORDER BY alb_order ASC";
		
$res = mysql_query($sql);

$arrAlb = array();

if($res && mysql_num_rows($res) > 0)
{
	while($row = mysql_fetch_array($res))
	{
		$arrAlb[$row['alb_name']] = $row['alb_title'];
	}
}
/*	/список всех альбомов	*/



/*~~~ определяем расширение файла ~~~*/
function ext($f)
{
	$file_info = getimagesize($f);
	switch ($file_info[2])
	{
		case 1 : $ext = "gif"; break;
		case 2 : $ext = "jpg"; break;
		case 3 : $ext = "png"; break;
		default : break;			
	}
	return $ext;
}



/*~~~ создаем папку для фотоальбома ~~~*/
function CreateFolder($zhanr)
{
	mkdir($_SERVER['DOCUMENT_ROOT']."/photo".$zhanr);
	chmod($_SERVER['DOCUMENT_ROOT']."/photo".$zhanr, 0777);
}



/*~~~~~~~~ сохраняем с расширением ~~~~~~~~~~~*/
function saveImage($f, $folder, $name)
{
	$file_info = getimagesize($f);
	
	switch ($file_info[2])
	{
		case 1 : $src = imagecreatefromgif($f); break;
		case 2 : $src = imagecreatefromjpeg($f); break;
		case 3 : $src = imagecreatefrompng($f); break;
		default : $src = imagecreatefromjpeg($f); break;
	}
	
	$im = imagecreatetruecolor($file_info[0], $file_info[1]);
	
	// сохраняем прозрачность для png-24
	imageAlphaBlending($im, false);
	imagesavealpha($im, true);
	
	imagecopyresampled($im, $src, 0, 0, 0, 0, $file_info[0], $file_info[1], $file_info[0], $file_info[1]);
	
	switch ($file_info[2])
	{
		case 1 : imagegif($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".gif"); break;
		//case 2 : imagejpeg($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".jpg", 100); break;
		case 3 : imagepng($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".png"); break;
		default : imagejpeg($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".jpg", 100); break;
	}
	
	/*imagejpeg($im, $_SERVER['DOCUMENT_ROOT']."/".$folder."/".$name.".jpg", 100);*/
}
/*~~~~~~~~ //конвертируем файл в jpeg и сохраняем с расширением ~~~~~~~~~~~*/

/*	изменение порядка следования записей	*/
function MoveItem($id, $direction)
{
	global $tbl;
	
	$table_name = $tbl;
	
	$order_field = "order_by";
	
	if($direction == "asc") $arrow = ">";
	elseif($direction == "desc") $arrow = "<";
	
	$sql = "select * from `".$table_name."` where id=".$id;
	//echo $sql."<br>";
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res);

	$sql = "select * from `".$table_name."` where (".$order_field." ".$arrow." ".$row[$order_field].") order by ".$order_field." ".$direction." limit 1 ";
	//echo $sql."<br>";
	$res = mysql_query($sql);
	$row_2 = mysql_fetch_array($res);
	
	$sql = "update `".$table_name."` set ".$order_field."=".$row_2[$order_field]." where id=".$id;
	//echo $sql."<br>";
	$res = mysql_query($sql);
	
	$sql = "update `".$table_name."` set ".$order_field."=".$row[$order_field]." where id=".$row_2['id'];
	//echo $sql."<br>";
	$res = mysql_query($sql);
}

/*~~~ удаление картинки ~~~*/
if(isset($del)) 
{
	mysql_query("delete from `$tbl` where `id` = '$id'");	
	
	$dir = opendir($_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$zhanr");
	chdir($_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$zhanr");
	while($file = readdir($dir))
	{
		//$template = "[^".$id."-?\w*(-mono)?.(jpg|gif|png)$]";
		$template = "[^".$id."(-\w*(-mono)?)?.(jpg|gif|png)$]";
		$result = preg_match($template, $file); 
		if($result)	
		{
			//echo $file."<br>";
			unlink($file);
		}		
	}
	
	if(mysql_num_rows(mysql_query("select `id` from `$tbl`"))>=$in_page*$p)
	{
		header("Location: ?page=$page&zhanr=$zhanr&p=$p&last_type=$last_type");
	} 
	else 
	{ 
		$p = $p - 1; 
		header("Location: ?page=$page&zhanr=$zhanr&p=$p&last_type=$last_type"); 
	}
	exit;
}
/*~~~ /удаление картинки ~~~*/

/*~~~ изменение записи ~~~*/
if(isset($upd)) 
{
	$sql = "SELECT * FROM `".$_VARS['tbl_photo_name'].$zhanr."`
			WHERE id = ".$id;
	$res = mysql_query($sql);
	$row_img = mysql_fetch_array($res);
	$ext = $row_img['file_ext'];
		
	if($_POST['removeTo'] != '' && $_POST['removeTo'] != 0)
	{
		// имя нового альбома
		$newDir = $_POST['removeTo'];
		
		//echo 'Новый альбом: '.$newDir.'<br>';
		
		// скопируем файл-оригинал в новый альбом		
		$copy = copy($_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$zhanr/$id.".$ext, $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/$id.".$ext);
		
		//echo 'копируем: '.$_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$zhanr/$id.".$ext.'<br>
				//в '.$_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/$id.".$ext.'<br>';
		
		// если копирование прошло удачно
		if($copy)
		{
			//echo 'копирование прошло удачно <br>';
			
			$tbl = $_VARS['tbl_photo_name'].$newDir; 
			//$ext = ext($_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/$id.jpg");
			
			$sql = "insert into `$tbl` set `name`='$name', `file_ext`='$ext', `tags`='$tags', url='$url'";
			mysql_query($sql);
			//echo $sql.'<br>';
			
			$newId = mysql_insert_id();
			$sql = "update `$tbl` set `order_by` = '".$newId."' where `id` = '".$newId."'";
			$res = mysql_query($sql);
			
			//echo $sql.'<br>';
			
			rename($_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/$id.".$ext, $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/".$newId.".".$ext);
			
			/*echo 'переименуем '.$_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/$id.".$ext.'
			<br> в '.$_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$newDir/".$newId.".".$ext.'<br>';*/
			
			$sql = "delete from `".$_VARS['tbl_photo_name'].$zhanr."` where `id` = '$id'";
			mysql_query($sql);
			
			//echo $sql.'<br>';
			
			$old_dir = $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$zhanr";
			
			$dir = opendir($old_dir);
			
			//echo 'откроем папку '.$old_dir.'<br>';
			
			chdir($old_dir);
						
			while($file = readdir($dir))
			{
				//echo 'id = '.$id."<br>";
				//$template = "[^".$id."-?\w*(-mono)?.(jpg|gif|png)$]";
				$template = "[^".$id."(-\w*(-mono)?)?.(jpg|gif|png)$]";
				//echo $template.'<br>';
				$result = preg_match($template, $file); 
				if($result)	
				{
					//echo 'удалим файл '.$file."<br>";
					unlink($file);
				}		
			}			
		}
	}
	else
	{
		$sql = "update `$tbl` set `name`='$name', `tags`='$tags', `url`='$url' where `id` = '$id'";
		mysql_query($sql);	
		
		/*echo 'копирование не прошло <br>';
		echo $sql.'<br>';*/
		
	}	
	
	
	header("Location: ?page=$page&zhanr=$zhanr&p=$p&last_type=$last_type");
	exit;
}
/*~~~ /изменение записи ~~~*/



// создадим массив загруженных файлов
function makeArrFiles()
{
	/*printArray($_FILES);
	echo count($_FILES['small']);*/
	
	$arr = array();
	
	if(is_array($_FILES['small']['tmp_name']))
	{
		foreach($_FILES['small'] as $param => $v)
		{
			foreach($_FILES['small'][$param] as $k => $v)
			{
				$arr[$k][$param] = $v;
			}
		}			
	}
	else
	{
		foreach($_FILES['small'] as $param => $v)
		{
			$arr[$param] = $v;
		}	
	}
	
	return $arr;
	
}

/*~~~ новая картинка ~~~*/
if(isset($_POST['loadNew'])) 
{
	$arrFiles = makeArrFiles();
	$arrError = array();
	$arrErrorCode = array(
		1 => 'Размер принятого файла превысил максимально допустимый размер',
		2 => 'Размер загружаемого файла превысил значение MAX_FILE_SIZE, указанное в HTML-форме',
		3 => 'Загружаемый файл был получен только частично',
		4 => 'Файл не был загружен',
		6 => 'Отсутствует временная папка',
		7 => 'Не удалось записать файл на диск',
		8 => 'PHP-расширение остановило загрузку файла'
	);
	
	//printArray($arrFiles);
	
	foreach($arrFiles as $k => $v)
	{
		if($v['error'] == 0)
		{
			$sql = "INSERT INTO `$tbl`
					SET `name`='".$v['name']."'";
					
			$res = mysql_query($sql);
			
			
			$id = mysql_insert_id();
			
			// определим тип закачанного файла
			$file_type 	= getimagesize($v["tmp_name"]);
			
			// если jpeg, то просто копируем его
			if($file_type[2] == 2)
			{
				copy($v["tmp_name"], $_SERVER['DOCUMENT_ROOT']."/".$parent_folder.$_VARS['photo_alb_sub_dir']."$zhanr/$id.jpg");
			}	
			// иначе конвертируем в jpeg
			else 
			{
				saveImage($v["tmp_name"], $parent_folder.$_VARS['photo_alb_sub_dir']."$zhanr", $id);
			}
			
			
			switch ($file_type[2])
			{
				case 1 : $ext = "gif"; break;
				//case 2 : $ext = "jpg"; break;
				case 3 : $ext = "png"; break;
				default : $ext = "jpg"; break;
			}
			
			$sql = "UPDATE `$tbl` 
					SET `file_ext`='$ext', `order_by` = '$id' 
					WHERE `id` = '$id'";
			$res = mysql_query($sql);	
		}
		else
		{
			$arrError[] = $k;
		}			
	}
	
	if(!empty($arrError))
	{
		foreach($arrError as $k)
		{
			echo 'Ошибка при загрузке файла '.$arrFiles[$k]['name'].' ('.$k.'):<br>';
			echo $arrErrorCode[$arrFiles[$k]['error']].'<br>';
			?><a href="?page=<?=$page?>&zhanr=<?=$zhanr?>&last_type=<?=$last_type?>">Перейти в альбом</a><?
			
		}
	}
	else
	{
		header("Location: ?page=$page&zhanr=$zhanr&last_type=$last_type");
		
	}
	exit;	
}
/*~~~ /новая картинка ~~~*/





if(isset($move) and isset($dir) and isset($id))
{
	MoveItem($id, $dir);
}


/*~~~ вывод всех картинок ~~~*/
if(!isset($p) || $p < 0) 
	$p = 0;
	
$start = $in_page * $p;

$sql = "SELECT * FROM `$tbl` 
		WHERE 1";
		
$res = mysql_query($sql);

$total = mysql_num_rows($res);

//echo $total;

$uurl = "?page=$page&zhanr=$zhanr&p=$p";

$sql = "SELECT * FROM `$tbl` 
		WHERE 1
		ORDER BY `order_by` ASC 
		LIMIT $start, $in_page";
$r = mysql_query($sql);
/*~~~ /вывод всех картинок ~~~*/

/* навигация */
function putNavBar()
{
	global $page, $zhanr, $total, $in_page;
	
	$arr = array();
	
	$n = ceil($total / $in_page);	
		
	for($i = 0; $i < $n; $i++)
	{
		if(isset($_GET['p']) && $i == $_GET['p'])
			$arr[] = '<a class="active" href="?page='.$page.'&zhanr='.$zhanr.'&p='.$i.'">'.($i+1).'</a>';
		else
			$arr[] = '<a href="?page='.$page.'&zhanr='.$zhanr.'&p='.$i.'">'.($i+1).'</a>';
	}	
	?>
	<fieldset class="navBar">
		<?
		foreach($arr as $k)
			echo '<span>'.$k.'</span>';		
		?>
		<div style="clear:both"></div>
		<div class="inPage">
			<?
			$arr = array(
				'все' 	=> 1000000,
				'10'	=> 10,
				'20'	=> 20,
				'30'	=> 30,
				'50'	=> 50,
				'100'	=> 100,
				'200' 	=> 200);
			?> 
			Показать:
			<?
			foreach($arr as $k => $v)
			{
				if($in_page == $v)
					echo '<a class="active" href="?page='.$page.'&zhanr='.$zhanr.'&p=0&in_page='.$v.'">'.$k.'</a>';	
				else
					echo '<a href="?page='.$page.'&zhanr='.$zhanr.'&p=0&in_page='.$v.'">'.$k.'</a>';
			}
			?>
			
		</div>
	</fieldset>
	<?
}
/* /навигация */

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
<link rel="stylesheet" href="admin.css" type="text/css">

<style>
.navBar{padding:10px}
.navBar span{display:block; float:left; margin:5px; }
.navBar span a{padding:5px; border:1px solid #666; background:#ddd; display:block; width:15px; text-align:center }
.navBar span a.active{padding:5px; /*border:1px solid #eee;*/ background:#fff; }

.inPage a{text-decoration:underline; margin-left:10px}
.inPage a.active{text-decoration:none; margin-left:10px}

</style>

<script language="javascript" type="text/javascript" src="js/jquery-1.5.min.js"></script>

<script language="javascript">

$(document).ready(function()
{
	$('a.add').click(function()
	{
		var obj = $(".formField").eq($(".formField").size() - 1);
		$(".formField").eq(0).clone().insertAfter(obj);
		return false;
	})
	
	$('.formField a.del').click(function()
	{
		if($('.formField').size() > 1)
		{
			var obj = $(this).parents('.formField');
			$(obj).remove();
		}
		return false;
	})
})

</script>

</head>


<body bgcolor="#FFFFFF">
<a href="?page=photo_alb">Все альбомы</a>
<?
$sql = "SELECT alb_title FROM `".$_VARS['tbl_photo_alb_name']."` 
		WHERE alb_name = '".$zhanr."'";
//echo $sql;
$res = mysql_query($sql);
$row = mysql_fetch_array($res);
?>
<h3>Фотоальбом "<?=$row['alb_title']?>"</h3>
<?

if(!isset($error))$error="";
if(!isset($name))$name=""; 

?>
<fieldset><legend><strong>Новая картинка</strong></legend>
<?=$error?>
<form action="#new" method="post" enctype="multipart/form-data" name="newform">
    <table border="0" cellspacing="2" cellpadding="2">
        <tr class="formField">
            <td>Выберите картинку:</td>
			<td>
				<input type="file" name="small[]">&nbsp;<a class='del' href="">Удалить</a>
			</td>
        </tr>
		       
		<tr>
            <td colspan=2>				
                <input type="hidden" name="zhanr" id="zhanr" value="<?=$zhanr?>">
				<a class='add' href="">Добавить поле</a>&nbsp
                <input type="submit" name="loadNew" value="Загрузить">
            </td>
        </tr>
    </table>	 
</form>
</fieldset>


<?=putNavBar()?>


<table border="0" cellspacing="0" cellpadding="4">
 <?
while($e = mysql_fetch_array($r)) 
{
	if(!isset($fon) OR $fon =="#ffffff") 
		$fon="#eee"; 
	else 
		$fon="#ffffff";
?>
    <form action="?page=<?=$page?>&zhanr=<?=$zhanr?>" method="post" enctype="multipart/form-data" name="form<?=$e['id']?>">
        <tr bgcolor="<?=$fon?>" valign="top">			
            <td rowspan=4 width="10"><?=$e['id']?></td>
			<td rowspan=4 align="center" width=45>
				<a href="?page=<?=$page?>&zhanr=<?=$zhanr?>&p=<?=$p?>&id=<?=$e["id"]?>&last_type=<?=$last_type?>&move&dir=asc"><img src='<?=$_ICON["down"]?>' alt="down"></a>
				<a href="?page=<?=$page?>&zhanr=<?=$zhanr?>&p=<?=$p?>&id=<?=$e["id"]?>&last_type=<?=$last_type?>&move&dir=desc"><img src='<?=$_ICON["up"]?>' alt="up"></a>
			</td>
            <td rowspan=4 width=100>
			<?
						
			$img = new Image();
			$img -> imgCatalogId 	= $zhanr;
			$img -> imgId 			= $e["id"];
			$img -> imgAlt 			= "";
			$img -> imgWidthMax 	= 200;
			$img -> imgHeightMax 	= 100;	
			$img -> imgMakeGrayScale= false;
			$img -> imgGrayScale 	= false;
			$img -> imgTransform	= "resize";
			
			$html = $img -> showPic();
			echo $html;
			
			?>
            </td>
            <td>Название картинки :</td>
            <td><input type="text" name="name" value="<?=htmlspecialchars($e["name"])?>" style="width:300px;">
                &nbsp;</td>
            <td align="right" valign="top" rowspan=2><a href="javascript:if(confirm('Удалить эту картинку?'))location.replace('?page=<?=$page?>&zhanr=<?=$zhanr?>&p=<?=$p?>&id=<?=$e["id"]?>&last_type=<?=$last_type?>&del=del');" style="color:red;font-size:10px;" title="Удалить картинку" ><img src='<?=$_ICON["del"]?>' alt="del"></a> </td>
        </tr>
        <tr bgcolor="<?=$fon?>">
            <td>Теги</td>
            <td><input type="text" name="tags" value="<?=htmlspecialchars($e["tags"])?>" style="width:300px;"></td>
        </tr>
		<tr bgcolor="<?=$fon?>">
            <td>Ссылка:</td>
			<td colspan="2"><input type="text" name="url" value="<?=htmlspecialchars(@$e["url"])?>" style="width:300px;"></td>
        </tr>
        <tr bgcolor="<?=$fon?>">
            <td>Перенести в альбом</td>
            <td>
				
				<?
				$select = new FormElement();
				$select -> fieldProperty["name"] = 'removeTo'; // атрибут NAME поля SELECT
				$select -> fieldProperty["value"] = $zhanr;	// id родительского альбома
				$select -> thisId 	= $zhanr;	// id данного альбома
					
				$html = $select -> createSelectAlb();
				
				echo $html;
				?>
            </td>
            <td align=right>
            	<input name="id" 	type="hidden" value="<?=$e["id"]?>">
                <input name="zhanr" type="hidden" id="zhanr" value="<?=$zhanr?>">
                <input name="upd" 	type="hidden" id="upd" value="upd">
				<input name="last_type" type="hidden" value="<?=$last_type;?>">
                <input name="p" 	type="hidden" id="p" value="<?=$p?>">
            	<input type="submit" name="Submit" value="Сохранить">
             </td>
        </tr>
    </form>
    <?
}

?>
</table>

<?=putNavBar()?>

<br>
</body>
</html>
