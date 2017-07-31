<div class="page-content">
 <?php 
 	$result = $category_id->result;
 	$dpid = $this->companyId;	
 ?> 
 <table cellspacing="0" cellpadding="0" width="500" border="1">
 	<tr>
 		<td colspan="2">饿了么外卖菜品</td>
 		<td>收银机菜品</td>
 	</tr>
 	<?php foreach ($result as $category) {?>
 		<?php 
 			$categoryId = $category->id;
 		?>
 		<?php
			$products = Elm::getItems($dpid,$categoryId);
	 		$productsobj = json_decode($products,true);
	 		$resultid = $productsobj['result']; 
			foreach ($resultid as $product){
			$se=new Sequence("eleme_cpdy");
				$lid = $se->nextval();
				$creat_at = date("Y-m-d H:i:s");
				$update_at = date("Y-m-d H:i:s");
				$inserData = array(
							'lid'=>	$lid,
							'dpid'=> $dpid,
							'create_at'=>$creat_at,
							'update_at'=>$update_at,
							'elemeID'=>	$product['id'],
							'categoryId'=>$product['categoryId']
					);
				$res = Yii::app()->db->createCommand()->insert('nb_eleme_cpdy',$inserData);
			?>
 		<tr >
			<td><span><?php echo $product['id'];?></span></td>
			<td><span><?php echo $product['name'];?></span></td>
			<td><input type="" name="" disabled="disabled"> <span class="xuanze">选择关联菜品</span></td>
			
 		</tr>
 		<?php }?>
 	<?php }?>
 </table>
</div>
<script type="text/javascript">
		$(".xuanze").click(
			function(){
				window.open ("<?php echo $this->createUrl('eleme/glcp',array('companyId'=>$this->companyId));?>", "newwindow", "height=500, width=1000,top=350px,left=350px,toolbar =no, menubar=no, scrollbars=no, resizable=no, location=no, status=no") ;
			}
			)
</script>	