<?php
/* @var $this ProductController */
	$parentCategorys = ProductCategory::getCategorys($this->companyId);	
?>

<link rel="stylesheet" type="text/css"  href="../css/product/category.css" />
<script type="text/javascript" src="../js/product/category.js"></script>
<?php if($parentCategorys):?>
<ul class="promptu-menu">
	<li>
		<ul>
			<li class="parents">热点</li>
			<li class="child">推荐品</li>
			<li class="child">套餐</li>
			<li class="child">点赞TOP10</li>
			<li class="child">点单TOP10</li>
		</ul>
	</li>
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
	$(document).ready(function(){
		$('ul.promptu-menu').promptumenu({height:200, rows: 1, columns: 2, direction: 'horizontal', pages: false});
		var liWidth = 0;
		$('.promptu-menu').children('li').each(function(){
			$(this).css({'left':liWidth,'top':5});
			liWidth +=$(this).width();
		});
	});
</script>
