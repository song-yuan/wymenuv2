<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('领券专区');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cupon.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<style>
body{background: #EC5D5D;}
</style>
<div class=" " id="wrap">
    <div class="topbox"><img src="../img/mall/cupon.png" /></div>
     <ul>
     <?php foreach($cupons as $cupon):?>
     <?php if($cupon['is_used']==2):?>
     <li><a href="<?php echo $this->createUrl('/mall/cuponInfo',array('companyId'=>$this->companyId,'detailid'=>$cupon['lid']));?>">
		<div class="rindex">
            <div class="rindex_box">
                <div class="rindex_icon">
                     <div class="rtitl">
                         <h1><?php echo $cupon['title'];?></h1>
						 <div class="rtitl_h">
						 <?php if($cupon['promotion_type']==2):?>
						 <floor title="#">消费满</floor><?php echo $cupon['min_consumer'];?><floor title="#">可用</floor>
						 <?php endif;?>
						 </div>                                 
						 <div class="rtitl_h">
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
		</a></li>
     <?php else:?>
		<li><a href="<?php echo $this->createUrl('/mall/cuponInfo',array('companyId'=>$this->companyId,'detailid'=>$cupon['lid']));?>">
		<div class="index">
            <div class="index_box">
                <div class="index_icon">
                     <div class="titl">
                         <h1><?php echo $cupon['title'];?></h1>
						 <div class="titl_h">
						 <?php if($cupon['promotion_type']==2):?>
						 <floor title="#">消费满</floor><?php echo $cupon['min_consumer'];?><floor title="#">可用</floor>
						 <?php endif;?>
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
		</a></li>
		<?php endif;?>
		<?php endforeach;?>
   </ul>
</div>


<script> 
$(document).ready(function(){ 
	<?php if(isset($msg)&&$msg):?>
	layer.msg('<?php echo $msg;?>');
	<?php endif;?>
	

}) 
</script> 