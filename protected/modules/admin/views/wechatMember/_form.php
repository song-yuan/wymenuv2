    <?php $form=$this->beginWidget('CActiveForm', array(
                    'id' => 'CradImg-form',
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'
                    ),
    )); ?>
            <div class="form-body">
                    <div class="form-group">
                            <?php echo $form->labelEx($model, 'grade',array('class' => 'col-md-3 control-label'));?>
                            <div class="col-md-4">
                                    <?php echo $form->textField($model, 'grade',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('grade')));?>
                                    <?php echo $form->error($model, 'grade' )?>
                            </div>
                    </div>
                    <div class="form-group <?php if($model->hasErrors('img_path')) echo 'has-error';?>">
                            <?php echo $form->labelEx($model,'img_path',array('class'=>'control-label col-md-3')); ?>
                            <div class="col-md-9">
                            <?php
                            $this->widget('application.extensions.swfupload.SWFUpload',array(
                                    'callbackJS'=>'swfupload_callback',
                                    'fileTypes'=> '*.jpg',
                                    'buttonText'=> yii::t('app','上传图片'),
                                    'imgUrlList' => array($model->img_path),
                            ));
                            ?>
                           <?php echo $form->hiddenField($model,'img_path'); ?>
                           <?php echo $form->error($model,'img_path'); ?>
                            </div>
                    </div>
                    <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                                    <a href="<?php echo $this->createUrl('wechatMember/styleIndex', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                            </div>
                    </div>
            </div>
    <?php $this->endWidget(); ?>

	<script>
		function swfupload_callback(name,path,oldname)  {
			$("#CardImg_img_path").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		
	</script>							