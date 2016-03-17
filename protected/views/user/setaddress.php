<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('地址列表');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/weui.min.css">
<style>
.weui_cell_ft{
	position:absolute;
	text-align:center;
	top:5px;
	right:10px;
	color:red;
	display:none;
}
.over-distance .weui_cell_ft{
	display:block;
}
</style>
<script type="text/javascript">
var editUrl = "<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId));?>";
</script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=hzj3D9srpRthGaFjOeBGvOG6"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<body class="my_address bg_lgrey2">
	<?php if($addresss):?>
	<ul class="addlist" id="list">
		<?php foreach($addresss as $k=>$address):?>
		<li id='<?php echo $address['lid'];?>'>
			<label for="add<?php echo $k+1;?>" address-id="<?php echo $address['lid'];?>">
			<span class="user">收货人：<?php echo $address['name'];?></span>
			<span class="font_l small">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
			<div class="weui_cell_ft"><i class="weui_icon_warn"></i><br>超出配送范围</div>
			</label>
			<input type="hidden" name="lng" value="<?php echo $address['lng'];?>" />
			<input type="hidden" name="lat" value="<?php echo $address['lat'];?>" />
		</li>
		<?php endforeach;?>
	</ul>
	<?php endif;?>
	<div class="tools">
		<ul>
			<li class="addicon"><a href="<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId,'url'=>$url));?>">添加收货地址</a></li>
		</ul>
	</div>
	<input type="hidden" name="Company_lng" value="<?php echo $company['lng'];?>" />
	<input type="hidden" name="Company_lat" value="<?php echo $company['lat'];?>" />
	<input type="hidden" name="Company_distance" value="<?php echo $company['distance']*1000;?>" />
	<input type="hidden" name="back" value="<?php echo urldecode($url);?>" />
</body>

<script>
var list = document.getElementById("list");
new SwipeOut(list);
list.addEventListener("delete", function(evt) {
	var addressId = evt.target.id;
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
	<?php if($type==2):?>
	var map = new BMap.Map("");
	var companyLng = $('input[name="Company_lng"]').val();
	var companyLat = $('input[name="Company_lat"]').val();
	var distance = $('input[name="Company_distance"]').val();
	var pointCompany = new BMap.Point(companyLng,companyLat);
	$('#list li').each(function(){
		var addressLng = $(this).find('input[name="lng"]').val();
		var addressLat = $(this).find('input[name="lat"]').val();
		var pointAddress = new BMap.Point(addressLng,addressLat);
		if(parseInt(map.getDistance(pointCompany,pointAddress)) > parseInt(distance)){
			$(this).addClass('over-distance');
		}
	});
	<?php endif;?>
	$('#list li').click(function(){
		if($(this).hasClass('over-distance')){
			layer.msg('超出配送范围');
			return;
		}
		var addressId = $(this).find('label').attr('address-id');
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