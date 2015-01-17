<?php
/* @var $this ProductController */
	Yii::app()->clientScript->registerCssFile('css/cartlist.css');
?>
	<!--<div class="orderup"><a href="<?php echo $this->createUrl('/waiter/product/cartList',array('id'=>$id,'cid'=>$this->companyId,'code'=>$this->seatNum));?>"><div class="ordercart">已选产品</div></a><a href="<?php echo $this->createUrl('/product/orderList',array('id'=>$id));?>"><div class="ordercart active">已下单产品</div></a></div>
	<div class="clear"></div>-->
	<div class="waiter"><a href="<?php echo $this->createUrl('/waiter/seat/index');?>"><div class="waiter-back">返回座次列表</div></a></div>
	<div class="title">
	  <div class="seatnum"><?php  echo $seatNum;?></div>
	  <a href="<?php echo $this->createUrl('/waiter/product/cartList',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>"><div class="ordercart">已选</div></a><a href="<?php echo $this->createUrl('/waiter/product/orderList',array('cid'=>$this->companyId,'code'=>$this->seatNum));?>"><div class="ordercart active">已下单</div></a>
	<div class="clear"></div>
	</div>
	
	 <div class="low-cost">
	  <label class="cost-left"><?php echo $totalPrice['remark'];?></label>
	 </div>
	 
	<div class="orderlist">
	 <div class="order-cat">
	  <div class="cat-left">应付:</div>
	  <div class="cat-right">共<?php echo $totalPrice['total'];?>元</div>
	  <div class="clear"></div>
	 </div>
	
	 
	  <?php if($orderProducts):?>
	  <?php 
	  		foreach($orderProducts as $product): 
	  ?>
	 <div class="order">
	   <a href="<?php echo $this->createUrl('/waiter/product/productInfo',array('id'=>$product['product_id'],'cid'=>$this->companyId,'code'=>$this->seatNum));?>">
	    <div class="order-left" style="width:30%;"><img src="<?php echo $product['main_picture'];?>" style="height:100%"/></div></a>
	    <div class="order-middle" style="width:60%;">
	      <lable><?php echo $product['product_name'];?></lable><br/>
	      <lable>数量:<?php echo $product['amount'];?></lable><lable>  总金额:<?php echo $product['price']*$product['amount'];?></lable><br/>
	      <lable>下单时间:<?php echo date('H:i:s',$time);?></lable>
	    </div>
	  </div>
	 <?php 
	   endforeach;
	 ?>
	 <?php endif;?>
	</div>