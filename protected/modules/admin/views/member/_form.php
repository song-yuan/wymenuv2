<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_002.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/mobiscroll_003.css');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_002.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_004.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_003.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/mobiscroll_005.js');?>      
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
	<?php if($model->getIsNewRecord()):?>
			<div class="form-group">
				<?php echo $form->label($model, 'rfid',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'rfid',array('class' => 'form-control','placeholder'=>'请使用读卡器刷卡'));?>
					<?php echo $form->error($model, 'rfid' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'selfcode',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'selfcode',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('selfcode')));?>
					<?php echo $form->error($model, 'selfcode' )?>
				</div>
			</div>
		<?php else:?>
			<div class="form-group">
				<?php echo $form->label($model, 'rfid',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'rfid',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('rfid'),'disabled'=>'disabled'));?>
					<?php echo $form->error($model, 'rfid' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'selfcode',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'selfcode',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('selfcode')));?>
					<?php echo $form->error($model, 'selfcode' )?>
				</div>
			</div>
		<?php endif;?>
			<div class="form-group" <?php if($model->hasErrors('level_id')) echo 'has-error';?>>
				<?php echo $form->label($model, 'level_id',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->dropDownList($model, 'level_id',array('0' => yii::t('app','-- 请选择 --')) +Helper::getCardLevels() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('level_id')));?>
					<?php echo $form->error($model, 'level_id' )?>
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
				<?php echo $form->label($model, 'birthday',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'birthday',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('birthday')));?>
					<?php echo $form->error($model, 'birthday' )?>
					<span style="color: red;">会员生日的格式必须填写为xx.xx形式，否则无法生效。</span>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'enable_date',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'enable_date',array('class' => 'form-control ui_timepicker','readonly'=>'true', 'placeholder'=>$model->getAttributeLabel('enable_date')));?>
					<?php echo $form->error($model, 'enable_date' )?>
					
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
					<select class="form-control" name="MemberCard[sex]">
						<option value="m" <?php if($model->sex=='m') echo 'selected';?>>男</option>
						<option value="f" <?php if($model->sex=='f') echo 'selected';?>>女</option>
					</select>
					<?php echo $form->error($model, 'sex' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, 'ages',array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-2">
					<select class="form-control" name="MemberCard[ages]">
						<option value="<18" <?php if($model->sex=='<18') echo 'selected';?>> <18 </option>
						<option value="18-25" <?php if($model->sex=='18-25') echo 'selected';?>>18-25</option>
						<option value="25-30" <?php if($model->sex=='25-30') echo 'selected';?>>25-30</option>
						<option value="30-35" <?php if($model->sex=='30-35') echo 'selected';?>>30-35</option>
						<option value="35-40" <?php if($model->sex=='35-40') echo 'selected';?>>35-40</option>
						<option value="40-45" <?php if($model->sex=='40-45') echo 'selected';?>>40-45</option>
						<option value="45-50" <?php if($model->sex=='45-50') echo 'selected';?>>45-50</option>
						<option value="50-55" <?php if($model->sex=='50-55') echo 'selected';?>>50-55</option>
						<option value="55-60" <?php if($model->sex=='55-60') echo 'selected';?>>55-60</option>
						<option value=">60" <?php if($model->sex=='>60') echo 'selected';?>> >60 </option>
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
					<!-- <a href="<?php echo $this->createUrl('member/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>   -->                            
				</div>
			</div>
	<script type="text/javascript">
	function check(){
                var memberLevel  =$("#MemberCard_level_id").val();
                if(memberLevel==false){
                    alert("请选择会员等级。");
                    return false;
                
                }
		var password1 = $('input[name="MemberCard[password_hash]"]').val();
		var password2 = $('input[name="MemberCard[password_hash1]"]').val();
		if(password1 != password2){
			alert('两次输入的密码不一致!');
			return false;
		}
		return true;
	}
	jQuery(document).ready(function() {
                 var currYear = (new Date()).getFullYear();
                var opt = {};
                opt.date = {
                    preset : 'birthday'
                };
                opt.datetime = {
                    preset : 'birthday'
                };
                opt.time = {
                    preset : 'birthday'
                };
                opt.default = {
                theme : 'android-ics light', //皮肤样式
                display : 'modal', //显示方式
                mode : 'scroller', //日期选择模式
                dateFormat : 'mm.dd',
                //width : cHeight / 1.2,
                //height : cHeight / 1.6,
                width:100,
                height:40,
                circular:true,
                showScrollArrows:true,
                lang : 'zh',
                showNow : true,
                nowText : "今天",
                startYear : currYear , //开始年份
                endYear : currYear  //结束年份
            };

                var optDateTime = $.extend(opt['datetime'], opt['default']);
                // var optTime = $.extend(opt['time'], opt['default']);
                $("#MemberCard_birthday").mobiscroll(optDateTime).date(optDateTime);

                
                $(".ui_timepicker").datetimepicker({
                         //showOn: "button",
                         //buttonImage: "./css/images/icon_calendar.gif",
                         //buttonImageOnly: true,
                         showSecond: true,
                         timeFormat: 'hh:mm:ss',
                         stepHour: 1,
                         stepMinute: 1,
                         stepSecond: 1
                 });
                $('input[type="radio"]').change(function(){
                       if($(this).val()==0){
                              $('.password').hide();
                       }else{
                              $('.password').show();
                       }
                });
                $('form input').keypress(function(e){
                     if(e.keyCode==13){
                         e.preventDefault();
                     }
                });
	});
	</script>
	<?php $this->endWidget(); ?>