<style>
	div.radio{
		margin-bottom: 10px !important;
	}
	.radioclass{
		border: 1px solid red;
		border-radius: 6px;
	}
	.radioinput{
		margin: 0px !important;
	}
</style>
							<?php $form=$this->beginWidget('CActiveForm', array(
									'id' => 'ProductSetDetail-form',
									'errorMessageCssClass' => 'help-block',
									'htmlOptions' => array(
										'class' => 'form-horizontal',
										'enctype' => 'multipart/form-data'
									),
							)); ?>
								<div class="form-body">
								<?php if($status):?>
								<?php $status=true;else:$status=false;?>
								<?php endif;?>
								<?php if(!$model->dpid):?>
									<div class="form-group">
										<?php echo $form->label($model, 'dpid',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->dropDownList($model, 'dpid', array('0' => yii::t('app','-- 请选择 --')) +Helper::genCompanyOptions() ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid') , 'disabled'=>$status,));?>
											<?php echo $form->error($model, 'dpid' )?>
										</div>
									</div>
								<?php endif;?>

                                    <div class="form-group">
										<?php echo $form->label($model, 'category_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control', 'disabled'=>$status,));?>
                                        </div>
                                    </div>

                                    <div class="form-group" <?php if($model->hasErrors('product_id')) echo 'has-error';?>>
										<?php echo $form->label($model, 'product_id',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
                                            <?php echo $form->dropDownList($model, 'product_id', array('0' => yii::t('app','-- 请选择 --')) +$products ,array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('dpid'), 'disabled'=>$status,));?>
											<?php echo $form->error($model, 'product_id' )?>
										</div>
									</div>
                                    <div class="form-group" <?php if($model->hasErrors('price')) echo 'has-error';?>>
										<?php echo $form->label($model, 'price',array('class' => 'col-md-3 control-label'));?>
										<div class="col-md-4">
											<?php echo $form->textField($model, 'price',array('class' => 'form-control','placeholder'=>$model->getAttributeLabel('price')));?>
											<?php echo $form->error($model, 'price' )?>
											<span style="color:red" class="control-label">单个菜品的差额（注：若数量为2，则套餐总价会加上差额乘以2）</span>
										</div>
									</div>
									<div class="form-group">
										<span class="col-md-3 control-label">是否替换其他分组菜品</span>

										<div id="czfs" class="col-md-4 ">
	                                        <input class="radioinput" type="radio" id="RYcz" <?php if($model->is_select == 1) echo 'checked';?> name="cz" value="1" <?php if($type==1) echo 'disabled';?>>
	                                        <label class="radioclass" name="RYcz" for="RYcz">新加分组</label>
	                                        <input class="radioinput" type="radio" id="TCcz" <?php if($model->is_select == 0) echo 'checked';?> name="cz" value="2" <?php if($type==1) echo 'disabled';?>>
	                                        <label class="radioclass" name="TCcz" for="TCcz">可替换菜品</label>
                                    	</div>

									</div>
									<div id="xinzu" style="<?php if($model->is_select == 0) echo 'display: none';?>">
	                                    <div class="form-group" <?php if($model->hasErrors('group_no')) echo 'has-error';?>>
											<?php echo $form->label($model, 'group_no',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
											<?php if($model->group_no>=0 && $model->product_id !=0):?>
												<?php echo $form->textField($model, 'group_no',array('id'=>'groupnoId', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('group_no'),'readonly'=>"true"));?>
												<?php echo $form->error($model, 'group_no' )?>
											<?php else:?>
												<input type="text" id="newgroupnoId" maxlength="5" size="5" class="additionnum" maxgroupno="<?php echo $maxgroupno;?>" value="<?php echo $maxgroupno+1;?>" readonly="true"/>
											<?php endif;?>

											</div>
										</div>
	                                    <div class="form-group">
											<?php echo $form->label($model, 'is_select',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
												<?php echo $form->dropDownList($model, 'is_select', array( '1' =>yii::t('app','是')) , array('id'=>'isSelectId', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_select'),'readonly'=>'ture'));?>
												<?php echo $form->error($model, 'is_select' )?>
											</div>
										</div>
	                                    <div class="form-group" <?php if($model->hasErrors('number')) echo 'has-error';?>>
											<?php echo $form->label($model, 'number',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
	                                            <?php echo $form->textField($model, 'number',array('id'=>'newnumber','class' => 'form-control','placeholder'=>$model->getAttributeLabel('number')));?>
												<?php echo $form->error($model, 'number' )?>
											</div>
										</div>
									</div>
									<div id="tihuanzu" style="<?php if($model->is_select == 1) echo 'display: none;'?>">
	                                    <div class="form-group" <?php if($model->hasErrors('group_no')) echo 'has-error';?>>
											<?php echo $form->label($model, '可替换分组菜品（默认）',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
												<?php echo $form->dropDownList($model, 'group_no', $groups , array('id'=>'groupnoId1', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('group_no')));?>
												<?php echo $form->error($model, 'group_no' )?>
											</div>
										</div>
	                                    <div class="form-group">
											<?php echo $form->label($model, 'is_select',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
												<?php echo $form->dropDownList($model, 'is_select', array('0' => yii::t('app','否') , ) , array('id'=>'isSelectId1', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_select')));?>
												<?php echo $form->error($model, 'is_select' )?>
											</div>
										</div>
	                                    <div class="form-group" <?php if($model->hasErrors('number')) echo 'has-error';?>>
											<?php echo $form->label($model, 'number',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
	                                            <?php echo $form->textField($model, 'number',array('id'=>'number1','class' => 'form-control','placeholder'=>$model->getAttributeLabel('number')));?>
												<?php echo $form->error($model, 'number' )?>
											</div>
										</div>
									</div>
									<input type="hidden" id="groupno" name="groupno" value="" />
									<input type="hidden" id="isselect" name="isselect" value="" />
									<input type="hidden" id="number" name="number" value="" />
									<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<button type="button" id="su"  class="btn blue"><?php echo yii::t('app','确定');?></button>
											<!-- <a href="<?php echo $this->createUrl('productSet/detailindex' , array('companyId' => $model->dpid,'lid' => $model->set_id ,'status'=>$status));?>" class="btn default"><?php echo yii::t('app','返回');?></a> -->
										</div>
									</div>
							<?php $this->endWidget(); ?>

<script type="text/javascript">
$(document).ready(function(){
	$('#selectCategory').change(function(){
		var cid = $(this).val();
		//alert('<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId,'productSetId'=>$model->set_id));?>/pid/'+cid);
		//alert($('#ProductSetDetail_product_id').html());
		$.ajax({
			url:'<?php echo $this->createUrl('productSet/getChildren',array('companyId'=>$this->companyId,'productSetId'=>$model->set_id));?>/pid/'+cid,
			type:'GET',
			dataType:'json',
			success:function(result){
				//alert(result.data);
				var str = '<?php echo yii::t('app','<option value="">--请选择--</option>');?>';
				if(result.data.length){
				//alert(1);
					$.each(result.data,function(index,value){
						str = str + '<option value="'+value.id+'">'+value.name+'</option>';
					});
				}
				$('#ProductSetDetail_product_id').html(str);
			}
		});
	});
// 	var productVal=$('#ProductSetDetail_product_id').val();
// 	$('#ProductSetDetail_product_id').change(function(){
// 		var productid = $(this).val();
// 		//alert(productid);
// 		$.ajax({
			url:'<?php echo $this->createUrl('productSet/isDoubleSetDetail',array('companyId'=>$this->companyId,'productSetId'=>$model->set_id));?>/productid/'+productid,
// 			type:'GET',
// 			dataType:'json',
// 			success:function(result){
// 				if(result.data){
					alert("<?php echo yii::t('app','该单品套餐内已经存在！');?>");
// 					$('#ProductSetDetail_product_id').val(productVal);
// 				}else{
// 				//alert(2);
// 					productVal=$('#ProductSetDetail_product_id').val();

// 				}
// 			}
// 		});
// 	});
});
	$('input[name="cz"]').change(function(){
		var type = '<?php echo $type;?>';
		var groupnum = '<?php echo $maxgroupno;?>';
		//alert(type);
		if(type==0 && groupnum !=''){
		    if($(this).val()==1){
		       $("#xinzu").show();
		       $("#tihuanzu").hide();
		    }else if($(this).val()==2){
		       $("#tihuanzu").show();
		      $("#xinzu").hide();
		    }
		}else{
			layer.msg('添加第一个套餐产品，无法替换！！！',{icon: 0});
			$('input[name="cz"]').val('1').click();
			}
	 });
	$('.minus').click(function(){
		var input = $(this).siblings('input');
		var num = parseInt(input.val());
		var maxgroupno = parseInt(input.attr('maxgroupno'));
		if(num-1 > 0){
			num = num - 1;
		}
		input.val(num);
	});
	$('.plus').click(function(){
		var input = $(this).siblings('input');
		var num = parseInt(input.val());
		var maxgroupno = parseInt(input.attr('maxgroupno'));
		num = num + 1;
		if(num > maxgroupno){
			num = maxgroupno+1;
			$("#isSelectId").val('1');
		}
		input.val(num);
	});


    $("#su").on('click',function() {
        var type = <?php echo $type;?>;
        var isselected = <?php echo $model->is_select;?>;
    	var val=$('input:radio[name="cz"]:checked').val();

        var groupnoId = $('#groupnoId').val();
        var isSelectId = $('#isSelectId').val();
        var newnumber = $('#newnumber').val();
        var newgroupnoId = $('#newgroupnoId').val();

        var groupnoId1 = $('#groupnoId1').val();
        var isSelectId1 = $('#isSelectId1').val();
        var number1 = $('#number1').val();

        if(val==2){
            var groupno = groupnoId1;
            var isselect = isSelectId1;
            var number = number1;
        }else{
            if(type==1){
                var groupno = groupnoId;
            }else{
                var groupno = newgroupnoId;
            }
            var isselect = isSelectId;
            var number = newnumber;
            }


        //alert(groupno);alert(isselect);alert(number);

        $("#groupno").val(groupno);
        $("#isselect").val(isselect);
        $("#number").val(number);
        $("#ProductSetDetail-form").submit();
    });

</script>