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
.over-distance label{
	background:none !important;
}
</style>
<script type="text/javascript">
var editUrl = "<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId,'url'=>urldecode($url)));?>";
</script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<body class="my_address bg_lgrey2">
	<?php if($addresss):?>
	<ul class="addlist" id="list">
		<?php foreach($addresss as $k=>$address):?>
		<?php $distance = WxAddress::getDistance($company['lat'],$company['lng'],$address['lat'],$address['lng']);?>
		<li id='<?php echo $address['lid'];?>' <?php if($type==2&&$company['distance'] < $distance/1000):?>class="over-distance"<?php endif;?>>
			<input type="radio" id="add<?php echo $k+1;?>" name="addresslist" <?php if($address['default_address']){ echo 'checked';}?> value="" >
			<label for="add<?php echo $k+1;?>" address-id="<?php echo $address['lid'];?>" address-dpid="<?php echo $address['dpid'];?>">
			<span class="user">收货人：<?php echo $address['name'];?></span>
			<span class="font_l small">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?> <?php echo number_format($distance/1000,2);?>千米</span>
			<div class="weui_cell_ft small"><i class="weui_icon_warn"></i><br>超出范围</div>
			</label>
		</li>
		<?php endforeach;?>
	</ul>
	<?php endif;?>
	<input id="user_lid" type="hidden" value="<?php echo $user['lid'];?>">
	<input id="user_dpid" type="hidden" value="<?php echo $user['dpid'];?>">
	<div class="tools">
		<ul>
			<li class="addicon"><a href="<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId,'url'=>$url));?>">添加收货地址</a></li>
		</ul>
	</div>
	<input type="hidden" name="back" value="<?php echo urldecode($url);?>" />
</body>

<script>
function deleteItem(lid,dpid){
	$.ajax({
		url:"<?php echo $this->createUrl('/user/ajaxDeleteAddress',array('companyId'=>$this->companyId));?>",
		data:{lid,dpid:dpid},
		success:function(data){
			if(parseInt(data)){
				history.go(0);
			}else{
				layer.msg('删除失败');
			}
		}
	});
}
var list = document.getElementById("list");
new SwipeOut(list);
list.addEventListener("delete", function(evt) {
	var listId = evt.target.id;
	var dpid = $('#user_dpid').val();
	deleteItem(listId,dpid);
});

$(document).ready(function(){
	$('#list li').click(function(){
		if($(this).hasClass('over-distance')){
			layer.msg('超出配送范围');
			return;
		}
		var addressId = $(this).find('label').attr('address-id');
		var addressDpid = $(this).find('label').attr('address-dpid');
		var userId = $('#user_lid').val();
		var back = $('input[name="back"]').val();
		
		$.ajax({
			url:'<?php echo $this->createUrl('/user/ajaxSetAddress',array('companyId'=>$this->companyId));?>',
			data:{lid:addressId,dpid:addressDpid,userId:userId},
			type:'post',
			success:function(msg){
				layer.msg('正在跳转....');
				location.href = back;
			}
		});
	});
});
</script>