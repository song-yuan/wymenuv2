	<style>
	.none {
		display:none;
	}
	</style>
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'taste-form',
			'errorMessageCssClass' => 'help-block',
			'htmlOptions' => array(
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data',
				'onsubmit'=>'return check()',
			),
	)); ?>
		<div class="form-body">
		<div class="form-group">
				<?php echo $form->label($model, 'selfcode',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'selfcode',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('selfcode')));?>
					<?php echo $form->error($model, 'selfcode' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'name',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'name',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('name')));?>
					<?php echo $form->error($model, 'name' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'mobile',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'mobile',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('mobile')));?>
					<?php echo $form->error($model, 'mobile' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'email',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'email',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('email')));?>
					<?php echo $form->error($model, 'email' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'sex',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-2">
					<select name="MemberCard[sex]">
						<opation value="m" <?php if($model->sex=='m') echo 'selected';?>>男</opation>
						<opation value="f" <?php if($model->sex=='f') echo 'selected';?>>女</opation>
					</select>
					<?php echo $form->error($model, 'sex' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'ages',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-2">
					<select name="MemberCard[ages]">
						<opation value="<18" <?php if($model->sex=='<18') echo 'selected';?>> <18 </opation>
						<opation value="18-25" <?php if($model->sex=='18-25') echo 'selected';?>>18-25</opation>
						<opation value="25-30" <?php if($model->sex=='25-30') echo 'selected';?>>25-30</opation>
						<opation value="30-35" <?php if($model->sex=='30-35') echo 'selected';?>>30-35</opation>
						<opation value="35-40" <?php if($model->sex=='35-40') echo 'selected';?>>35-40</opation>
						<opation value="40-45" <?php if($model->sex=='40-45') echo 'selected';?>>40-45</opation>
						<opation value="45-50" <?php if($model->sex=='45-50') echo 'selected';?>>45-50</opation>
						<opation value="50-55" <?php if($model->sex=='50-55') echo 'selected';?>>50-55</opation>
						<opation value="55-60" <?php if($model->sex=='55-60') echo 'selected';?>>55-60</opation>
						<opation value=">60" <?php if($model->sex=='>60') echo 'selected';?>> >60 </opation>
					</select>
					<?php echo $form->error($model, 'ages' )?>
				</div>
			</div>
			<div class="form-group">
				<label class="col-md-3 control-label">密码</label>
				<div class="col-md-4">
					<div class="radio-list">
						<label class="radio-inline">
						<input type="radio" name="MemberCard[haspassword]" value="0" <?php if(!$model->haspassword) echo 'checked';?>> 无
						</label>
						<label class="radio-inline">
						<input type="radio" name="MemberCard[haspassword]" value="1" <?php if($model->haspassword) echo 'checked';?>> 有
						</label>
					</div>
				</div>
			</div>
			<div class="password <?php if(!$model->haspassword) echo 'none';?>">
			<div class="form-group">
				<?php echo $form->label($model, 'password_hash',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->passwordField($model, 'password_hash',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('password_hash')));?>
					<?php echo $form->error($model, 'password_hash' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'password_hash1',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->passwordField($model, 'password_hash1',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('password_hash1')));?>
					<?php echo $form->error($model, 'password_hash1' )?>
				</div>
			</div>
			</div>
			<div class="form-actions fluid">
				<div class="col-md-offset-3 col-md-9">
					<button type="submit" class="btn blue"><?php echo yii::t('app','确定');?></button>
					<a href="<?php echo $this->createUrl('member/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>                              
				</div>
			</div>
	<script type="text/javascript">
	function check(){
		var password1 = $('input[name="MemberCard[password_hash]"]').val();
		var password2 = $('input[name="MemberCard[password_hash1]"]').val();
		if(password1 != password2){
			alert('两次输入的密码不一致!');
			return false;
		}
		return true;
	}
	jQuery(document).ready(function() {
		   $('input[type="radio"]').change(function(){
		   	  if($(this).val()==0){
		   	  	 $('.password').hide();
		   	  }else{
		   	  	 $('.password').show();
		   	  }
		   });
		});
	</script>
	<?php $this->endWidget(); ?>