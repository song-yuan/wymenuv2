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
    <div class="topbox"><img src="../img/mall/cupon.png" /></div>
     <ul>
		<div class="index">
            <div class="index_box">
                <div class="index_icon">
                     <div class="titl">
                         <h1><?php echo $is_success;?></h1>
						 <div class="titl_h">
						 <span><?php echo $status;?></span>
						 <span><?php echo $result_code;?></span>
						 <span><?php echo $result_message;?></span>
						 </div>                                 
                     </div>	
                </div>
            </div>
        </div>
		</a></li>
   </ul>
</div>
