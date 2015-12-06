<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('领取');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/receivecupon.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>

<div class=" " id="wrap">
    <div class="topbox"><img src="../img/mall/cupon/top1.jpg" /></div>
    <div class="cj_box" id="lottery">
            <ul>
                    <li class="lottery-unit  lottery-unit-0 active"><img src="../img/mall/cupon/k100.png" alt="" width="80px" height="131px"/></li>
                    <li class="lottery-unit lottery-unit-1 active"><img src="../img/mall/cupon/y5.png" alt="" width="105px" height="80px" /></li>
                    <li class="lottery-unit lottery-unit-2 active"><img src="../img/mall/cupon/y50.png" alt="" width="105px" height="80px" /></li>
                    <li class="pt5"><img src="../img/mall/cupon/cj1.jpg" alt="" width="130px" height="46px" class="btn" id="receive"/></li>
                    <li class="lottery-unit pt5 fr lottery-unit-3  active"><img src="../img/mall/cupon/k200.png" alt="" width="80px" height="131px"/></li>
                    <li class="lottery-unit pt5 fr lottery-unit-4 active"><img src="../img/mall/cupon/y25.png" alt="" width="105px" height="80px" /></li>
                    <li class="lottery-unit pt5 fr lottery-unit-5 active"><img src="../img/mall/cupon/y10.png" alt="" width="105px" height="80px" /></li>
                    <div class="clear"></div>
            </ul>
    </div>
    <div class="guize">
      <span class="gztt">代金券使用规则：</span><br/>
     1. 代金券只有领取后才可使用<br/>
     2. 现金券不可折现，不可叠加使用<br/>
     3. 代金券使用限制见代金券<br/>
     4. 此次活动最终解释权归本店所有<br/>
    </div>
    <input type="hidden" name="ptype" value="<?php echo $ptype;?>"/>
    <input type="hidden" name="lid" value="<?php echo $lid;?>"/>
</div>
</div>


<script> 
$(document).ready(function(){ 
	$('#receive').click(function(){
		var ptype = $('input[name="ptype"]').val();
		var lid = $('input[name="lid"]').val();
		$.ajax({
			url:'<?php echo $this->createUrl('/mall/getCupon',array('companyId'=>$this->companyId));?>',
			data:{type:ptype,lid:lid},
			success:function(msg){
				layer.msg(msg);
			}
		});
	});
}) 
</script> 