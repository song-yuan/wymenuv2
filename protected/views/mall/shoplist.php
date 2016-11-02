<?php 
	// 品牌门店 门店列表
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('品牌门店');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/shop.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>

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
				<a href="<?php echo $this->createUrl('/mall/index',array('companyId'=>$child['dpid']));?>">	
				<li lat="<?php echo $child['lat'];?>" lng="<?php echo $child['lng'];?>">
					<div class="left"><img src="<?php echo $child['logo'];?>"></div>
					<div class="right">
						<h1><?php echo $child['company_name'];?></h1>
						<div class="info small font_l">地址: <?php echo $child['address'];?></div>
						<div class="misinfo small font_l"><span class="left">电话: <?php echo $child['telephone'];?></span><span class="right"></span></div>
					</div>
				</li>
				</a>
				<?php endforeach;?>
			</ul>
			<!-- 全部门店 -->
	    </div>
	</div>
	<script type="text/javascript">
		/**
	     * approx distance between two points on earth ellipsoid
	     * @param {Object} lat1
	     * @param {Object} lng1
	     * @param {Object} lat2
	     * @param {Object} lng2
	     */
	    var EARTH_RADIUS = 6378137.0;    //单位M
	    function getFlatternDistance(lat1,lng1,lat2,lng2){
	        var f = getRad((lat1 + lat2)/2);
	        var g = getRad((lat1 - lat2)/2);
	        var l = getRad((lng1 - lng2)/2);
	        
	        var sg = Math.sin(g);
	        var sl = Math.sin(l);
	        var sf = Math.sin(f);
	        
	        var s,c,w,r,d,h1,h2;
	        var a = EARTH_RADIUS;
	        var fl = 1/298.257;
	        
	        sg = sg*sg;
	        sl = sl*sl;
	        sf = sf*sf;
	        
	        s = sg*(1-sl) + (1-sf)*sl;
	        c = (1-sg)*(1-sl) + sf*sl;
	        
	        w = Math.atan(Math.sqrt(s/c));
	        r = Math.sqrt(s*c)/w;
	        d = 2*w*a;
	        h1 = (3*r -1)/2/c;
	        h2 = (3*r +1)/2/s;
	        
	        return d*(1 + fl*(h1*sf*(1-sg) - h2*(1-sf)*sg));
	    }
	    wx.ready(function () {
	    	wx.getLocation({
			    type: 'wgs84', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
			    success: function (res) {
			        var latitude = res.latitude; // 纬度，浮点数，范围为90 ~ -90
			        var longitude = res.longitude; // 经度，浮点数，范围为180 ~ -180。
			        var speed = res.speed; // 速度，以米/每秒计
			        var accuracy = res.accuracy; // 位置精度
			        $('#allshop').find('li').each(function(){
						var lat = $(this).attr('lat');
						var lng = $(this).attr('lng');
						alert(lat);alert(lng);
						var distance = getFlatternDistance(latitude,longitude,lat,lng);
						alert(distance);
						$(this).find('span.right').html(parseInt(distance)+'米');
				    });
			    }
			});
	    });
	</script>
</body>
