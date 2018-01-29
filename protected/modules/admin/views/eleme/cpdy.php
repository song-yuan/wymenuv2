<div class="page-content">
 <div id="responsive" class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">Modal title</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn blue">Save changes</button>
					<button type="button" class="btn default" data-dismiss="modal">Close</button>
				</div>
			</div>
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	 <?php 
 	$result = $category_id->result;
 	$dpid = $this->companyId;	
 ?> 
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','饿了么外卖'),'url'=>$this->createUrl('eleme/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','菜品对应'),'url'=>$this->createUrl('eleme/cpdy' , array('companyId'=>$this->companyId,'type'=>0)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('eleme/index' , array('companyId' => $this->companyId,'type' => '0')))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'siteType-form',
				'action' =>'',
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
		<div class="portlet purple box">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','菜品对应');?></div>
			</div>
			<div class="portlet-body">
				<div class="table-responsive">
					<table class="tree table table-striped table-hover table-bordered dataTable">
						<tr>
 		<td>饿了么外卖菜品</td>
 		<td colspan="3">收银机关联菜品</td>
 	</tr>
 	<?php foreach ($result as $category) {?>
 		<?php 
 			$categoryId = $category->id;
 		?>
 		<?php
			$products = Elm::getItems($dpid,$categoryId);

	 		$productsobj = json_decode($products,true);
	 		$resultid = $productsobj['result'];	 
	 		// var_dump($resultid);exit();		
			foreach ($resultid as $product){
				$specs = $product['specs'];
			?>
 		
			<?php if(count($specs)>1):?>
				<?php foreach($specs as $spec):?>
			<tr>
			<td>
				<span class="id" style="display: none;"><?php echo $product['id'];?></span><span><?php echo $product['name'];?><?php echo $spec['name'];?></span>
			</td>
			<?php if(in_array($product['id'], $itemm ) && in_array($spec['specId'], $spemodel)){?>
			<td>
			<?php foreach ($items as $item):?>
			<?php if(in_array($spec['specId'], $item)):?>
				<span><?php echo $item['name'];?></span>
				<?php endif;?>

			<?php endforeach;?>		
			</td>
			<td>
				<a class="add_btn" pid="<?php echo $product['id'];?>" specsId="<?php echo $spec['specId'];?>" data-toggle="modal"><?php echo yii::t('app','菜品重新关联')?></a>
			</td>
			<?php }else{?>
			<td>
				<a class="add_btn" pid="<?php echo $product['id'];?>" specsId="<?php echo $spec['specId'];?>" data-toggle="modal"><?php echo yii::t('app','菜品关联')?></a>
			</td>
			<?php }?>
			</tr>
			<?php endforeach;?>
			<?php else:?>
			<tr >
				<td>
				<span class="id" style="display: none;"><?php echo $product['id'];?></span><span><?php echo $product['name'];?></span>
			</td>
				<?php if(in_array($product['id'], $itemm)){?>
			<td>
			<?php foreach ($items as $item):?>
			<?php if(in_array($product['id'], $item)):?>
				<span><?php echo $item['name'];?></span>
				<?php endif;?>

			<?php endforeach;?>		
			</td>
			<td>
				<a class="add_btn" pid="<?php echo $product['id'];?>" data-toggle="modal"><?php echo yii::t('app','菜品重新关联')?></a>
			</td>
			<?php }else{?>
			<td>
				<a class="add_btn" pid="<?php echo $product['id'];?>" data-toggle="modal"><?php echo yii::t('app','菜品关联')?></a>
			</td>
			<?php }?>
			<?php endif;?>
 		</tr>
 		<?php }?>
 	<?php }?>
					</table>
                </div>
			</div>
		</div>
	</div>
<?php $this->endWidget(); ?>
</div>
<script type="text/javascript">
	var $modal = $('.modal');
    $('.add_btn').on('click', function(){
        <?php if(Yii::app()->user->role > User::SHOPKEEPER):?>
         alert("您没有权限！！！");
         return false;
        <?php endif;?>
        id = $(this).attr('pid');
        specs = $(this).attr('specsId');
        if(specs){
        	 $modal.find('.modal-content').load('<?php echo $this->createUrl('eleme/glcp',array('companyId'=>$this->companyId));?>/id/'+id+'/specs/'+specs+'', function(){
	          $modal.modal();
	        });
        }else{
        	 $modal.find('.modal-content').load('<?php echo $this->createUrl('eleme/glcp',array('companyId'=>$this->companyId));?>/id/'+id+'', function(){
	          $modal.modal();
	        });
        }
    });
</script>