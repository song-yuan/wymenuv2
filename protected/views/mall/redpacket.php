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
    <div class="redpacket">
          <p class="title"><?php if($redPacket){foreach($redPacketDetails as $detail){ if($detail['promotion_type']==1) echo $detail['item']['promotion_title'].' ';else $detail['item']['cupon_money'].' ';}}else{ echo '活动已结束';}?></p>
    </div>
    <div class="guize">
      <span class="gztt">代金券使用规则：</span><br/>
     1. 代金券只有领取后才可使用<br/>
     2. 现金券不可折现，不可叠加使用<br/>
     3. 代金券使用限制见代金券<br/>
     4. 此次活动最终解释权归本店所有<br/>
    </div>
	</div>
</div>