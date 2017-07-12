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
		'id' => 'ProductSetGroup-form',
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


        <div class="form-group">
			<label for="" class= 'col-md-3 control-label'>产品组合</label>
			<div class="col-md-4">
				<select name="prod_group_id" id="" class="form-control">
					<?php if($pgroups): ?>
					<option value="">- 请选择产品组合 -</option>
					<?php foreach ($pgroups as $pgroup): ?>
					<option value="<?php echo $pgroup->lid; ?>" ><?php echo $pgroup->name; ?></option>
					<?php endforeach; ?>
					<?php else: ?>
					<option><?php echo Yii::t('app','请基础设置添加产品组合'); ?></option>
					<?php endif; ?>
				</select>
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
	var productVal=$('#ProductSetDetail_product_id').val();
	$('#ProductSetDetail_product_id').change(function(){
		var productid = $(this).val();
		//alert(productid);
		$.ajax({
			url:'<?php echo $this->createUrl('productSet/isDoubleSetDetail',array('companyId'=>$this->companyId,'productSetId'=>$model->set_id));?>/productid/'+productid,
			type:'GET',
			dataType:'json',
			success:function(result){
				if(result.data){
					alert("<?php echo yii::t('app','该单品套餐内已经存在！');?>");
					$('#ProductSetDetail_product_id').val(productVal);
				}else{
				//alert(2);
					productVal=$('#ProductSetDetail_product_id').val();
				}
			}
		});
	});
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
        $("#ProductSetGroup-form").submit();
    });

</script>