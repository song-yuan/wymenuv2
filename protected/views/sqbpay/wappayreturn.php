<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('支付');
?>

<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/style.css">
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/mall/cupon.css">
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/mall/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl.'/js/layer/layer.js';?>"></script>
<style>
body{background: #EC5D5D;}
</style>
<div class=" " id="wrap">
		<div class="index">
            <div class="index_box">
                <div class="index_icon">
                     <div class="titl">
                         <h1><?php echo $is_success;?></h1>
						 <div class="titl_h">
						 <h2><?php echo $status;?></h2>
						 <h2><?php echo '正在处理订单，请稍后。。。';?></h2>
						 </div>                                 
                     </div>	
                </div>
            </div>
        </div>
</div>
<script>
	$(document).ready(function(){
		layer.load(2);
		)};
</script>
