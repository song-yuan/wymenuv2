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
	top:8px;
	right:10px;
	color:red;
	display:none;
}
.over-distance .weui_cell_ft{
	display:block;
}
</style>
<script type="text/javascript">
var editUrl = "<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId,'url'=>urldecode($url)));?>";
</script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=hzj3D9srpRthGaFjOeBGvOG6"></script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<body class="my_address bg_lgrey2">
	<?php if($addresss):?>
	<ul class="addlist" id="list">
		<?php foreach($addresss as $k=>$address):?>
		<?php $distance = WxAddress::getDistance($company['lat'],$company['lng'],$address['lat'],$address['lng']);?>
		<li id='<?php echo $address['lid'];?>' <?php if($type==2&&$company['distance']*1000 < $distance):?>class="over-distance"<?php endif;?>>
			<label for="add<?php echo $k+1;?>" address-id="<?php echo $address['lid'];?>">
			<span class="user">收货人：<?php echo $address['name'];?></span>
			<span class="font_l small">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
			<div class="weui_cell_ft small"><i class="weui_icon_warn"></i><br>超出范围</div>
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