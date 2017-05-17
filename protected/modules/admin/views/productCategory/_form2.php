			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'productCategory-form',
				'action'=>$action,
				'enableAjaxValidation'=>false,
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
				<?php if($model->pid==0):?>
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
				<?php endif;?>
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
                <div class="form-group">
                     <?php echo $form->label($model, 'type',array('class' => 'col-md-3 control-label'));?>
                     <div class="col-md-4">
                          <?php echo $form->dropDownList($model, 'type', array('0' => yii::t('app','是') , '1' => yii::t('app','否')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
                          <?php echo $form->error($model, 'type' )?>
                     </div>
                </div>
                <?php if($model->pid==0):?>
                <div class="form-group">
                     <?php echo $form->label($model, 'cate_type',array('class' => 'col-md-3 control-label'));?>
                     <div class="col-md-4">
                          <?php echo $form->dropDownList($model, 'cate_type', array('0' => yii::t('app','单一类别') , '1' => yii::t('app','公共类别'), '2' => yii::t('app','套餐类别')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('cate_type')));?>
                          <?php echo $form->error($model, 'cate_type' )?>
                     </div>
                </div>
                <?php endif;?>
          		<div class="form-group">
                     <?php echo $form->label($model, 'show_type',array('class' => 'col-md-3 control-label'));?>
                     <div class="col-md-4">
                          <?php echo $form->dropDownList($model, 'show_type', array('1' => yii::t('app','都显示') , '2' => yii::t('app','微信外卖不显示'), '3' => yii::t('app','微信堂食不显示'), '4' => yii::t('app','微信端都不显示')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('show_type')));?>
                          <?php echo $form->error($model, 'show_type' )?>
                     </div>
                </div>
	</div>
			<div class="modal-footer">
				<button type="button" data-dismiss="modal" class="btn default"><?php echo yii::t('app','取 消');?></button>
				<input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
			</div>
			<?php $this->endWidget(); ?>
               
			<script>
			function swfupload_callback(name,path,oldname)  {
				$("#ProductCategory_main_picture").val(name);
				$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
			}
			</script>