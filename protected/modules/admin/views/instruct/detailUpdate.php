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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','基础设置'),'subhead'=>yii::t('app','编辑指令明细'),'breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','指令设置'),'url'=>$this->createUrl('instruct/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','指令明细列表'),'url'=>$this->createUrl('instruct/detailIndex' , array('companyId' => $model->dpid,'groupid'=>$groupid))),array('word'=>yii::t('app','编辑指令明细'),'url'=>''))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo $instruction->instruct_name;?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_detailForm', array('model'=>$model,'groupid'=>$groupid)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  