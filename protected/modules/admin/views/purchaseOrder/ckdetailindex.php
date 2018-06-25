<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','仓库管理'),'subhead'=>yii::t('app','采购订单详情列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','仓库管理'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','采购订单管理'),'url'=>$this->createUrl('purchaseOrder/ckindex' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','采购订单详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('purchaseOrder/ckindex' , array('companyId' => $this->companyId,)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('purchaseOrder/ckdetailDelete' , array('companyId' => $this->companyId,'polid'=>$polid,'status'=>$status,)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','采购订单详情列表');?></div>
					<div class="actions">
					
					<?php if(in_array($status,array(0,2)) && Yii::app()->user->role >=11):?>
						<a href="<?php echo $this->createUrl('purchaseOrder/ckdetailcreate' , array('companyId' => $this->companyId,'lid'=>$polid));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
					<?php endif;?>	
					</div>
					
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','商品名称');?></th>
								<th><?php echo yii::t('app','商品进价');?></th>
								<th><?php echo yii::t('app','采购数量');?></th>
								<th><?php echo yii::t('app','赠品数量');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
							<div style="display: none;" id="storagedetail" val="1"></div>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td style="width:16%"><?php echo Common::getgoodsName($model->material_id);?></td>
								<td><?php echo $model->price;?></td>
								<td ><?php echo $model->stock;?></td>
								<td><?php echo $model->free_stock;?></td>
								<td class="center">
								<?php if($status==0 && Yii::app()->user->role >=3):?>
								<a href="<?php echo $this->createUrl('purchaseOrder/ckdetailupdate',array('lid' => $model->lid , 'polid'=>$model->purchase_id, 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								<?php endif;?>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
							<div style="display: none;" id="storagedetail" val="0"></div>
						<?php endif;?>
						<tr>
							<td colspan="20" style="text-align: right;">
							<?php if($purchase->status == 0):?>
								<span style="color:red">正在编辑</span>
								<input id="verify-pass" purchase-id="<?php echo $polid;?>" type="button" class="btn blue" value="生成采购单" />&nbsp;
							<?php else:?>
								<input id="storageOrder" purchase-id="<?php echo $polid;?>" type="button" class="btn blue" value="生成入库单" />&nbsp;
							<?php endif;?>
							</td>
						</tr>
						</tbody>
					</table>
					</div>
					<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
							</div>

							<div class="col-md-7 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap">
								<?php $this->widget('CLinkPager', array(
									'pages' => $pages,
									'header'=>'',
									'firstPageLabel' => '<<',
									'lastPageLabel' => '>>',
									'firstPageCssClass' => '',
									'lastPageCssClass' => '',
									'maxButtonCount' => 8,
									'nextPageCssClass' => '',
									'previousPageCssClass' => '',
									'prevPageLabel' => '<',
									'nextPageLabel' => '>',
									'selectedPageCssClass' => 'active',
									'internalPageCssClass' => '',
									'hiddenPageCssClass' => 'disabled',
									'htmlOptions'=>array('class'=>'pagination pull-right')
								));
								?>
								</div>
							</div>

						</div>
						<?php endif;?>					
						</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$('#verify-pass').click(function(){
			var pid = $(this).attr('purchase-id');
			var storagedetail = $('#storagedetail').attr('val');
			
			if(storagedetail == 1){
				if(confirm('确认生成该采购订单')){
					$.ajax({
						url:'<?php echo $this->createUrl('purchaseOrder/purchaseVerify',array('companyId'=>$this->companyId));?>',
						data:{type:1,pid:pid},
						success:function(msg){
							if(msg=='true'){
								alert('生成成功');
							}else{
								alert('生成失败');
							}
							location.href="<?php echo $this->createUrl('purchaseOrder/ckindex' , array('companyId'=>$this->companyId,));?>";
						}
					});
				}
			}else{
				alert('请添加需要采购的品项');
			}
		});
		$('#storageOrder').click(function(){
			var pid = $(this).attr('purchase-id');
			if(confirm('确认生成入库订单')){
				$.ajax({
					url:'<?php echo $this->createUrl('purchaseOrder/storageOrder',array('companyId'=>$this->companyId));?>',
					data:{pid:pid},
					success:function(msg){
						if(msg=='true'){
							alert('生成订单成功');
						}else{
							alert('生成订单失败');
						}
						history.go(0);
					}
				});
			}
		});
	});
	</script>