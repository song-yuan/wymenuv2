<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'productCategory-form',
				'action'=>$action,
				'enableAjaxValidation'=>false,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
<h5 style="margin-left: 150px;">选择<span style="color: red;"><?php echo $name.$specname;?><input type="hidden" name="eleme[elemeId]" value="<?php echo $elemeId?>"><?php if($specsid):?><input type="hidden" name="eleme[specid]" value="<?php echo $specsid;?>"><?php endif;?></span>关联菜品</h5>
<div class="form-group">
     <label class="col-md-3 control-label" for="ProductCategory_show_type">关联菜品</label>
     <div class="col-md-4">
     <select class="form-control" id="categoryid"  placeholder="关联菜品" name="category_name">
	        <option value="">选择菜品分类</option>
	        <?php foreach ($modelCategory as $model):?>
			<option value="<?php echo $model->lid?>"><?php echo $model->category_name?></option>
			<?php endforeach;?>    
       </select>
      
     </div>
     <div class="col-md-4">
       <select class="form-control category_selecter" placeholder="关联菜品" name="eleme[phs_code]">
	      <option value="">选择菜品</option>
        </select>
     </div>
</div>
<div class="modal-footer">
	<input type="submit" class="btn green" id="create_btn" value="<?php echo yii::t('app','确 定');?>">
</div>
<?php $this->endWidget(); ?>
<script type="text/javascript">
$(document).ready(function(){
	 $('#categoryid').change(function(){
			var cid = $(this).val();
			$('.category_selecter').empty();
			$.ajax({
            type: "get",//数据发送的方式（post 或者 get）
            url: "<?php echo $this->createUrl('eleme/canzhi',array('companyId'=>$companyId));?>",//要发送的后台地址
            data: {cid:cid},//要发送的数据（参数）格式为{'val1':"1","val2":"2"}
            dataType: "json",//后台处理后返回的数据格式
            success: function (result) {//ajax请求成功后触发的方法
	   						$.each(result.data,function(index,value){
	   						$(".category_selecter").append('<option value="'+value.id+'">'+value.name+'</option>');

	   					});	
            },
            error: function (msg) {//ajax请求失败后触发的方法
                alert('该分类没有菜品');//弹出错误信息
            }
        });
	});
});
</script>