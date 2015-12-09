				
				<h3 class="form-section">卡券详细信息</h3>
				<div class="col-md-12">
					<table class="table table-bordered table-striped">
						<tbody>
							<tr>
								<td style="width:50%"><img src="<?php echo $model->logo;?>" width="76" height="76" style="border-radius: 100% !important;"/></td>
								<td></td>
							</tr>
							<tr>
								<td style="width:50%"><?php echo $model->title;?></td>
								<td></td>
							</tr>
							<tr>
								<td>
									<?php if($card):?>
									<span style="color:#8d8d8d">
										有效期:<?php echo date('Y.m.d',$card->begin_time).'-'.date('Y.m.d',$card->end_time);?><br/>
										序列号:<?php echo $code;?>
									</span>
									<?php else: ?>
									<span style="color:#8d8d8d">
										<?php if($model->date_info_type == 1):?>
										有效期:<?php echo date('Y.m.d',$model->begin_timestamp).'-'.date('Y.m.d',$model->end_timestamp);?><br/>
										序列号:<?php echo $code;?>
										<?php elseif($model->date_info_type == 2):?>
										有效期:<?php echo date('Y.m.d',$model->create_time + $model->fixed_begin_term*24*60*60).'-'.date('Y.m.d',$model->create_time + ($model->fixed_begin_term+$model->fixed_term)*24*60*60);?><br/>
										序列号:<?php echo $code;?>
										<?php endif;?>
									</span>
									<?php endif; ?>
								</td>
								<td> 
									<?php if($card):?>
									<span style="color:#44b549;font-size: 16px;">有效卡券</span>
									<?php else:?>
									<span style="color:#e15f63;font-size: 16px;">已删除</span>
									<?php endif;?>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
				<!--/row-->
				<?php if($card):?>
				<!--/row-->
				<div class="form-actions fluid">
					<div class="row">
						<div class="col-md-6">
							<div class="col-md-offset-3 col-md-9">
								<button type="submit" id="offline_confirm" class="btn green" onclick="jQuery.get('<?php echo $this->createUrl('/brand/wxcard/confirmCard',array('cid'=>$this->companyId,'code'=>$code));?>',function(data){alert(data.msg);if(data.status){$('#offline_confirm').remove();}},'json');"> 确认核销</button>
								<button type="button" class="btn default" onclick="jQuery('#offlineOrderForm').empty();jQuery('#dealCode').val('');"> 取 消 </button>                              
							</div>
						</div>
						<div class="col-md-6">
						</div>
					</div>
				</div>
				<?php endif;?>
