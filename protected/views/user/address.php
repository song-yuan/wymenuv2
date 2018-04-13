<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('地址列表');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<script type="text/javascript">
var editUrl = "<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId));?>";
</script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<body class="my_address bg_lgrey2">
	<?php if($addresss):?>
	<ul class="addlist" id="list">
		<?php foreach($addresss as $k=>$address):?>
		<li id="<?php echo $address['lid'];?>">
			<label for="add<?php echo $k+1;?>">
			<span class="user">收货人：<?php echo $address['name'];?></span>
			<span class="font_l small">收货地址：<?php echo $address['province'].$address['city'].$address['area'].$address['street'];?></span>
			</label>
		</li>
		<?php endforeach;?>
	</ul>
	<?php endif;?>
	<input id="address_dpid" type="hidden" value="<?php echo $user['dpid'];?>">
	<div class="tools">
		<ul>
			<li class="addicon"><a href="<?php echo $this->createUrl('/user/addAddress',array('companyId'=>$this->companyId));?>">添加收货地址</a></li>
		</ul>
	</div>
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
	var dpid = $('#address_dpid').val();
	deleteItem(listId,dpid);
});
</script>