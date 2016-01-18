<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('视频弹幕');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/screen.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>


<body>
	<div class="main_discuss">
		<div class="bottom"></div>
	</div>
	<div class="bg"><img src="<?php echo $screen['discuss_pic'];?>" /></div>
</body>
		
<script type="text/javascript">
		
		function init_barrage(obj){
			var _top = 20;
			$('.main_discuss').find('.'+obj).show().each(function(){
				var topW = $('.main_discuss').width();
				var thisW = $(this).width();
				var _height = $('.main_discuss').height();
				var _left = topW + thisW;
				
				
				$(this).css({left:topW + 20,top:_top,color:getRandomColor()});

				_top +=100;
				if(_top > _height - 130){
					_top = 20;
				}
				var time = 20000;
				if($(this).index() % 2 == 0){
					time = 15000;
				}
				$(this).animate({left:"-"+_left+"px"},time,function(){
					$(this).remove();
				});
			});
		}
		function init_bottom_barrage(obj){
			$('.bottom').find('.message').show().each(function(){
				var topW = $('.main_discuss').width();
				var thisW = $(this).width();
				var _height = $('.main_discuss').height();
				var _left = topW + thisW;
				
				
				$(this).css({left:topW + 20,color:getRandomColor()});

				var time = 15000;
				
				$(this).animate({left:"-"+_left+"px"},time,function(){
					$(this).remove();
					bottomTips();
				});
			});
		}
		//获取随机颜色
		function getRandomColor(){
			return '#' + (function(h){
				return new Array(7 - h.length).join("0") + h
			})((Math.random() * 0x1000000 << 0).toString(16))
		}
		function bottomTips(){
			$.ajax({
					url:'<?php echo $this->createUrl('/screen/ajaxGetScreenTips',array('companyId'=>$this->companyId,'screenId'=>$screen['lid']));?>',
					dataType:'json',
					success:function(msg){
						if(msg){
							$(".bottom").append('<div class="message ">'+msg['default_content']+'</div>');
						}
						init_bottom_barrage();
					},

				});
		}
		$(document).ready(function(){
			bottomTips()
			var i = 0;
			 setInterval(function(){
				$.ajax({
					url:'<?php echo $this->createUrl('/screen/ajaxGetDiscuss',array('companyId'=>$this->companyId,'screenId'=>$screen['lid']));?>',
					dataType:'json',
					success:function(msg){
						for(var p in msg){
							$(".main_discuss").append('<div class="message ms'+i+'">'+msg[p]['content']+'</div>');
						}
						init_barrage('ms'+i);
						i++;
						if(i > 100000){
							i = 0;
						}
					},

				});
			 },3000);
		});
</script>