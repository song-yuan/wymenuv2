	<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'cupon-form',
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
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','代金券面值'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'cupon_money',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('cupon_money')));?>
					<?php echo $form->error($model, 'cupon_money' )?>
				</div>
			</div><!-- 活动类型 -->
			<div class="form-group ">
			<?php if($model->hasErrors('cupon_title')) echo 'has-error';?>
				<?php echo $form->label($model, yii::t('app','代金券名称'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'cupon_title',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('cupon_title')));?>
					<?php echo $form->error($model, 'cupon_title' )?>
				</div>
			</div><!-- 活动标题 -->


			<div class="form-group" >
			<?php if($model->hasErrors('cupon_abstract')) echo 'has-error';?>
				<?php echo $form->label($model, yii::t('app','摘要'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'cupon_abstract',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('cupon_abstract')));?>
					<?php echo $form->error($model, 'cupon_abstract' )?>
				</div>
			</div><!-- 活动摘要 -->

			 <div class="form-group">
				<?php echo $form->label($model, yii::t('app','使用该代金券的最低消费'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'min_consumer',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('min_consumer')));?>
					<?php echo $form->error($model, 'min_consumer' )?>
				</div>
			</div><!-- 是否可用代金券 -->
			<div class="form-group" >
			<?php if($model->hasErrors('change_point')) echo 'has-error';?>
				<?php echo $form->label($model, yii::t('app','兑换该代金券所需的积分'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->textField($model, 'change_point',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('change_point')));?>
					<?php echo $form->error($model, 'change_point' )?>
				</div>
			</div><!-- 需要的积分 -->
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','活动针对对象'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->dropDownList($model, 'to_group', array( '0' => yii::t('app','所有人'), '2' => yii::t('app','会员等级')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('to_group')));?>
					<?php echo $form->error($model, 'to_group' )?>
				</div>
			</div>
            <!-- <div class="form-group">
				<?php echo $form->label($model, yii::t('app','活动针对对象'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->dropDownList($model, 'to_group', array('1' => yii::t('app','关注微信的人群') , '2' => yii::t('app','会员等级') ,'3' => yii::t('app','会员个人')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('to_group')));?>
					<?php echo $form->error($model, 'to_group' )?>
				</div>
			</div><!-- 活动实施对象 --
			 -->
			<?php if($model->to_group=="2"):{?>
			<div id="yincang" style="" class="form-group ">
				<label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
				<div class="col-md-4" style="border:1px solid red;">
				<?php if($brdulvs) :{?>
				<?php $i=1;?>
				<?php foreach ($brdulvs as $brdulv):?>

					<tr class="odd gradeX">
						<td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes" <?php if(!empty($userlvs)){foreach ($userlvs as $userlv){if($userlv['brand_user_lid'] == $brdulv->lid) echo 'checked' ;}}else echo "123";?> value="<?php echo $brdulv->lid;?>" name="chk" /></td>
						<td><?php echo $i,$brdulv->level_name; ?></td>

					</tr>
				<?php $i=$i+1;?>
				<?php endforeach;?>
				<?php }endif;?>
				</div>
				<input type="hidden" id="hidden1" name="hidden1" value="" />
			</div>
		<?php }elseif($model->to_group!="2"):{?>
				<div id="yincang" style="display:none ;" class="form-group ">
				<label class="col-md-3 control-label"><?php echo yii::t('app','会员等级');?></label>
				<div class="col-md-4" style="border:1px solid red;">
				<?php if($brdulvs) :{?>
				<?php $i=1;?>
				<?php foreach ($brdulvs as $brdulv):?>

					<tr class="odd gradeX">
						<td><input type="checkbox" id="<?php echo $i;?>" class="checkboxes" value="<?php echo $brdulv->lid;?>" name="chk" /></td>
						<td><?php echo $i,$brdulv->level_name; ?></td>

					</tr>
				<?php $i=$i+1;?>
				<?php endforeach;?>
				<?php }endif;?>
				</div>
				<input type="hidden" id="hidden1" name="hidden1" value="" />
			</div>
		<?php }endif;?>
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','可用对象'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->checkBoxList($model, 'type', array( '1' => yii::t('app','POS机端'), '2' => yii::t('app','微信端堂食'), '3' => yii::t('app','微信端外卖')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('type')));?>
					<?php echo $form->error($model, 'type' )?>
				</div>
			</div>
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','是否生效'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->dropDownList($model, 'is_available', array('0' => yii::t('app','生效') , '1' => yii::t('app','不生效')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_available')));?>
					<?php echo $form->error($model, 'is_available' )?>
				</div>
			</div><!-- 活动是否生效 -->
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','限定日期形式'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-4">
					<?php echo $form->dropDownList($model, 'time_type', array( '1' => yii::t('app','固定日期'), '2' => yii::t('app','有效期')) , array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('time_type')));?>
					<?php echo $form->error($model, 'time_type' )?>
				</div>
			</div>
            <div class="form-group timetype1">
					<label class="control-label col-md-3"><?php echo yii::t('app','活动有效期限');?></label>
					<div class="col-md-4">
						 <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
							 <?php echo $form->textField($model,'begin_time',array('class' => 'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('begin_time'))); ?>
							 <span class="input-group-addon"> ~ </span>
							 <?php echo $form->textField($model,'end_time',array('class'=>'form-control ui_timepicker','style'=>'width:160px;','placeholder'=>$model->getAttributeLabel('end_time'))); ?>
						</div>
						<!-- /input-group -->
						<?php echo $form->error($model,'begin_time'); ?>
						<?php echo $form->error($model,'end_time'); ?>
					</div>
				</div>
				<div style="display: none;" class="form-group timetype2">
				  	<LABEL class="col-md-3 control-label">有效天数</LABEL>
				  	<div class="col-md-4">
				    	<div class="row ">
					   		<div class="col-md-4 select left">
							   <select class="form-control" id="begin_day" >
									<option value="0">当天</option>
									<?php for($i=1;$i<31;$i++):?>
									<option value="<?php echo $i;?>"><?php echo $i;?>天</option>
									<?php endfor;?>
								</select>
							</div>
							<div class="col-md-4 select middle">&nbsp;生效,有效天数 </div>
							<div class="col-md-4 select left">
								<select class="form-control" id="day" >
									<?php for($i=3;$i<361;$i+=3):?>
									<option value="<?php echo $i;?>" <?php if($i==60) echo 'selected';?>><?php echo $i;?>天</option>
									<?php endfor;?>
								</select>
							</div>
						</div>
				  	</div>
				</div>
				<input type="hidden" id="cupon_begin_day" name="cupon_begin_day" value="" />
				<input type="hidden" id="cupon_day" name="cupon_day" value="" />
			<div class="form-group">
				<?php echo $form->label($model, yii::t('app','使用说明'),array('class' => 'col-md-3 control-label'));?>
				<div class="col-md-8">
					<?php echo $form->textArea($model, 'cupon_memo' , array('class' => 'form-control','rows'=>5,'placeholder'=>$model->getAttributeLabel('cupon_memo')));?>
					<?php echo $form->error($model, 'cupon_memo' );?>
				</div>
			</div><!-- 图文说明 -->
			<div class="form-actions fluid">
				<div class="col-md-offset-3 col-md-9">
					<button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
					<a href="<?php echo $this->createUrl('cupon/index' , array('companyId' => $model->dpid));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
				</div>
			</div>
	<?php $this->endWidget(); ?>



	<script>
	 $(document).ready(function(){
		 var timetype = '<?php echo $model->time_type;?>';
		 if(timetype=='2'){
			$(".timetype1").hide();
			$(".timetype2").show();
		}
		 $('#Cupon_to_group').change(function(){
		 var p1=$(this).children('option:selected').val();//这就是selected的值
			//alert(p1);
			 if(p1=="2"){
				 $("#yincang").show();
			 }else{
				$("#yincang").hide();
				 }

		 })
		 $('#Cupon_time_type').change(function(){
		 var p1=$(this).children('option:selected').val();//这就是selected的值
		 	//layer.msg(p1);
			 if(p1=="1"){
				$(".timetype1").show();
				$(".timetype2").hide();
			 }else{
				$(".timetype1").hide();
				$(".timetype2").show();
				 }

		 })
		 });


	     $("#su").on('click',function() {
                var cuponmoney   =$("#Cupon_cupon_money").val();
                if(cuponmoney==false){
                    alert("请填写代金券面值");
                    return false;
                }

	         var p1 = $('#Cupon_to_group').children('option:selected').val();
	         var aa = document.getElementsByName("chk");
	         var begintime = $('#Cupon_begin_time').val();
	         var endtime = $('#Cupon_end_time').val();
	         var timetype = $('#Cupon_time_type').val();
	         var beginday = $('#begin_day').children('option:selected').val();
	         var day = $('#day').children('option:selected').val();
	         //layer.msg(beginday+'@@'+day);
	         var str=new Array();


	         if(timetype=='1'&&endtime<=begintime){
	           	 alert("<?php echo yii::t('app','活动结束时间应该大于开始时间!!!');?>");
	           	 return false;
	            }
	         if(p1=='2'){
	         for (var i = 0; i < aa.length; i++) {
	             if (aa[i].checked) {
	                 str += aa[i].value +',';
	             }
	         }
	         if(str!=''){
	         str = str.substr(0,str.length-1);//除去最后一个“，”
	         }else{
	        	 alert("<?php echo yii::t('app','请选择相应的会员等级！！！');?>");
	        	 return false;
	        	 }
	         }

	         $("#hidden1").val(str);
	         $("#cupon_begin_day").val(beginday);
	         $("#cupon_day").val(day);
	         $("#cupon-form").submit();
	     });

		function swfupload_callback(name,path,oldname)  {
			//alert(6789);
			$("#Cupon_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />");
		}

	</script>