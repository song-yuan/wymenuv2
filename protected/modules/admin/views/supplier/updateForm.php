<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'supplier-form',
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ),
)); ?>
<style>
    #category_container select {display:block;float:left;margin-right:3px;max-width:200px;overflow:hidden;}
</style>

<div class="form-body">
    <div class="form-group <?php if($model->hasErrors('classification_id')) echo 'has-error';?>">
        <?php echo $form->label($model, '供应商类别',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->dropDownList($model, 'classification_id', array('0' => yii::t('app','——请选择——')) +Helper::genMfrClass() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('classification_id')));?>
            <?php echo $form->error($model, 'classification_id' )?>
        </div>
    </div>
    <div class="form-group <?php if($model->hasErrors('manufacturer_code')) echo 'has-error';?>">
        <?php echo $form->label($model, '供应商编号',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'manufacturer_code',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('供应商编号')));?>
            <?php echo $form->error($model, 'manufacturer_code' )?>
        </div>
    </div>
    <div class="form-group <?php if($model->hasErrors('manufacturer_name')) echo 'has-error';?>">
        <?php echo $form->label($model, '供应商名称',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'manufacturer_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('供应商名称')));?>
            <?php echo $form->error($model, 'manufacturer_name' )?>
        </div>
    </div>
    <div class="form-group <?php if($model->hasErrors('post_code')) echo 'has-error';?>">
        <?php echo $form->label($model, 'post_code',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'post_code',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('post_code')));?>
            <?php echo $form->error($model, 'post_code' )?>
        </div>
    </div>
    <div class="form-group <?php if($model->hasErrors('address')) echo 'has-error';?>">
        <?php echo $form->label($model, 'address',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'address', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('address')));?>
            <?php echo $form->error($model, 'address' )?>
        </div>
    </div>
    <div class="form-group <?php if($model->hasErrors('contact_name')) echo 'has-error';?>">
        <?php echo $form->label($model, 'contact_name',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'contact_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('contact_name')));?>
            <?php echo $form->error($model, 'contact_name' )?>
        </div>
    </div>
    <div class="form-group <?php if($model->hasErrors('contact_tel')) echo 'has-error';?>">
        <?php echo $form->label($model, 'contact_tel',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'contact_tel', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('contact_tel')));?>
            <?php echo $form->error($model, 'contact_tel' )?>
        </div>
    </div>
    <div class="form-group" <?php if($model->hasErrors('contact_fax')) echo 'has-error';?>>
        <?php echo $form->label($model, 'contact_fax',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'contact_fax', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('contact_fax')));?>
            <?php echo $form->error($model, 'contact_fax' )?>
        </div>
    </div>

    <div class="form-group" <?php if($model->hasErrors('email')) echo 'has-error';?>>
        <?php echo $form->label($model, 'email',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'email',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('email')));?>
            <?php echo $form->error($model, 'email' )?>
        </div>
    </div>
    <div class="form-group <?php if($model->hasErrors('bank')) echo 'has-error';?>">
        <?php echo $form->label($model, 'bank',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'bank',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('bank')));?>
            <?php echo $form->error($model, 'bank' )?>
        </div>
    </div>
    <div class="form-group" <?php if($model->hasErrors('bank_account')) echo 'has-error';?>>
        <?php echo $form->label($model, 'bank_account',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'bank_account',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('bank_account')));?>
            <?php echo $form->error($model, 'bank_account' )?>
        </div>
    </div>
    <div class="form-group" <?php if($model->hasErrors('tax_account')) echo 'has-error';?>>
        <?php echo $form->label($model, 'tax_account',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-4">
            <?php echo $form->textField($model, 'tax_account',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('tax_account')));?>
            <?php echo $form->error($model, 'tax_account' )?>
        </div>
    </div>
    <div class="form-group" <?php if($model->hasErrors('remark')) echo 'has-error';?>>
        <?php echo $form->label($model, 'remark',array('class' => 'col-md-3 control-label'));?>
        <div class="col-md-5">
            <?php echo $form->textArea($model, 'remark', array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('remark')));?>
            <?php echo $form->error($model, 'remark' )?>
        </div>
    </div>
    <div class="form-actions fluid">
        <div class="col-md-offset-3 col-md-9">
            <button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
            <a href="<?php echo $this->createUrl('Supplier/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
        </div>
    </div>
    <?php $this->endWidget(); ?>
    <?php $this->widget('ext.kindeditor.KindEditorWidget',array(
        'id'=>'',	//Textarea id
        'language'=>'zh_CN',
        // Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
        'items' => array(
            'height'=>'200px',
            'width'=>'100%',
            'themeType'=>'simple',
            'resizeType'=>1,
            'allowImageUpload'=>true,
            'allowFileManager'=>true,
        ),
    )); ?>

    <script>
        $('#category_container').on('change','.category_selecter',function(){
            var id = $(this).val();
            var $parent = $(this).parent();
            var sid ='0000000000';
            var len=$('.category_selecter').eq(1).length;
            if(len > 0)
            {
                sid=$('.category_selecter').eq(1).val();
                //alert(sid);
            }
        });
    </script>