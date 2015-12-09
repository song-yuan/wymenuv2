			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
				<h4 class="modal-title">选择你要创建的卡券类型</h4>
			</div>
			<div class="modal-body">
				<div class="form-group">
				<div class="col-md-12">
					<div class="radio-list">
						<label>
						<input type="radio" name="cardType"  value="2" checked> 兑换券<br />
						<span class="help-block">&nbsp;&nbsp;&nbsp;&nbsp;即“通用券”，建议当以上无法满足需求时采用</span><br />
						</label>
						<label>
						<input type="radio" name="cardType" value="0"> 代金券<br />
						<span class="help-block">&nbsp;&nbsp;&nbsp;&nbsp;可为用户提供抵扣现金服务。可设置成为“满*元，减*元”</span><br />
						</label>
						<label>
						<input type="radio" name="cardType"  value="1" > 优惠券<br />
						<span class="help-block">&nbsp;&nbsp;&nbsp;&nbsp;即“通用券”，建议当以上无法满足需求时采用</span><br />
						</label>
					</div>
				</div>
				</div>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button type="button" data-dismiss="modal" class="btn default">取 消</button>
				<input type="submit" class="btn green" id="create_btn" value="确 定"/>
			</div>
		<script>
		$('#create_btn').click(function(){
			var checked = $('input[name="cardType"]:checked');
			if(parseInt(checked.val())==1){
				location.href = '<?php echo $this->createUrl('/admin/wxcard/create',array('companyId'=>$this->companyId,'type'=>1));?>';
			}else if(parseInt(checked.val())==2){
				location.href = '<?php echo $this->createUrl('/admin/wxcard/create',array('companyId'=>$this->companyId,'type'=>2));?>';
			}else{
				location.href = '<?php echo $this->createUrl('/admin/wxcard/create',array('companyId'=>$this->companyId));?>';
			}
		});
		</script>
