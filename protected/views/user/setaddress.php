<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('地址列表');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<body class="my_address bg_lgrey2">
	<?php if($addresss):?>
	<ul class="addlist" id="list">
		<?php foreach($addresss as $k=>$address):?>
		<li id='<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId,'lid'=>$address['lid']));?>'>
			<input type="radio" id="add<?php echo $k+1;?>" name="addresslist" value="<?php echo $address['lid'];?>" <?php if($address['default_address']) echo 'checked';?>>
			<label for="add<?php echo $k+1;?>" address-id="<?php echo $address['lid'];?>">
			<span class="user">收货人：<?php echo $address['name'];?></span>
			<span class="font_l small">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
			</label>
		</li>
		<?php endforeach;?>
	</ul>
	<?php endif;?>
	<div class="tools">
		<ul>
			<li class="addicon"><a href="<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId,'url'=>$url));?>">添加收货地址</a></li>
		</ul>
	</div>
	<input type="hidden" name="back" value="<?php echo urldecode($url);?>" />
</body>

<script>
var list = document.getElementById("list");
new SwipeOut(list);
list.addEventListener("delete", function(evt) {
	var addressId = $(evt.target).find('label').attr('address-id');
	$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxDeleteAddress',array('companyId'=>$this->companyId));?>',
			data:{lid:addressId},
			type:'post',
			success:function(msg){
				history.go(0);
			}
		});
});

$(document).ready(function(){
	$('#list label').click(function(event){
		var addressId = $(this).attr('address-id');
		var userId = '<?php echo $userId;?>';
		var back = $('input[name="back"]').val();
		
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxSetAddress',array('companyId'=>$this->companyId));?>',
			data:{lid:addressId,userId:userId},
			type:'post',
			success:function(msg){
				layer.msg('正在跳转....');
				location.href = back;
			}
		});
	});
});
</script>