<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('个人中心');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<section class="my_address bg_lgrey2">
	<ul class="addlist" id="list">
		<li id="myadd1">
			<input type="radio" id="add1" name="addresslist" value="" checked>
			<label for="add1">
			<span class="user">收货人：Renee</span>
			<span class="font_l small">收货地址：上海市虹口区广纪路738号明珠创意园738号…</span>
			</label>
	
		</li>
	
		<li id="myadd2">
			<input type="radio" id="add2" name="addresslist" value="" >
			<label for="add2">
			<span class="user">收货人：Renee</span>
			<span class="font_l small">收货地址：上海市虹口区广纪路738号明珠创意园738号…</span>
			</label>
	
		</li>
	</ul>
	<div class="tools">
		<ul>
			<li class="addicon"><a href="<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId));?>">添加收货地址</a></li>
		</ul>
	</div>
</section>

<script>
var list = document.getElementById("list");
new SwipeOut(list);
list.addEventListener("delete", function(evt) {
	alert('地址已删除');
});
</script>