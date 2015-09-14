			<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/js/product/jquery.form.js');?>
			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'GoodsCategory',
				'action'=>$action,
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title"><?php echo yii::t('app','添加商品类目');?></h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<?php echo $form->label($model,'main_picture',array('class'=>'col-md-3 control-label')); ?>
					<div class="col-md-9">
						<?php
						$this->widget('application.extensions.swfupload.SWFUpload',array(
							'callbackJS'=>'swfupload_callback',
							'fileTypes'=> '*.jpg',
							'buttonText'=> yii::t('app','上传类别图片'),
							'companyId' => $model->dpid,
							'imgUrlList' => array($model->main_picture),
						));
						?>
						<?php echo $form->hiddenField($model,'main_picture'); ?>
						<?php echo $form->error($model,'main_picture'); ?>
					</div>
				</div>
				<div class="form-group">
					<?php echo $form->label($model,'category_name',array('class'=>'col-md-3 control-label')); ?>
					<div class="col-md-9">
						<?php echo $form->hiddenField($model,'pid'); ?>
						<?php echo $form->textField($model,'category_name',array('class'=>'form-control','placeholder'=>$model->getAttributeLabel('category_name'))); ?>
						<?php echo $form->error($model,'category_name',array('class'=>'errorMessage')); ?>
					</div>
				</div>
                                <div class="form-group">
                                        <?php echo $form->label($model, 'order_num',array('class' => 'col-md-3 control-label'));?>
                                        <div class="col-md-4">
                                                <?php echo $form->textField($model, 'order_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('order_num')));?>
                                                <?php echo $form->error($model, 'order_num' )?>
                                        </div>
                                </div>
			</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
				<input type="button" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
			</div>
			<?php $this->endWidget(); ?>
			<script>
			$(document).ready(function(){
				$('#create_btn').click(function(){
					$('#GoodsCategory').ajaxSubmit(function(msg){
						if(parseInt(msg)){
							alert(msg);
						}
					});
				});
			});
			function swfupload_callback(name,path,oldname)  {
				$("#ProductCategory_main_picture").val(name);
				$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
			}
			</script>