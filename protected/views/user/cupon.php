<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('代金券');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/reset.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/common.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cupon.css">

<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<div class="header">
		<!--
		<a href="index.html" class="back"></a>
		-->
        <span>我的代金券</span>
</div>
<?php if($cupons):?> 
<?php foreach($cupons as $cupon):?>
<?php if($cupon['end_time'] >= date('Y-m-d H:i:s',time())&&$cupon['is_used']==1):?>
<div class="index">
    <div class="index_box">
        <div class="index_icon">
             <div class="icon_h"><img src="<?php echo $cupon['main_picture'];?>" style="width:150px;height:150px;"></div>
             <div class="titl">
                 <h1><?php echo $cupon['cupon_title'];?></h1>
				 <div class="titl_h">
				 <floor title="#">消费满</floor><?php echo $cupon['min_consumer'];?><floor title="#">可用</floor>
				 </div>                                 
				 <div class="titl_h">
				 <floor title="#">使用期限：</floor>
                  <time title="#"><?php echo $cupon['begin_time'];?></time>
				  <span>~</span>
                  <time title="#"><?php echo $cupon['end_time'];?></time>
                  <I><span></span></I>
                 </div>
             </div>	
             <div class="clear"></div>
        </div>
    </div>
</div>
<?php else:?>    
<div class="rindex">
    <div class="rindex_box">
        <div class="rindex_icon">
             <div class="ricon_h"><img src="<?php echo $cupon['main_picture'];?>"></div>
             <div class="rtitl">
				<div class="rtitl_h">
                 <h1><?php echo $cupon['cupon_title'];?></h1> 
				 </div>
				 <div class="rtitl_h">
				 <floor title="#">消费满</floor><?php echo $cupon['min_consumer'];?><floor title="#">可用</floor>
				 <a><?php if($cupon['is_used']==2):?>（已使用）<?php else:?>（已过期）<?php endif;?></a>
				 </div>
				 <div class="rtitl_h">
				 
				 <floor title="#">使用期限：</floor>
                  <time title="#"><?php echo $cupon['begin_time'];?></time>
				  <span>~</span>
                  <time title="#"><?php echo $cupon['end_time'];?></time>
                  <I><span></span></I>
                 </div>
             </div>	
             <div class="clear">
			 </div>
        </div>
    </div>
</div>
<?php endif;?>
<?php endforeach;?>
<?php endif;?>


  
