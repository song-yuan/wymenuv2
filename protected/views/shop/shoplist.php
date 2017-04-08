<?php 
	// 品牌门店 门店列表
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('品牌门店');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/shop.css">

<body class="shop_list bg_lgrey">
	<div id="topbar" class="bg_white pad_10" style="text-align:left;">当前位置:<span id="current_position" class="font_org"></span> </div>
	<div class="shops">
		<div class="search"><input id="name-search" type="text" value="" placeholder="请输入搜索关键字"></div>
		<div class="shopcontainer">
			<!-- 全部门店 -->
			<ul id="allshop">
				<?php foreach ($children as $k=>$child): if($child['is_rest']==0){continue;}?>
				<li href="<?php echo $this->createUrl('/mall/index',array('companyId'=>$child['dpid'],'type'=>$type));?>" distance="" searil="<?php echo $k;?>" lat="<?php echo $child['lat'];?>" lng="<?php echo $child['lng'];?>">
					<div class="right">
						<h1><span class="com-name"><?php echo $child['company_name'];?></span><span class="rest_message small font_l"><?php if($child['is_rest']==1||$child['is_rest']==2){ echo '(休息中...)';}?></span></h1>
						<div class="info small font_l" style="margin-top:5px;">地址: <span class="address_info"><?php echo $child['province'].($child['city']!='市辖区'?$child['city']:'').$child['county_area'].$child['address'];?></span><span class="open-location"><img alt="" src="<?php echo $baseUrl;?>/img/wechat_img/icon_location.png" style="width:20px;height:20px;vertical-align:middle;"></span></div>
						<div class="misinfo small" style="margin-top:5px;">
							<span class="left">
							<span class=" font_l">营业时间: <?php echo date('H:i',strtotime($child['shop_time'])).'-'.date('H:i',strtotime($child['closing_time']));?></span><br>
								<span style="font-weight:800;">电话: <?php echo trim('<a class="" href="tel:'.$child['telephone'].'">'.$child['telephone'].'</a>'.' '.'<a class="" href="tel:'.$child['mobile'].'">'.$child['mobile'].'</a>');?></span>
							</span>
							<span class="right font_org"></span>
						</div>
					</div>
				</li>
				<?php endforeach;?>
			</ul>
			<!-- 全部门店 -->
			<ul id="activeshop" class="shown">
			
			</ul>
			<div id="tips" class="info" style="text-align:center;">附近暂无餐厅可提供该服务,试试搜索吧!</div>
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
	    var PI = Math.PI;
	    
	    function getRad(d){
	        return d*PI/180.0;
	    }
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
	    function sortNumber(a,b){ 
			return a - b 
		} 
	    function searchShop(){
		    var shopStr = '';
	    	var search = $('#name-search').val();
			if(search==''){
				return;
			}
			var originDistanceArr = new Array();
			var shopDistanceArr = new Array();
		 	$('#allshop').find('li').each(function(){
			 	var searil = $(this).attr('searil');
			 	var shopDistance =  $(this).attr('distance');
			 	var name = $(this).find('.com-name').html();
	 	 	 	var patt = new RegExp(search);
		 	  	if(patt.test(name)){
		 	  		originDistanceArr[searil] = shopDistance;
		 	  		shopDistanceArr[searil] = shopDistance;
			 	}
		 	});	 
		 	if(shopDistanceArr.length==0){
				$("#tips").show();
			}else{
				$("#tips").hide();
				sortShop(originDistanceArr,shopDistanceArr);
			}
	    }
	    function sortShop(oriarr,arr){
		    var str = '';
	    	var originArr = oriarr;
	    	arr.sort(sortNumber);
		    for(var k in arr){
		    	var index = originArr.indexOf(arr[k]);
		    	originArr[index] = -1;
		    	str +=$('li[searil="'+index+'"][distance="'+arr[k]+'"]').prop("outerHTML");
		    }
		    $("#activeshop").html(str);
	    }
    	$('#activeshop').on('click','a',function(event){
 	    	event.stopPropagation();
 		});
    	$('#activeshop').on('click','.open-location',function(event){
 	    	var liObj = $(this).parents('li');
 	    	var latitude = parseFloat(liObj.attr('lat'));
 	    	var longitude = parseFloat(liObj.attr('lng'));
 	    	var name = liObj.find('.com-name').html();
 	    	var address = liObj.find('.address_info').html();
 	    	var infoUrl = '<?php echo Yii::app()->request->getHostInfo();?>'+liObj.attr('href');
 	    	wx.openLocation({
 	    	    latitude: latitude, // 纬度，浮点数，范围为90 ~ -90
 	    	    longitude: longitude, // 经度，浮点数，范围为180 ~ -180。
 	    	    name: name, // 位置名
 	    	    address: address, // 地址详情说明
 	    	    scale: 14, // 地图缩放级别,整形值,范围从1~28。默认为最大
 	    	    infoUrl: infoUrl // 在查看位置界面底部显示的超链接,可点击跳转
 	    	});
 	    	event.stopPropagation();
 		});
 	    $('#activeshop').on('click','li',function(){
 		    var href = $(this).attr('href');
 		    location.href = href;
 		});
 		$("#name-search").change(function(){
 			searchShop();
 		});
	    wx.ready(function () {
		    layer.load(2);
	    	wx.getLocation({
			    type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
			    success: function (res) {
			    	layer.closeAll('loading');
			        var latitude = parseFloat(res.latitude); // 纬度，浮点数，范围为90 ~ -90
			        var longitude = parseFloat(res.longitude); // 经度，浮点数，范围为180 ~ -180。
			        var speed = res.speed; // 速度，以米/每秒计
			        var accuracy = res.accuracy; // 位置精度
			        
			        var originDistanceArr = new Array();
					var shopDistanceArr = new Array();
			        $('#allshop').find('li').each(function(){
						var lat = parseFloat($(this).attr('lat'));
						var lng = parseFloat($(this).attr('lng'));
						if(isNaN(lat)||isNaN(lng)){
							var distance = getFlatternDistance(latitude,longitude,0,0);
						}else{
							var distance = getFlatternDistance(latitude,longitude,lat,lng);
						}
						$(this).attr('distance',distance);
						var searil = $(this).attr('searil');
						if(distance >= 1000){
							originDistanceArr[searil] = distance;
				 	  		shopDistanceArr[searil] = distance;
							distance = (distance/1000).toFixed(2)+'千米';
							$(this).find('span.right').html(distance);
						}else if(distance < 1000){
							originDistanceArr[searil] = distance;
				 	  		shopDistanceArr[searil] = distance;
							distance = distance.toFixed(2)+'米';
							$(this).find('span.right').html(distance);
						}
				    });
			        if(shopDistanceArr.length==0){
						$("#tips").show();
					}else{
						$("#tips").hide();
						sortShop(originDistanceArr,shopDistanceArr);
					}
			        var latLng = new qq.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
			        //调用获取位置方法
			        geocoder.getAddress(latLng);
			    },
		    	cancel: function (res) {
		    		layer.closeAll('loading');
		            layer.msg('用户拒绝授权获取地理位置');
		        }
			});
	    });
	</script>
</body>
