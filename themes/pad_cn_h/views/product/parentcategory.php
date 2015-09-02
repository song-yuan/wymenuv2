<?php
/* @var $this ProductController */
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
<link rel="stylesheet" type="text/css"  href="<?php echo $baseUrl.'/css/product/categorypad_h.css';?>" />

<div id="page_0_left">
	<?php if($parentCategorys):?>
		<?php foreach($parentCategorys as $categorys):?>
                  <div class="category-partents level1" lid="<?php echo $categorys['lid'];?>"><?php echo Helper::truncate_utf8_string($categorys['category_name'],6);?></div>
			<?php foreach($categorys['children'] as $category):?>
                   <div class="child level2" lid="<?php echo $category['lid'];?>"><a href="javascript:;"><?php echo Helper::truncate_utf8_string($category['category_name'],6);?></a></div>
			<?php endforeach;?>
		<?php endforeach;?>
		<?php endif;?>
</div>
 
<?php if(Yii::app()->language=='jp'):?>
<input type="hidden"  name="language" value="1"  />
<?php else:?>
<input type="hidden"  name="language" value="0"  />
<?php endif;?>
