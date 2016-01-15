<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('视频弹幕');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/screen.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>


<body style="background:url(<?php echo $baseUrl;?>/img/bg/2.jpg);">
	<div class="main_discuss">
	
	</div>
</body>
		
<script type="text/javascript">
		
		function init_barrage(obj){
			var _top = 20;
			$('.main_discuss').find('.'+obj).show().each(function(){
				var topW = $('.main_discuss').width();
				var thisW = $(this).width();
				var _height = $('.main_discuss').height();
				var _left = topW + 20;
				
				
				$(this).css({left:_left,top:_top,color:getRandomColor()});

				_top +=100;
				if(_top > _height - 130){
					_top = 20;
				}
				var time = 15000;
				if($(this).index() % 2 == 0){
					time = 13000;
				}
				$(this).animate({left:"-"+_left+"px"},time,function(){
					$(this).remove();
				});
			});
		}
		//获取随机颜色
		function getRandomColor(){
			return '#' + (function(h){
				return new Array(7 - h.length).join("0") + h
			})((Math.random() * 0x1000000 << 0).toString(16))
		}
		$(document).ready(function(){
			var i = 0;
			 setInterval(function(){
				$.ajax({
					url:'<?php echo $this->createUrl('/screen/ajaxGetDiscuss',array('companyId'=>$this->companyId,'screenId'=>$screenId));?>',
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