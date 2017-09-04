<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('../../../../css/product/ui-btn.css');
	Yii::app()->clientScript->registerCssFile('../../../../css/product/ui-img.css');
	Yii::app()->clientScript->registerCssFile('../../../../css/product/ui-list.css');
	Yii::app()->clientScript->registerCssFile('../../../../css/product/ui-base.css');
	Yii::app()->clientScript->registerCssFile('../../../../css/product/ui-box.css');
	Yii::app()->clientScript->registerCssFile('../../../../css/product/ui-color.css');
	Yii::app()->clientScript->registerCssFile('../../../../css/product/pic.css');
	Yii::app()->clientScript->registerCssFile('../../../../css/product/ui-media.css'); 
	Yii::app()->clientScript->registerScriptFile('../../../../js/waiter/zepto.js');
	Yii::app()->clientScript->registerScriptFile('../../../../js/waiter/base64.js'); 
	Yii::app()->clientScript->registerScriptFile('../../../../js/waiter/pic.js');
	Yii::app()->clientScript->registerScriptFile('../../../../js/jquery-1.10.2.min.js');
	Yii::app()->clientScript->registerScriptFile('../../../../js/jquery.fly.min.js');  		 	
?>
<div class="y-body">
   	<div class="y-head">
   		<div class="icon left">
   			<div class="self">
   			<a href="<?php echo $this->createUrl('/ymall/user/index');?>"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/waiter/icon-dpczy.png"/></a>
   			</div>
   		</div>
   		<div class="logo">
   			<div class="yidianchi">
   			<a href=""><?php echo '壹点吃';?></a>
   			</div>
   		</div>
   		<div class="icon right">
   			<div class="search">
   			<a href="<?php echo $this->createUrl('/ymall/user/index');?>"><img src="<?php echo Yii::app()->request->baseUrl;?>/img/mall/icon_search2.png"/></a>
   			</div>
   		</div>
	</div>
   	<div class="top">
		<marquee>滚动条公告</marquee>
	</div>



	<!--content开始-->
    <div id="content" class="of-y content">
		<div id="forum_list" class="contentbox">
			<div style="width: 100%;height: auto;" id="goods-area">
			<?php if($mates):?>
			<?php foreach ($mates as $m):?>
				<div class="goods-class">
					<div class="goods-pic"><img src="<?php echo 'http://menu.wymenu.com/'.$m['main_picture']?>"/></div>
					<div><?php echo $m['goods_name'];?></div>
					<div>
						<div class="float-l color-r">￥ <?php echo $m['original_price'];?></div>
						<div class="float-l "><?php echo $m['goods_unit'];?></div>
						<div class="float-r mr-50 color-r ">
							<div class="addicon" >+</div>
						</div>
					</div>
					
				</div>
			<?php endforeach;?>
			<?php endif;?>
				<div style="clear: both;"></div>
			</div>
			
		</div>
		<!--列表结束-->
    </div>
    <!--content结束-->

</div>
	<div id="categorydiv" style="" class="catess">
		<div style="width: 150px;" class="productcate catedaohang zhankai">
			<div style="" class="catsbox">
				<div class="cates bg-color-orig"><span>首页</span></div>
				<?php if($cates):?>
				<?php foreach ($cates as $m):?>
				<div class="cates"><span><?php echo $m['category_name'];?></span></div>
				<?php endforeach;?>
				<?php endif;?>
				<div style="height: 15px;"><span><?php echo '';?></span></div>
			</div>
		</div>
		<div id="categorycont" class="dhcont yincang">>></div>
		<div id="categorycont2" class="dhcont "><<</div>
	</div>
	
	<div class="shoping-car">
		<div class="img"><span id="car_num">0</span></div>
	</div>
        <script src="../../../../js/jquery-1.10.2.min.js"></script> 
        <script src="../../../../js/jquery.fly.min.js"></script> 
<script type="text/javascript">
	var cat =<?php echo $child['lid'];?>;
	
	window.onload=function(type,catgory,companyId,code)
	{
		type = 1;
		catgory = cat;
		companyId=<?php echo $this->companyId;?>;
		
		
	}	
