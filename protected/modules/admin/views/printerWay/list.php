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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','清单打印机'),'subhead'=>yii::t('app','清单打印机'),'breadcrumbs'=>array(array('word'=>yii::t('app','打印机管理'),'url'=>$this->createUrl('site/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','修改打印机'),'url'=>''))));?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','清单打印机');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
                                                        <?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'printer-form',
									'errorMessageCssClass' => 'help-block',                                                              
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
							<div class="form-group">
                                                                <?php echo $form->label($model, 'printer_id',array('class' => 'col-md-3 control-label'));?>
                                                                <div class="col-md-4">
                                                                        <?php echo $form->dropDownList($model, 'printer_id', array('0' => yii::t('app','-- 请选择 --'))+$printers  ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('printer_id')));?>
                                                                        <?php echo $form->error($model, 'printer_id' )?>
                                                                </div>
                                                        </div>
                                                        <div class="form-actions fluid">
                                                                <div class="col-md-offset-3 col-md-9">
                                                                        <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                                                                    
                                                                </div>
                                                        </div>
                                                        <?php $this->endWidget(); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  