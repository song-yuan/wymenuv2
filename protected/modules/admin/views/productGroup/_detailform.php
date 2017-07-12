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
									'id' => 'ProductGroupDetail-form',
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
										<label class="col-md-3 control-label" for="ProductGroupDetail_category_id">产品分类</label>
										<div class="col-md-4">
											<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control', 'disabled'=>$status,));?>
                                        </div>
                                    </div>
                                                                        
                                    <div class="form-group">
                                    <label class="col-md-3 control-label" for="product_id">产品名称</label>
										<!--<?php echo $form->label($model, 'product_id',array('class' => 'col-md-3 control-label'));?> -->
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
										</div>
									</div>
									
									
												
	                                    <div class="form-group">
											<?php echo $form->label($model, 'is_select',array('class' => 'col-md-3 control-label'));?>
											<div class="col-md-4">
												<?php echo $form->dropDownList($model, 'is_select', array( '1' =>yii::t('app','是'),'0' => yii::t('app','否')) , array('id'=>'isSelectId', 'class' => 'form-control','placeholder'=>$model->getAttributeLabel('is_select'),'readonly'=>'ture'));?>
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
									
	                                    <!--<div class="form-group">
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
										</div> -->
                                        
                                        <div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
                                        
											<button type="submit" id="su"  class="btn blue"><?php echo yii::t('app','确定');?></button>
                                   
											<!--<a href="<?php echo $this->createUrl('productGroup/detailindex' , array('companyId' => $model->dpid,'lid' => $model->lid ,'status'=>$status));?>" class="btn default"><?php echo yii::t('app','返回');?></a>-->               
										</div>
									</div>
									</div>
									<input type="hidden" id="groupno" name="groupno" value="" />
									<input type="hidden" id="isselect" name="isselect" value="" />
									<input type="hidden" id="number" name="number" value="" />
									<input type="hidden" id="pg_code" name="number" value="<?php echo $pg_code; ?>" />
									
							<?php $this->endWidget(); ?>
							
<script type="text/javascript">
$(document).ready(function(){
	$('#selectCategory').change(function(){
		var cid = $(this).val();
		$.ajax({
			url:'<?php echo $this->createUrl('productGroup/getChildren',array('companyId'=>$this->companyId));?>/pid/'+cid,
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
				$('#ProductGroupDetail_product_id').html(str); 
			}
		});
	});
	
	var productVal=$('#ProductGroupDetail_product_id').val();
	$('#ProductGroupDetail_product_id').change(function(){
		var productid = $(this).val();
		$.ajax({
			url:'<?php echo $this->createUrl('productGroup/IsDoubleGroupDetail',array('companyId'=>$this->companyId,'productgroupId'=>$prodgroupId));?>/productid/'+productid,
			type:'GET',
			dataType:'json',
			success:function(result){
				if(result.data){
					alert("<?php echo yii::t('app','该单品套餐内已经存在！');?>");
					$('#ProductGroupDetail_product_id').val(productVal);                                                                                                                                                                                                   
				}else{
				//alert(2);
					productVal=$('#ProductGroupDetail_product_id').val();
				                                                                                                
				}                                                                                             
			}
		});
	});
});

// 	$('input[name="cz"]').change(function(){
		//var type = '<?php echo $type;?>';
// 		alert(type);
// 		if(type==0 && groupnum !=''){
// 		    if($(this).val()==1){
// 		       $("#xinzu").show();
// 		       $("#tihuanzu").hide();
// 		    }else if($(this).val()==2){
// 		       $("#tihuanzu").show();
// 		      $("#xinzu").hide();
// 		    }
// 		}else{
// 			layer.msg('添加第一个套餐产品，无法替换！！！',{icon: 0});
// 			$('input[name="cz"]').val('1').click();
// 			}
// 	 });                                                        
// 	$('.minus').click(function(){
// 		var input = $(this).siblings('input');
// 		var num = parseInt(input.val());
// 		var maxgroupno = parseInt(input.attr('maxgroupno'));
// 		if(num-1 > 0){
// 			num = num - 1;
// 		}
// 		input.val(num);			
// 	});
// 	$('.plus').click(function(){
// 		var input = $(this).siblings('input');
// 		var num = parseInt(input.val());
// 		var maxgroupno = parseInt(input.attr('maxgroupno'));
// 		num = num + 1;
// 		if(num > maxgroupno){
// 			num = maxgroupno+1;
// 			$("#isSelectId").val('1');
// 		}
// 		input.val(num);			
// 	});


    $("#su").on('click',function() {
        var type = <?php echo $type;?>;
        //var isselected = <?php echo $model->is_select;?>;
    	
        //var groupnoId = $('#groupnoId').val();
        var isSelectId = $('#isSelectId').val();
        var newnumber = $('#newnumber').val();
        var newgroupnoId = $('#newgroupnoId').val();
        
        var groupnoId1 = $('#groupnoId1').val();
        var isSelectId1 = $('#isSelectId1').val();
        var number1 = $('#number1').val(); 

       
            if(type==1){
                var groupno = groupnoId;
            }else{
                var groupno = newgroupnoId;
            }
            var isselect = isSelectId;
            var number = newnumber;
            

        
        //alert(groupno);alert(isselect);alert(number);
        
        $("#groupno").val(groupno);
        $("#isselect").val(isselect);
        $("#number").val(number);
        //$("#ProductGroupDetail-form").submit();
    });
	
</script>