<?php
/* @var $this ProductController */
	$parentCategorys = ProductCategory::getCategorys($this->companyId);	
?>
<?php if($parentCategorys):?>
<div class="category">
<?php foreach($parentCategorys as $categorys):?>
	<div >
    <div class="pcat"><?php echo $categorys['category_name'];?></div>
	<?php foreach($categorys['children'] as $category):?>
	<a href="<?php echo $this->createUrl('/product/index',array('pid'=>$categorys['category_id'],'category'=>$category['category_id']));?>"><div class="catename"><?php echo $category['category_name'];?></div></a>
	<?php endforeach;?>
	<div class="clear"></div>
	</div>
<?php endforeach;?>
</div>
<?php endif;?>