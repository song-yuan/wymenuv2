<?php
/* @var $this ProductController */
Yii::app()->clientScript->registerCssFile('css/product.css');
?>
<div class="productcate">
<?php if($categorys):?>
<div class="inner" style="width:<?php echo count($categorys)*120+20;?>px;">
<?php foreach($categorys as $category):?>
  <a href="<?php echo $this->createUrl('/product/index',array('category'=>$category['category_id']));?>"><div class="catename <?php if($category['category_id']==$categoryId) echo 'active';?>"><?php echo $category['category_name'];?></div></a>
<?php endforeach;?>
</div>
<?php endif;?>
<div class="clear"></div>
</div>
<div class="productlist">
<?php if($products):?>
<?php foreach($products as $product):?>
  <div class="product">
    <div class="productimg">
      <img src="<?php echo $product['main_picture'];?>" width="100%" height="100%"/>
      <div class="productbuy">
	       <a class="numminus" href="javascript:;" product-id="<?php echo $product['product_id'];?>" origin_price="<?php echo $product['origin_price'];?>" price="<?php echo $product['price'];?>">-</a>
	       <input type="text" class="num" name="product_num" maxlength="8" value="0"/>
	       <a class="numplus" href="javascript:;" product-id="<?php echo $product['product_id'];?>" origin_price="<?php echo $product['origin_price'];?>" price="<?php echo $product['price'];?>">+</a>
      </div>
    </div>
    <div class="productname">
     <div class="name"><?php echo $product['product_name'];?></div>
     <div class="price">￥<?php echo $product['price'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;原价:￥<strike><?php echo $product['origin_price'];?></strike></div>
    </div>
  </div>
  <?php endforeach;?>
  <?php endif;?>
  <div class="clear"></div>
</div>
<script type="text/javascript">
 $(document).ready(function(){
 	Flipsnap('.inner'); 
 	Flipsnap('.inner',{
            distance:100    //每次移动的距离
        });
 	$('.numplus').click(function(){
 		var id = $(this).attr('product-id');
 		var numObj = $(this).siblings('.num');
 		var numVal = parseInt(numObj.val());
 		numVal += 1; 
 		numObj.val(numVal);
 		$.ajax({
 			url:'<?php echo $this->createUrl('/product/createCart');?>&id='+id,
 			success:function(msg){
 				if(msg==1){
 					alert('点单成功!');
 				}else if(msg==0){
 					alert('请重新点单!');
 				}else if(msg==2){
					alert(msg);
 				}
 			},
 		});
 	});
 	$('.numminus').click(function(){
 		var id = $(this).attr('product-id');
 		var numObj = $(this).siblings('.num');
 		var numVal = parseInt(numObj.val());
 		if(numVal>0){
 			numVal -= 1;
 			$.ajax({
 			url:'<?php echo $this->createUrl('/product/deleteCartProduct');?>&id='+id,
 			success:function(msg){
 				
 			},
 		});
 		}
 		numObj.val(numVal);
 	});
 });
</script>