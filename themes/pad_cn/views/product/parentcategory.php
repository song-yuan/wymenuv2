<?php
/* @var $this ProductController  */
	$parentCategorys = ProductCategory::getCategorys($this->companyId);	
	$result = ProductClass::getCartInfo($this->companyId,$siteNoId);	
	$resArr = explode(':',$result);
	$price = $resArr[0];
	$nums = $resArr[1];
//	var_dump($parentCategorys);exit;
?>
<?php 
	$baseUrl = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css"  href="<?php echo $baseUrl.'/css/product/categorypad.css';?>" />
<div class="fixed-top">
  <div class="top-left"> 
      
  	<div class="top-left-right">            
	<span class="category-all"><?php echo yii::t('app','首页');?></span>&nbsp;&nbsp;<span class="category-all-name"></span>
	</div>
  </div>
    
    <div class="padsetting"></div>
  
   <!--<div class="top-middle">
   	<button id="updatePadOrder">下单并打印</button>
   </div>-->
  <div class="top-right">
	  <div class="shoppingCart">
	     <div class="total-num num-circel"><?php echo $nums;?></div>
	  </div>
	  <div class="total-price"><?php if(Yii::app()->language=='jp') echo (int)Money::priceFormat($price); else echo Money::priceFormat($price);?></div>
          <div class="top-right-button">
                <button id="infoPadOrder"><?php echo yii::t('app','订单详情...');?></button>
           </div>
  	<div class="clear"></div>
  </div>
</div>
<div class="category-level1">
	
	<div class="category-level1-item isTOP10" category-id="TOP10" category-name="人气 TOP10"><div class="pad-productbuy" style="height:3.0em;background:white;color:rgb(239,68,77);"><div class="inmiddle" style="text-align:center;font-size:2.0em"><?php echo yii::t('app','人气 TOP10');?></div></div><img src="/wymenuv2/./img/top10/company_<?php echo $this->companyId;?>/product-top10.jpg"/></div>
	<div class="category-level1-item productset" category-id="productset" category-name="套餐"><div class="pad-productbuy" style="height:3.0em;background:white;color:rgb(239,68,77);"><div class="inmiddle" style="text-align:center;font-size:2.0em"><?php echo yii::t('app','套餐');?></div></div><img src="/wymenuv2/./img/top10/company_<?php echo $this->companyId;?>/productset.jpg"/></div>
	<?php if($parentCategorys):?>
		<?php foreach($parentCategorys as $categorys):?>
		<div class="category-level1-item" category-id="<?php echo $categorys['lid'];?>" category-name="<?php echo $categorys['category_name'];?>"><div class="pad-productbuy" style="height:3.0em;background:white;color:rgb(239,68,77);"><div class="inmiddle" style="text-align:center;font-size:2.0em"><?php echo $categorys['category_name'];?></div></div><img src="<?php echo $categorys['main_picture'];?>"/></div>
		<?php endforeach;?>
	<?php endif;?>
	<div class="clear"></div>
</div>
<?php if(Yii::app()->language=='jp'):?>
<input type="hidden"  name="language" value="1"  />
<?php else:?>
<input type="hidden"  name="language" value="0"  />
<?php endif;?>
