<style>
.form-horizontal .radio{
     padding-top: 0px!important;
   
}
.form-group .col-md-4{
    padding-top: 7px!important;
}
</style>    

<?php $form=$this->beginWidget('CActiveForm', array(
                    'id' => 'WeixinMessagetpl-form',
                    'errorMessageCssClass' => 'help-block',
                    'htmlOptions' => array(
                    'class' => 'form-horizontal',
                    'enctype' => 'multipart/form-data'
                    ),
)); ?>
            <div class="form-body">
                <div class="form-group">
                    <?php echo $form->labelEx($model, 'message_type',array('class' => 'col-md-3 control-label'));?>
                    <div class="col-md-4">
                            <?php echo $form->radioButtonList($model, 'message_type',$message_type,array('separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','class' => 'form-control'));?>
                            <?php echo $form->error($model, 'message_type' )?>
                    </div>
                </div>
                    <div class="form-group">
                            <?php echo $form->labelEx($model, 'message_tpl_id',array('class' => 'col-md-3 control-label'));?>
                            <div class="col-md-4">
                                    <?php echo $form->textArea($model, 'message_tpl_id',array('class' => 'form-control','rows'=>'4','cols'=>'50'));?>
                                    <?php echo $form->error($model, 'message_tpl_id' )?>
                            </div>
                    </div>
         
                    <div class="form-actions fluid">
                            <div class="col-md-offset-3 col-md-9">
                                    <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                                    <a href="<?php echo $this->createUrl('wxMessage/index', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                            </div>
                    </div>
            </div>
    <?php $this->endWidget(); ?>
							