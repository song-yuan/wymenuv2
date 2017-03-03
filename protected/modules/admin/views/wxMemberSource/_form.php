    <?php $form=$this->beginWidget('CActiveForm', array(
                    'id' => 'WxMemberSource-form',
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'
                    ),
    )); ?>
            <div class="form-body">
                    <div class="form-group">
                            <?php echo $form->labelEx($model, 'channel_name',array('class' => 'col-md-3 control-label'));?>
                            <div class="col-md-4">
                                    <?php echo $form->textField($model, 'channel_name',array('class' => 'form-control'));?>
                                    <?php echo $form->error($model, 'channel_name' )?>
                            </div>
                    </div>
                    <div class="form-group">
                            <?php echo $form->labelEx($model, 'channel_comment',array('class' => 'col-md-3 control-label'));?>
                            <div class="col-md-4">
                                    <?php echo $form->textArea($model, 'channel_comment',array('class' => 'form-control','rows'=>'4','cols'=>'50'));?>
                                    <?php echo $form->error($model, 'channel_comment' )?>
                            </div>
                    </div>
                    <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                                    <a href="<?php echo $this->createUrl('WxMemberSource/index', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                            </div>
                    </div>
            </div>
    <?php $this->endWidget(); ?>

	<script>
		
		
	</script>							