<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('新增地址');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/members.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/address.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/hammer.js"></script>
<script src="<?php echo $baseUrl;?>/js/mall/swipeout.js"></script>
<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=hzj3D9srpRthGaFjOeBGvOG6"></script>

<section class="add_address bg_lgrey2">
	<form action="<?php echo Yii::app()->createUrl('/user/generateAddress',array('companyId'=>$this->companyId,'url'=>$url));?>" method="post" onsubmit="return validate()">
		<ul class="complete_add">
			<li><label for="name">收货人</label><input type="text" id="name" name="address[name]" placeholder="名字" value="<?php echo $address?$address['name']:'';?>"></li> 
			<li><label for="tel">手机号码</label><input type="text" id="mobile" name="address[mobile]" placeholder="11位手机号码" value="<?php echo $address?$address['mobile']:'';?>"></li>
			<li><label for="area">选择地区</label>
                <select id="province" name="address[province]" style="width:23%;"></select>
                <select id="city" name="address[city]"style="width:23%;"></select>
                <select id="area" name="address[area]"style="width:23%;"></select>
            </li>
			<li><label for="receiver">详细地址</label><input type="text" id="street" name="address[street]" placeholder="街道门牌信息" value="<?php echo $address?$address['street']:'';?>"></li>
			<li><label for="receiver">邮政编码</label><input type="text" id="postcode" name="address[postcode]" placeholder="邮政编码" value="<?php echo $address?$address['postcode']:'';?>"></li>
			<li>
			<div class="left">设置为默认配送地址</span></div>
			<div class="right">
			<label><input type="checkbox" name="address[default_address]" class="ios-switch green  bigswitch" <?php echo $address&&$address['default_address']?'checked':'';?> value="1"/><div><div></div></div></label>
			</div>
				
			</li>
		</ul>
		<input type="hidden" name="address[user_id]" value="<?php echo $userId;?>"/>
		<input type="hidden" name="address[lid]" value="<?php echo $address?$address['lid']:'-1';?>"/>
		<input type="hidden" name="address[lng]" value="<?php echo $address?$address['lng']:'0';?>"/>
		<input type="hidden" name="address[lat]" value="<?php echo $address?$address['lat']:'0';?>"/>
		<div class="bttnbar">
		<button class="bttn_black2 bttn_large" type="button"><a href="<?php echo $this->createUrl('/user/address',array('companyId'=>$this->companyId));?>">取消</a></button>
		<button class="bttn_black2 bttn_large" type="submit">保存</button>
		</div>
	</form>
</section>
<script type="text/javascript">
 <?php
        if($address){
    ?>
    addressInit('province', 'city', 'area', '<?php echo $address['province'];?>', '<?php echo $address['city'];?>', '<?php echo $address['area'];?>');
    <?php }else{?>
    addressInit('province', 'city', 'area', '', '', '');
    <?php }?>
  function validate() {
        if($('#name').val() == ''){
            alert('请填写收货人名字！');
            return false;}
        if($('#mobile').val() == ''){
            alert('请填写收货人手机！');
            return false;}
        if($('#province').val() == '请选择..' ||$('#province').val() == '' || $('#city').val() == '' || $('#area').val() == ''){
            alert('请选择地区！');
            return false;}
        if($('#street').val() == ''){
            alert('请填写详细地址！');
        return false;}
    }
    $(document).ready(function(){
    	var myGeo = new BMap.Geocoder();
    	$('#street').change(function(){
    		var city = $('#city').val();
    		var area = $('#area').val();
    		var street = $('#province').val();
    		myGeo.getPoint(city + area + street, function(point){
				if (point) {
					alert(point.lng);alert(point.lat);
					$('input[name="address[lng]"]').val(point.lng);
					$('input[name="address[lat]"]').val(point.lat);
				}else{
					alert("您选择地址没有解析到结果!");
				}
			}, city);
    	});
    });
</script>
