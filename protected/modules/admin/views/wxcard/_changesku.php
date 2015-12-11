			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">更改卡券库存</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
					<div class="row">
						<div class="col-md-3">
						</div>
						<div class="col-md-6">
							<div class="radio-list">
								<label class="radio-inline">
								<input type="radio" name="changeType" value="1" checked> 增加
								</label>
								<label class="radio-inline">
								<input type="radio" name="changeType"  value="0" > 减少
								</label>
							</div>
						</div>
						<div class="col-md-3">
						</div>
					</div>
					<div class="row" style="margin-top:20px;">
						<div class="col-md-3">
						</div>
						<div class="col-md-6">
							<div class="input-group">
								<input type="text" class="form-control" name="sku"  value="">
								<span class="input-group-addon">份</span>
							</div>
						</div>
						<div class="col-md-3">
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button type="button" data-dismiss="modal" class="btn default">取 消</button>
				<input type="submit" class="btn green" id="create_btn" value="确 定"/>
			</div>
		<script>
		App.init();
		$('#create_btn').click(function(){
			var checked = $('input[name="changeType"]:checked');
			var sku = $('input[name="sku"]');
			 $.post('<?php echo $this->createUrl('/admin/wxcard/changeSku',array('companyId'=>$this->companyId,'id'=>$id));?>',{type:checked.val(),sku:sku.val()},function(data){
					if(data.status){
						history.go(0);
					}else{
						alert(data.msg);
					}
		   		},'json');
		});
		</script>