$(document).ready(function(){

	var categhCol = window.document.getElementById('categorydiv');
	var cateconwhCol = window.document.getElementById('categorycont2');
	var goodsarea = window.document.getElementById('goods-area');

	var pinmuHeight = document.body.clientHeight;
	var pinmuHeight2 = window.screen.availHeight;
	//alert(pinmuHeight);alert(pinmuHeight2);

	var bodyhg = document.body.clientHeight;
	 
    var hMainCol =  categhCol .offsetHeight;
    var wMainCol =  categhCol .offsetWidth;
    var whCol =  cateconwhCol .offsetWidth;

    var goodsimgwh = goodsarea .offsetWidth;
    //alert(hMainCol);
    window.document.getElementById('categorycont').style.height = hMainCol + 'px';
    window.document.getElementById('categorycont2').style.height = hMainCol + 'px';
    window.document.getElementById('content').style.height = pinmuHeight2 - 120 + 'px';
    //window.document.getElementById('categorydiv').style.width = whCol + 'px';
    $('.goods-pic').css('height',goodsimgwh/2 + 'px');
	$('.dhcont').on('touchend',function (){
		//alert(11);
		if($('.catedaohang').hasClass('zhankai')){
			$('.catedaohang').removeClass('zhankai');
			$('.catedaohang').addClass('shouqi');
			$('#categorycont').removeClass('yincang');
			$('#categorycont2').addClass('yincang');
			window.document.getElementById('categorydiv').style.width = whCol + 'px';
		}else{
			$('.catedaohang').addClass('zhankai');
			$('.catedaohang').removeClass('shouqi');
			$('#categorycont2').removeClass('yincang');
			$('#categorycont').addClass('yincang');
			window.document.getElementById('categorydiv').style.width = wMainCol + 'px';
		}
	});
	$('.cates').on('touchend',function(){
		$('.cates').removeClass('bg-color-orig');
		$(this).addClass('bg-color-orig');
	});
    $('.addicon').on('touchend',function(){
        var num = $('#car_num').text();
        //alert(num);
        var nums = parseInt(num) +1 ;

		//以下为添加物品的动画效果....
		var src=$("img",$(this)).attr("src");
        var offset = $("#car_num").offset();
        var addcar = $(this);
        var img = $("img",$(this)).attr("src");
        var flyer = $('<div style="width: 15px;height: 15px;border-radius: 10px; background-color: red;"></div>');
            
    	flyer.fly({
            
            start: {
                left: addcar.offset().left,//event.pageX,
                top: addcar.offset().top,//event.pageY
            },
            end: {
                left: offset.left,
                top: offset.top,
                width: 5,
                height: 5,
            },
            speed: 0.1,
            onEnd: function(){
            	
                this.destory();
            }
        });
    	//setTimeout("alert(22);alert(111);",500);
    	$('#car_num').html(nums);
    	
    });
    $('#forum_list').on('click','.numplus',function(){
    	var id = $(this).attr('product-id');
 		var numObj = $(this).siblings('.num');
 		var numVal = parseInt(numObj.val());
 		$.ajax({
 			url:'<?php echo $this->createUrl('/waiter/product/createCart',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>&id='+id,
 			success:function(msg){
 				if(msg){
 					numVal += 1;
 					numObj.val(numVal); 
 				}
 			},
 		});
    });
 	
     $('#forum_list').on('click','.numminus',function(){
     	var id = $(this).attr('product-id');
 		var numObj = $(this).siblings('.num');
 		var numVal = parseInt(numObj.val());
 		if(numVal>0){
 			$.ajax({
 			url:'<?php echo $this->createUrl('/waiter/product/deleteCartProduct',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>&id='+id,
 			success:function(msg){
 				if(msg){
 					numVal -= 1;
 					numObj.val(numVal);
 				}
 			},
 		});
 		}
     });
 	$(window).on('touchend',function(e){
 	 	return false;
		var a = document.body.scrollHeight;
		var b = document.documentElement.clientHeight;
		var c = document.documentElement.scrollTop + document.body.scrollTop;
		//var c = document.documentElement.scrollTop || window.pageYOffset || document.body.scrollTop;
		var totalHeight = c+b+30;
		if(totalHeight >= a ){
			$('#nextpage').text('数据加载中……');
			getMorePic(1,cat);
		} 
	})
 });
</script>