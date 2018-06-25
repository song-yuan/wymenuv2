	<!-- BEGIN PAGE -->  
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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','仓库管理'),'subhead'=>yii::t('app','修改采购订单详情'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','采购订单管理'),'url'=>$this->createUrl('purchaseOrder/ckindex' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','采购订单详情管理'),'url'=>$this->createUrl('purchaseOrder/ckdetailindex' , array('companyId'=>$this->companyId,'lid'=>$model->purchase_id,))),array('word'=>yii::t('app','修改采购订单详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('purchaseOrder/ckdetailindex' , array('companyId' => $this->companyId,'lid'=>$model->purchase_id,)))));?>
	
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','修改采购订单详情');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_ckdetailform', array('model'=>$model,'categories' => $categories,'categoryId'=>$categoryId,'materials'=>$materials,)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  