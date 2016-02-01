  <style>
  #ajax-modal.modal{width:600px;}
  </style>
  	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'Cashcard',
			'action'=>$this->createUrl('/admin/cupon/sentCupon',array('cid'=>$this->companyId,'cupinid'=>$model->lid)),
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
		)); 
	?>
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
		<h4 class="modal-title">发送代金券</h4>
	</div>
	<div class="modal-body">
		<div class="form-group">
			<label for="GoodsCategory_category_name">填写会员号</label>
			<textarea class="form-control" placeholder="填写会员号,多个会员以 “，” 结束" name="userIds" rows="10"></textarea>	
			<div class="errorMessage"></div>								
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" data-dismiss="modal" class="btn default">取 消</button><button type="button" class="btn green submit">确 定</button>
	</div>
	<script>
	var submit = true;
	
	$('.submit').click(function(){
		var userIds = $('textarea').val();
		if(!userIds){
			$('.errorMessage').html('不能为空');
			submit = false;
			return;
		}

		if(submit){
			$('form').submit();
		}
		
	});
	</script>
<?php $this->endWidget(); ?>