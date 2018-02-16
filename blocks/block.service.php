<style>
.service{
display:block;
font-size:12px; 
padding:0px; 
background:#ccc;
/*position:absolute; 
top:100px; 

z-index:500; */
border:1px solid #000}
.service pre{padding:5px; margin:0; font-size:9px}
</style>

<div style="clear:both"></div>

<div class="service">
	
	<?
	echo "SESSION";
	printArray($_SESSION);
	echo "POST";
	printArray($_POST);
	echo "GET";
	printArray($_GET);
	?>
</div>