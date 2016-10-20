<?php 
	// 品牌门店 门店列表
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('品牌门店');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/shop.css">

<body class="shop_list bg_lgrey">
	<!-- 
	<div id="topbar" class="bg_white pad_10">
		<span class="area"><a href="shop_area.php">区域</a></span>
		<span class="tabset">
			<span class="allshop current">全部门店</span>
			<span class="actshop">活动门店</span>
		</span>
		<span class="nearby">附近</span>

	</div>
	-->
	<div class="shops">
		<!--  
		<div class="search"><input type="text" value=""></div>
		-->
		<div class="shopcontainer">
			<!-- 全部门店 -->
			<ul id="allshop" class="shown">
			<?php foreach ($children as $child):?>
			
				<li>
					<a href="<?php echo $this->createUrl('/mall/index',array('companyId'=>$child['dpid']));?>">	
					<div class="left"><img src="<?php echo $child['logo'];?>"></div>
					<div class="right">
						<h1><?php echo $child['company_name'];?></h1>
						<div class="info small font_l">地址: <?php echo $child['address'];?></div>
						<div class="misinfo small font_l"><span class="left">电话: <?php echo $child['telephone'];?></span><span class="right">＜140m</span></div>
					</div>
					</a>
				</li>
				<?php endforeach;?>
			</ul>
			<!-- 全部门店 -->
	    </div>
	</div>
</body>
