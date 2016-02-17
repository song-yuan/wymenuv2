  <style>
  #ajax-modal.modal{width:600px;}
  </style>
  	<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'Prize',
			'action'=>$this->createUrl('/admin/screen/index',array('companyId'=>$this->companyId,'download'=>1)),
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
			<label class="col-md-3 control-label">发放人数</label>
			<div class="col-md-4">
				<input type="number" id="number" class="form-control" name="num" value="50"/>
			</div>
		</div>
	</div>
	<div class="modal-footer" style="text-align:center;">
		<button type="button" data-dismiss="modal" class="btn default">取 消</button><button type="button" class="btn green submit">确 定</button>
	</div>
	<script>
	var submit = true;
	
	$('.submit').click(function(){
		var num = $('#number').val();
		if(num <= 0){
			alert('填写大于零的数')
			return;
		}

		if(submit){
			$('#Prize').submit();
		}
		
	});
	</script>
<?php $this->endWidget(); ?>