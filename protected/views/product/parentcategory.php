<?php
/* @var $this ProductController */
	$parentCategorys = ProductCategory::getCategorys($this->companyId);	
?>

<link rel="stylesheet" type="text/css"  href="../css/product/category.css" />
<script type="text/javascript" src="../js/product/category.js"></script>
<?php if($parentCategorys):?>
<ul class="promptu-menu">
	<?php foreach($parentCategorys as $categorys):?>
	<li>
		<ul>
		<li class="parents"><?php echo $categorys['category_name'];?></li>
		<?php foreach($categorys['children'] as $category):?>
			<a href="<?php echo $this->createUrl('/product/index',array('pid'=>$category['pid'],'categoryId'=>$category['lid']));?>">
			   <li class="child <?php if($category['lid']==$categoryId) echo 'active';?>">
			   <?php echo $category['category_name'];?>
			   </li>
			 </a>
		<?php endforeach;?>
		</ul>
	</li>	
	<?php endforeach;?>
</ul>
<?php endif;?>
<script>
	$(function(){
		$('ul.promptu-menu').promptumenu({height:200, rows: 1, columns: 4, direction: 'horizontal', pages: false});
	});
</script>
