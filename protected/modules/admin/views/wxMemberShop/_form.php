<?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'WxMemberShop-form',
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data'
                ),
)); ?>
        <div class="form-body">
                
                <div class="form-group  <?php if($model->hasErrors('goods_img')) echo 'has-error';?>">
                        <?php echo $form->label($model,'goods_img',array('class'=>'control-label col-md-3')); ?>
                        <div class="col-md-9">
                        <?php
                        $this->widget('application.extensions.swfupload.SWFUpload',array(
                                'callbackJS'=>'swfupload_callback',
                                'fileTypes'=> '*.jpg',
                                'buttonText'=> yii::t('app','上传图片'),
                                'imgUrlList' => array($model->goods_img),
                        ));
                        ?>
                        <?php echo $form->hiddenField($model,'goods_img'); ?>
                        <?php echo $form->error($model,'goods_img'); ?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'price',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'price',array('class' => 'form-control'));?>
                                <?php echo $form->error($model, 'price' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'goods_name',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4">
                                <?php echo $form->textField($model, 'goods_name',array('class' => 'form-control'));?>
                                <?php echo $form->error($model, 'goods_name' )?>
                        </div>
                </div>
                <div class="form-group">
                        <?php echo $form->label($model, 'goods_category',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4 item_list">
                                <?php echo $form->radioButtonList($model,'goods_category',$goods_category,array('class' => 'form-control','separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;') );?>
                                <?php echo $form->error($model, 'goods_category' )?>
                        </div>
                </div>
               <div class="form-group">
                        <?php echo $form->label($model, 'state',array('class' => 'col-md-3 control-label'));?>
                        <div class="col-md-4 item_list">
                                <?php echo $form->radioButtonList($model, 'state',$state,array('class' => 'form-control','separator'=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'));?>
                                <?php echo $form->error($model, 'state' )?>
                        </div>
                </div>
          							
                <div class="form-actions fluid">
                        <div class="col-md-offset-3 col-md-9">
                                <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
                                <a href="<?php echo $this->createUrl('wxMemberShop/index', array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
                        </div>
                </div>
<?php $this->endWidget(); ?>
<script>
    function swfupload_callback(name,path,oldname)  {
			$("#WxMemberShop_goods_img").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		
</script>							