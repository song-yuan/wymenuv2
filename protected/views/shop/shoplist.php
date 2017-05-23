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
			</ul>
			<!-- 全部门店 -->
			<ul id="activeshop" class="shown">
			
			</ul>
			<div id="tips" class="info" style="text-align:center;">附近暂无餐厅可提供该服务,试试搜索吧!</div>
			<div id="more" class="info" style="text-align:center;margin-bottom:20px;display:none;">点击查看更多</div>
	    </div>
	</div>
	<script type="text/javascript">
		var page = 0;
		var latitude = 0;
		var longitude = 0;
		var shopName = '';
		function getShopList(){ 
	    	$.ajax({
		        url:'<?php echo $this->createUrl('/shop/ajaxGetShop',array('companyId'=>$this->companyId));?>',
		        data:{page:page,lat:latitude,lng:longitude,keyword:shopName},
		        success:function(msg){
			        if(msg.length > 0){
			        	if(msg.length==10){
			        		$('#more').show();
			        	}else{
			        		$('#more').hide();
			        	}
			        	var str = '';
				        for(var i=0;i<msg.length;i++){
					       var cObj = msg[i];
					       str +='<li href="<?php echo $this->createUrl('/mall/index');?>?companyId='+cObj.dpid+'&type=<?php echo $this->type;?>" lat="'+cObj.lat+'" lng="'+cObj.lng+'">';
					       str +='<div class="right">';
					    	   str +='<h1><span class="com-name">'+cObj.company_name+'</span><span class="rest_message small font_l">';
					    	   if(cObj.is_rest=='2'){
					    		   str +='(休息中...)';
						    	}
						    	str +='</span></h1>';
					    	   	str +='<div class="info small font_l" style="margin-top:5px;">地址: <span class="address_info">'+cObj.province;
					    	   	if(cObj.city!='市辖区'){
					    		   str +=cObj.city;
						    	}
					    	   	str +=cObj.county_area+cObj.address+'</span><span class="open-location"><img alt="" src="<?php echo $baseUrl;?>/img/wechat_img/icon_location.png" style="width:20px;height:20px;vertical-align:middle;"></span></div>';
					    	   	str +='<div class="misinfo small" style="margin-top:5px;">';
					    		   str +='<span class="left">';
					    			   str +='<span class=" font_l">营业时间: '+cObj.shop_time+'-'+cObj.closing_time+'</span><br>';
					    			   str +='<span style="font-weight:800;">电话: <a class="" href="tel:'+cObj.telephone+'">'+cObj.telephone+'</a></span>';
					    			   str +='</span>';
								var juli = parseFloat(cObj.juli);
							    if(juli > 1000){
							    	str +='<span class="right font_org">'+parseFloat(juli/1000).toFixed(2)+'千米</span>';
						    	}else{
						    		str +='<span class="right font_org">'+juli.toFixed(2)+'米</span>';
						    	}
						    	str +='</div>';
						   	str +='</div>';
						 	str +='</li>';
					    }
				        if(page==0){
				        	$('#tips').hide();
				        	$('#activeshop').html(str);
					    }else{
					    	$('#activeshop').append(str);
					    }
				    }else{
					    if(page > 0){
						    $('#more').hide();
						}
				    }
		        },
		        dataType : 'json'
		    });
		} 
    	$('#activeshop').on('click','a',function(event){
 	    	event.stopPropagation();
 		});
    	$('#activeshop').on('click','.open-location',function(event){
 	    	var liObj = $(this).parents('li');
 	    	var latitude1 = parseFloat(liObj.attr('lat'));
 	    	var longitude1 = parseFloat(liObj.attr('lng'));
 	    	var name = liObj.find('.com-name').html();
 	    	var address = liObj.find('.address_info').html();
 	    	var infoUrl = '<?php echo Yii::app()->request->getHostInfo();?>'+liObj.attr('href');
 	    	wx.openLocation({
 	    	    latitude: latitude1, // 纬度，浮点数，范围为90 ~ -90
 	    	    longitude: longitude1, // 经度，浮点数，范围为180 ~ -180。
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
 			page = 0;
 			shopName = $(this).val();
 			getShopList();
 		});
 		$('#more').on('click',function(){
 	    	page++;
 	    	getShopList();
 		});
	    wx.ready(function () {
	    	layer.load(2);
	    	wx.getLocation({
			    type: 'gcj02', // 默认为wgs84的gps坐标，如果要返回直接给openLocation用的火星坐标，可传入'gcj02'
			    success: function (res) {
			    	layer.closeAll('loading');
			        latitude = parseFloat(res.latitude); // 纬度，浮点数，范围为90 ~ -90
			        longitude = parseFloat(res.longitude); // 经度，浮点数，范围为180 ~ -180。
			        var speed = res.speed; // 速度，以米/每秒计
			        var accuracy = res.accuracy; // 位置精度
			     
			        var latLng = new qq.maps.LatLng(parseFloat(latitude), parseFloat(longitude));
			        //调用获取位置方法
			        geocoder.getAddress(latLng);
				
			        getShopList();
			    },
		    	cancel: function (res) {
		    		layer.closeAll('loading');
		            layer.msg('用户拒绝授权获取地理位置');
		        }
			});
	    });
	</script>
</body>
