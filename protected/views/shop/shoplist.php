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
		var showDiscount = <?php echo $this->company['distance'];?>;
		var geocoder;
		var init = function() {
		    geocoder = new qq.maps.Geocoder({
		        complete : function(result){
			        var district = result.detail.addressComponents.district;
			        var streetNumber = result.detail.addressComponents.streetNumber;
		          	$('#current_position').html(district+streetNumber);
		        }
		    });
		}
		function getShopList(){ 
	    	$.ajax({
		        url:'<?php echo $this->createUrl('/shop/ajaxGetShop',array('companyId'=>$this->companyId,'type'=>$this->type));?>',
		        data:{page:page,lat:latitude,lng:longitude,keyword:shopName},
		        success:function(msg){
		        	var isShowMore = false;
		        	var isShowTips = true;
			        if(msg.length > 0){
			        	var str = '';
			        	var count = 0;
				        for(var i=0;i<msg.length;i++){
					       var cObj = msg[i];
					       var juli = parseFloat(cObj.juli);
					       if(shopName==''){
					    	   if(juli > showDiscount*1000&&showDiscount!=0){
						    	   continue;
						       }
						   }
						   count++;
				    	   isShowTips = false;
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
					    		   <?php if($this->type==2):?>
					    			   str +='<span class=" font_l">营业时间: '+cObj.wm_shop_time+'-'+cObj.wm_closing_time+'</span><br>';
									<?php else:?>
										str +='<span class=" font_l">营业时间: '+cObj.shop_time+'-'+cObj.closing_time+'</span><br>';
									<?php endif;?>
						    		str +='<span style="font-weight:800;">电话: <a class="" href="tel:'+cObj.telephone+'">'+cObj.telephone+'</a></span>';
					    			str +='</span>';
							    if(juli > 1000){
							    	str +='<span class="right font_org">'+parseFloat(juli/1000).toFixed(2)+'千米</span>';
						    	}else{
						    		str +='<span class="right font_org">'+juli.toFixed(2)+'米</span>';
						    	}
						    	str +='</div>';
						   	str +='</div>';
						 	str +='</li>';
					    }
					    if(count == 10){
						   isShowMore = true;
					    }
					    $('#activeshop').append(str);
				    }
				    if(isShowMore){
				    	$('#more').show(); 
					}else{
						$('#more').hide();
					}
					if(isShowTips){
						$('#tips').show();
				    }else{
				    	$('#tips').hide();
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
 		// 点击店铺列表
 	    $('#activeshop').on('click','li',function(){
 		    var href = $(this).attr('href');
 		    location.href = href;
 		});
 		// 输入店铺名查找店铺
 		$("#name-search").change(function(){
 			page = 0;
 			shopName = $(this).val();
 			$('#activeshop').html('');
 			getShopList();
 		});
 		// 点击查找更多
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
		        },
		        fail: function (res) {
		        	layer.closeAll('loading');
		            layer.msg('请打开设备的定位服务');
		        }
			});
	    });
	</script>
</body>
