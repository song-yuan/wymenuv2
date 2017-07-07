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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','地區分组'),'url'=>$this->createUrl('areaGroup/detailindex' , array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid,'type'=>$type))),array('word'=>yii::t('app','添加地區店铺'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('areaGroup/detailindex' , array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid,'type'=>$type)))));?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><?php echo yii::t('app','添加地區店铺');?></div>
						</div>
					</div>
						<div class="portlet-body form" style="margin-top:20px;">
							<!-- BEGIN FORM-->
							<?php $form=$this->beginWidget('CActiveForm', array(
							    'id'=>'area-group-add-form',
							    'action'=>$this->createUrl('areaGroup/add',array('companyId'=>$this->companyId,'type'=>$type,'areagroupid'=>$areagroupid)),
								'errorMessageCssClass' => 'help-block',
								'htmlOptions' => array(
									'class' => 'form-horizontal',
									'enctype' => 'multipart/form-data',
								),
							    'enableAjaxValidation'=>false,
							)); ?>
								<?php if($models): ?>
								<?php foreach($models as $model): ?>
							    <div class="col-md-4" style="margin-top:10px;border:1px solid gray;border-radius:3px;background: #abc;">
							        <input  type="checkbox" name="dpid[]" id="<?php echo $model['dpid']; ?>" value="<?php echo $model['dpid']; ?>"><label for="<?php echo $model['dpid']; ?>"><?php echo $model['company_name']; ?></label>
								</div>
								<?php endforeach; ?>
							    <div class="col-md-offset-3 col-md-9" style="margin-top:10px;">
									<?php echo CHtml::submitButton('确定',array('class' => 'btn blue')); ?>
							    	<a href="<?php echo $this->createUrl('priceGroup/index' , array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
							    </div>
								<?php else: ?>
									<h3 style="color:white;margin:0;border:1px solid gray;border-radius:3px;background: #abc;">请添加 <?php if($type==1): echo '店铺';elseif($type==2): echo '仓库';endif; ?></h3>
								<?php endif; ?>
								
							<?php $this->endWidget(); ?>

							<!-- END FORM--> 
						</div>

				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  