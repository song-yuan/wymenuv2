<style>
.form-horizontal .radio{
     padding-top: 0px!important;
   
}
.form-group .col-md-4{
    padding-top: 7px!important;
}
</style>

<?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'MemberWxcardStyle-form',
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                'class' => 'form-horizontal',
                'enctype' => 'multipart/form-data'
                ),
)); ?>
<div class="form-body">

        <div class="form-group <?php if($model->hasErrors('bg_img')) echo 'has-error';?>">
                <?php echo $form->labelEx($model,'bg_img',array('class'=>'control-label col-md-3')); ?>
                <div class="col-md-9">
                <?php
                $this->widget('application.extensions.swfupload.SWFUpload',array(
                        'callbackJS'=>'swfupload_callback',
                        'fileTypes'=> '*.jpg',
                        'buttonText'=> yii::t('app','上传图片'),
                        'imgUrlList' => array($model->bg_img),
                ));
                ?>
               <?php echo $form->hiddenField($model,'bg_img'); ?>
               <?php echo $form->error($model,'bg_img'); ?>
                   <div> 图片大小建议：宽300像素，高180像素</div>  
                </div>
               
        </div>
        
                    
               
<!--        <div class="form-group">
                <?php //echo $form->labelEx($model, 'style_cardnum_style',array('class' => 'col-md-3 control-label'));?>
                <div class="col-md-4">
                        <?php //echo $form->radioButtonList($model, 'style_cardnum_style',$style_cardnum_style,array('separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;','class' => 'form-control'));?>
                        <?php //echo $form->error($model, 'style_cardnum_style' )?>
                </div>
        </div>-->
        <div class="form-actions fluid">
                <div class="col-md-offset-3 col-md-9">
                        <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                        <a href="<?php echo $this->createUrl('wxCardStyle/index', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                </div>
        </div>
</div>
    <?php $this->endWidget(); ?>

<script>
        function swfupload_callback(name,path,oldname)  {
                $("#MemberWxcardStyle_bg_img").val(name);
                $("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
        }

</script>							