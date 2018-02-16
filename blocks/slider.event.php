<?
/*******************************/
/* карусель картинок к событию */
/*******************************/

if($alb > 0)
{
	$sql = "SELECT * FROM `".$_VARS['tbl_prefix']."_pic_".$alb."`
			WHERE 1
			ORDER BY order_by ASC";
	$res = mysql_query($sql);
	
	if($res && mysql_num_rows($res) > 0)
	{
		?>
		<script language="javascript">
		/************************/
		/* карусель contentflow */
		/************************/
		function init()
		{
			var cf = new ContentFlow('contentFlow', {     
				 visibleItems: 10, 
				 flowSpeedFactor: 0.4, 
				 relativeItemPosition : 'center',
				 loadingTimeout : 30000,
				 scaleFactorLandscape:  2,
				 maxItemHeight: 200,
				 fixItemSize: false,
				 reflectionHeight: 0.3 
			});
		}
		
		init();	 
		/*************************/
		/* /карусель contentflow */
		/*************************/
		
		</script>
		
		<div id="contentFlow" class="ContentFlow" style="height:200px;border:1px solid #dbdbdb; border-radius:4px 4px 4px 4px; -webkit-border-radius:7px 7px 7px 7px; -moz-border-radius:7px 7px 7px 7px; -khtml-border-radius:7px 7px 7px 7px;">
            <!-- should be place before flow so that contained images will be loaded first -->
			<h2>Фоторепортаж</h2>
            <div class="loadIndicator">
				<div class="indicator"></div>
			</div>
			<div class="flow" style="width:1100px; height:300px">
			<?
			while($row = mysql_fetch_array($res))
			{
				?>
				<!--<div class="item">-->
					<a href="/pic_catalogue/cbr_pic_<?=$alb?>/<?=$row['id']?>.jpg" rel="fancy" title="<?=$row['name']?>" class="item viewGallery"><?
					$img = new Image();
					$img -> imgCatalogId 	= $alb;
					$img -> imgId 			= $row['id'];
					$img -> imgAlt 			= $row['name'];
					$img -> imgClass 		= "content";
					$img -> imgWidthMax 	= 330;
					$img -> imgHeightMax 	= 200;	
					$img -> imgMakeGrayScale= false;
					$img -> imgGrayScale 	= false;
					$img -> imgTransform	= "crop";
					$html = $img -> showPic();
					echo $html;
					?></a>
					<!--<div class="caption"><a href="#"><?=$row['name']?></a></div>-->
				<!--</div>-->
				<?
			}
			?><div style="clear:both"></div>
			</div>
            <!--<div class="globalCaption"></div>
            <div class="scrollbar"><div class="slider">
                <div class="position"></div>
            </div></div>-->        
        </div>    
	<div style="clear:both"></div>
	<?
	}
}
?>	