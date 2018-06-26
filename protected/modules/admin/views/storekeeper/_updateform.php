<?php $form=$this->beginWidget('CActiveForm', array(
    'id' => 'user-form',
    'errorMessageCssClass' => 'help-block',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
        'enctype' => 'multipart/form-data'
    ),
)); ?>


<div class="form-group <?php if($model->hasErrors('username')) echo 'has-error';?>">
    <?php echo $form->label($model, 'username',array('class' => 'col-md-3 control-label'));?>
    <div class="col-md-4">
        <?php echo $form->textField($model, 'username',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('username'),'disabled'=>'true'));?>
        <?php echo $form->error($model, 'username' )?>
    </div>
</div>
<div class="form-group <?php if($model->hasErrors('mobile')) echo 'has-error';?>">
    <?php echo $form->label($model, 'mobile',array('class' => 'col-md-3 control-label'));?>
    <div class="col-md-4">
        <?php echo $form->textField($model, 'mobile',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mobile')));?>
        <?php echo $form->error($model, 'mobile' )?>
    </div>
</div>
<div class="form-group <?php if($model->hasErrors('password')) echo 'has-error';?>">
    <?php echo $form->label($model, 'password',array('class' => 'col-md-3 control-label'));?>
    <div class="col-md-4">
											<span id='content' value=>
												<input id="btcontent" type="button" value="点击修改密码" onclick='' class='form-control' style='background-color:rgb(0,238,283)'/>
											</span>
        <?php echo $form->error($model, 'password' )?>
        <input type="hidden" id="hidden1" name="hidden1" value="" />
    </div>
</div>
<div class="form-group <?php if($model->hasErrors('staff_no')) echo 'has-error';?>">
    <?php echo $form->label($model, 'staff_no',array('class' => 'col-md-3 control-label'));?>
    <div class="col-md-4">
        <?php echo $form->textField($model, 'staff_no',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('staff_no')));?>
        <?php echo $form->error($model, 'staff_no' )?>
    </div>
</div>
<div class="form-group <?php if($model->hasErrors('email')) echo 'has-error';?>">
    <?php echo $form->label($model, 'email',array('class' => 'col-md-3 control-label'));?>
    <div class="col-md-4">
        <?php echo $form->textField($model, 'email',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('email')));?>
        <?php echo $form->error($model, 'email' )?>
    </div>
</div>

<div class="form-actions fluid">
    <div class="col-md-offset-3 col-md-9">
        <button type="button" id="su"  class="btn blue"><?php echo yii::t('app','确定');?></button>
        <a href="<?php echo $this->createUrl('storekeeper/index',array('companyId'=>$this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
    </div>
</div>
<?php $this->endWidget(); ?>

<script type="text/javascript">
    $("#su").on('click',function() {

        var pw = $('#contentpw').val();
        if(pw){
            $("#hidden1").val(pw);
            //alert(pw);
        }

        $("#user-form").submit();
    });
    $("#btcontent").on('click',function() {
        if(window.confirm("确认需要修改密码?")){
            changeContent();
        }

    });
    function changeContent(){
        //alert(11);
        var o = document.getElementById("content");
        var c = o.innerHTML;
        o.innerHTML = "<input id='contentpw' type='password' type='text'  class='form-control' value=''/>"
        document.getElementById("contentpw").focus();
    }
</script>