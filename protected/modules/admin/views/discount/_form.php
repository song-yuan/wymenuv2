							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'discount-form',
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
														
									<div class="form-group ">
									<?php if($model->hasErrors('discount_name')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','折扣模板名称'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'discount_name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('discount_name')));?>
											<?php echo $form->error($model, 'discount_name' )?>
										</div>
									</div><!-- 活动标题 -->
						
									<div class="form-group" >
									<?php if($model->hasErrors('discount_abstract')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'discount_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('discount_abstract')));?>
											<?php echo $form->error($model, 'discount_abstract' )?>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group" >
									<?php if($model->hasErrors('discount_num')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','折扣比例'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'discount_num',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('discount_num')));?>
											<?php echo $form->error($model, 'discount_num' )?>
											<span style="color: red;">例：88折（或8.8折）在此处填写为0.88</span>
										</div>
									</div><!-- 活动摘要 -->
									<div class="form-group">
									<?php if($model->hasErrors('discount_type')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','设置折扣类型'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'discount_type', array( '0' => yii::t('app','只针对可以折扣的菜品'), '1' => yii::t('app','针对所有菜品')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('discount_type')));?>
											<?php echo $form->error($model, 'discount_type' )?>
										</div>
									</div>
									<div class="form-group">
									<?php if($model->hasErrors('is_available')) echo 'has-error';?>
										<?php echo $form->label($model, yii::t('app','设置折扣是否生效'),array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'is_available', array( '0' => yii::t('app','生效'), '1' => yii::t('app','无效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
											<?php echo $form->error($model, 'is_available' )?>
										</div>
									</div>
                                    
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
											<a href="<?php echo $this->createUrl('discount/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
										</div>
									</div>
							<?php $this->endWidget(); ?>
<script>
$("#su").on('click',function() {
    //alert(11);
    var dsc = $('#Discount_discount_num').val();
    //var begintime = $('#NormalPromotion_begin_time').val();
    //var endtime = $('#NormalPromotion_end_time').val();
    //var aa = document.getElementsByName("chk");
    //var str=new Array();
    //alert(begintime);
    //alert(endtime);
    //var ss = "";
  // if(aa.checked){
    if(dsc>'1'||dsc<'0'){
   	 alert("<?php echo yii::t('app','折扣数值应该在0~1之间，例如：88折应填写为0.88');?>");
   	 return false;
    }
    //if(p1=='2'){
    //for (var i = 0; i < aa.length; i++) {
     //   if (aa[i].checked) {
       //     str += aa[i].value +',';
        //}
    //}
    //if(str!=''){
    //str = str.substr(0,str.length-1);//除去最后一个“，”
    //}else{
   	 //alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
   	 //return false;
   	 //}
    //}
    //else{
   //	 alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
     //   }
   // alert(str);
 //  }else{
   //alert(str);}
    //$("#hidden1").val(str);
    $("#discount-form").submit();
});

</script>			
							
