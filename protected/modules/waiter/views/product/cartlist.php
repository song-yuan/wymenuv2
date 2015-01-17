<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('css/cartlist.css');
?>
	<div class="waiter"><a href="<?php echo $this->createUrl('/waiter/seat/index');?>"><div class="waiter-back">返回座次列表</div></a></div>
	<div class="title">
	  <div class="seatnum"><input type="text" class="code" value="<?php  echo $this->seatNum;?>" /></div><a href="javascript:;"><div class="ordercart active">已选</div></a><a href="javascript:;"><div class="ordercart hasorder">已下单</div></a>
	<div class="clear"></div>
	</div>
	<div class="orderlist">
	 <div class="order-cat">
	  <div class="cat-left">订单总价:</div>
	  <div class="cat-right">共0元</div>
	 </div>
	  <div class="clear"></div>
	  <?php if($cartLists):?>
	  <?php 
	  		$totalprice = 0; 
	  		$products = array();
	  		foreach($cartLists as $cartList): 
	         $totalprice +=$cartList->product_num*$cartList->product->price;
	         $product = array('product_id'=>$cartList->product->product_id,'product_num'=>$cartList->product_num,'price'=>$cartList->product_num*$cartList->product->price);
	         array_push($products,$product);
	  ?>
	 <div class="order">
	    <a href="<?php echo $this->createUrl('/waiter/product/productInfo',array('id'=>$cartList->product->product_id,'cid'=>$this->companyId,'code'=>$this->seatNum));?>"> 
	    <div class="order-left"><img src="<?php echo $cartList->product->main_picture;?>" style="height:100%"/></div></a>
	    <div class="order-middle">
	      <lable><?php echo $cartList->product->product_name;?></lable><br/>
	      <lable>数量:<?php echo $cartList->product_num;?></lable><lable>  总金额:<?php echo $cartList->product_num*$cartList->product->price;?></lable><br/>
	      <lable>下单时间:<?php echo date('H:i:s',$cartList->create_time);?></lable>
	    </div>
	    <div class="order-right"><a href="<?php echo $this->createUrl('product/deleteCart',array('id'=>$cartList->cart_id,'cid'=>$this->companyId,'code'=>$this->seatNum));?>"><div class="delete"></div></a></div>
	  </div>
	 <?php 
	   endforeach;
	   $jsonproducts = json_encode($products);
	 ?>
	 <input type="hidden" id="totalprice" value="<?php echo$totalprice;?>"/>
	  <a href="javascript:;"><div class="orderbtn">下单</div></a>
	 <?php endif;?>
	 <a href="<?php echo $this->createUrl('/waiter/product/index',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>"><div class="addproduct">添加</div></a> 
	</div>
<script type="text/javascript">
	var products = [];
	var jsonproduct = <?php echo isset($jsonproducts)?$jsonproducts:0;?>;
	function parseData(){
		if(jsonproduct){
			for(var i in jsonproduct){
				var product = [];
				product.push(jsonproduct[i].product_id,jsonproduct[i].product_num,jsonproduct[i].price);
				products[i] = product;
			}
		}
	}
	function getTotal(){
		var price = $('#totalprice').val();
		if(price==undefined){
			price = 0;
		}
		$('.cat-right').html('共'+price+'元');
		parseData();
	}
	$(document).ready(function(){
	    window.load = getTotal();
	    $('.ordercart').click(function(){
	    	var code = $('.code').val();
	    	if(isNaN(code)){
	    		alert("请输入正确的开台号！");
	    		return;
	    	}else{
	    		location.href = '<?php echo $this->createUrl('/waiter/product/cartList');?>&code='+code+'&cid='+<?php echo $this->companyId;?>; 
	    	}
	    });
	     $('.hasorder').click(function(){
	    	var code = $('.code').val();
	    	if(isNaN(code)){
	    		return;
	    	}
	    	location.href = '<?php echo $this->createUrl('/waiter/product/orderList',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>'; 
	    });
	    $('.orderbtn').click(function(){
	    	var code = $('.code').val();
	    	if(isNaN(code)){
	    		alert("请输入正确的开台号！");
	    		return;
	    	}
	    	if(products.length==0){
	    		return;
	    	}else{
	    		$.ajax({
	    		url:'<?php echo $this->createUrl('/waiter/product/createOrder')?>&code='+code+'&cid='+<?php echo $this->companyId;?>,
	    		type:'POST',
	    		data:{'products':products},
	    		success:function(msg){
	    			if(msg!=0){
	    				location.href = '<?php echo $this->createUrl('/waiter/product/orderList',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>'; 
	    			}else{
	    				alert("请输入正确的开台号!");
	    			}
	    		},
	    	  });
	    	}
	    });
	});
</script>
