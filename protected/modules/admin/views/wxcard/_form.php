<?php 
	$baseUrl = Yii::app()->baseUrl;
?>
<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/bootstrap-fileupload/bootstrap-fileupload.js"></script>
<script type="text/javascript" src="<?php echo $baseUrl;?>/js/product/jquery.form.js"></script> 
<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'WeixinCard',
			'clientOptions'=>array(
				'validateOnSubmit'=>true,
			),
			'htmlOptions'=>array(
				'class'=>'form-horizontal',
				'enctype'=>'multipart/form-data'
			),
		)); ?>
<div class="row">
	<div class="main">
		<div class="media_preview_area">
		<div class="msg_card_inner">
		<p class="msg_title"><?php if($type==1) echo '优惠券';elseif($type==2) echo '兑换券'; else echo '代金券';?></p>
		<div class="js_preview msg_card_section shop disabled">
			<div class="shop_panel" id="js_color_preview">
				<div class="logo_area group">
					<span class="logo l">
					<img id="js_logo_url_preview" src="<?php echo $baseUrl;?>/img/150x150.gif">
					</span>
					<p id="js_brand_name_preview"></p>
				</div>
				<div class="msg_area">
					<div class="tick_msg">
					<p>
					<b id="js_title_preview"></b>
					</p>
					<span id="js_sub_title_preview"></span>
					<br>
					</div>
					<p class="time"><span id="js_validtime_preview"></span></p>
				</div>
				<div class="msg_card_mask">
					<span class="vm_box"></span>
					<a href="javascript:;" data-actionid="9" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
				</div>
				<div class="deco"></div>
			 </div>
			</div>
			
			<div class="js_preview msg_card_section dispose disabled">
				<div class="unset" id="js_destroy_title">
				<p>销券设置</p>
				</div>
				<div class="msg_card_mask">
				<span class="vm_box"></span>
				<a href="javascript:;" data-actionid="10" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
				</div>
			</div>
			
			<div class="shop_detail">
				<ul class="list">
					<li class="msg_card_section js_preview">
						<div class="li_panel" href="">
							<div class="li_content">
							<p><?php if($type==1) echo '优惠券';elseif($type==2) echo '兑换券';else echo '代金券';?>详情</p>
							</div>
							<span class="ic ic_go"></span>
						</div>
						<div class="msg_card_mask">
							<span class="vm_box"></span>
							<a href="javascript:;" data-actionid="11" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
						</div>
					</li>
					<li class="msg_card_section js_preview last_li">
						<div class="li_panel" href="">
							<div class="li_content">
							<p>适用门店</p>
							</div>
							<span class="ic ic_go"></span>
						</div>
						<div class="msg_card_mask">
							<span class="vm_box"></span>
							<a href="javascript:;" data-actionid="12" class="js_edit_icon edit_oper"><i class="icon18_common edit_gray"></i></a>
						</div>
					</li>
				</ul>
			
			</div>
		</div>
		</div>
		
		<div class="media_edit_area" id="js_edit_area">
			<div class="js_edit_content appmsg_editor">
				<div class="inner">
					<div class="editor_section">
					 <h3 class="title">券面信息</h3>
					 
					  <div class="form-group">
						<label class="control-label col-md-2 label-right">商家名称</label>
						<div class="col-md-4">
						 <input type="text" class="form-control" name="brand_name" value="" maxlength="12"/>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-md-2 label-right">商家Logo</label>
						<div class="col-md-8">
						  <div class="fileupload fileupload-new" data-provides="fileupload">
							<div class="fileupload-new thumbnail" style="width: 200px; height: 150px;">
								<img src="<?php echo $baseUrl;?>/img/150x150.gif" alt="" width="200"/>
							</div>
							<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 150px; line-height: 20px;"></div>
								<div>
									<span class="btn default btn-file">
									<span class="fileupload-new"><i class="fa fa-paper-clip"></i> 选择图片</span>
									<span class="fileupload-exists"><i class="fa fa-undo"></i> 更改</span>
									<input type="file" name="filename" class="default cover" />
									</span>
									<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除</a>
								</div>
						    </div>
						    <span class="label label-danger">注意!</span>
							<span>卡券的商户 logo，尺寸为300*300</span>
						 </div>
					   </div>
					  
					  <div class="form-group">
						<label class="control-label col-md-2 label-right">卡券颜色</label>
						<div class="col-md-4">
						 <select class="form-control" name="color">
						 <?php foreach($colors as $color):?>
							<option value="<?php echo $color['name'];?>" style="background-color:<?php echo $color['value'];?>"></option>
						 <?php endforeach;?>
						</select>
						</div>
						<input type="hidden" name="color_val" value="" />
					  </div>
					  
					  <?php if(!$type):?>
					  <div class="form-group">
						<label class="control-label col-md-2 label-right">减免金额</label>
						<div class="col-md-6">
						  <div class="input-icon right"> 
							<div class="input-group">
						    	<input type="text" class="form-control" name="reduce_cost" num  value="" /><span class="input-group-addon">元</span>
						    </div>
						    <span class="notice">减免金额只能是大于0.01的数字</span>
						  </div>
						</div>
					  </div>
					  <?php endif;?>
					  
					  <div class="form-group">
						<label class="control-label col-md-2 label-right"><?php if($type) echo '优惠券';else echo '代金券';?>标题</label>
						<div class="col-md-6">
						 <input type="text" class="form-control" name="title" value="" maxlength="9"/>
						 <span class="notice">卡券名称不能为空且长度不超过9个汉字</span>
						</div>
					  </div>
					  
					  <?php if(!$type):?>
					  <div class="form-group">
						<label class="control-label col-md-2 label-right">抵扣条件(选填)</label>
						<div class="col-md-6">
						  <div class="input-icon right"> 
							<div class="input-group">
						    	<input type="text" class="form-control" name="least_cost"  value="" /><span class="input-group-addon">元</span>
						    </div>
						    <span class="help-block">消费满多少元可用。如不填写则默认：消费满任意金额可用</span>
						  </div>
						</div>
					  </div>
					  <?php endif;?>
					  
					  <div class="form-group">
						<label class="control-label col-md-2 label-right">副标题<br/>(选填)</label>
						<div class="col-md-6">
						 <input type="text" class="form-control" name="sub_title" value="" maxlength="18"/>
						</div>
					  </div>
					  
					  <div class="form-group">
						<label class="control-label col-md-2 label-right">有效期</label>
						<div class="col-md-8">
							<div class="row">
							  <div class="col-md-4">
								  <div class="radio-list">
								  	<label>
										<input type="radio" name="date_info_type"  value="1" checked> 固定日期
									</label>
								  </div>
							  </div>
							  <div class="col-md-8">
								  <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
									<input type="text" class="form-control" name="begin_timestamp" value="<?php echo date('Y.m.d',time());?>" />
									<span class="input-group-addon"> ~ </span>
									<input type="text" class="form-control" name="end_timestamp" value="<?php echo date('Y.m.d',time()+24*3600);?>"  />
								  </div>
							  </div>
						</div>
						<div class="row last">
							  <div class="col-md-4">
								  <div class="radio-list">
								  	<label>
										<input type="radio" name="date_info_type"  value="2" > 领取后，
									</label>
								  </div>
							  </div>
							  <div class="col-md-8">
							    <div class="row input-large">
								   <div class="col-md-4 select left">
									   <select class="form-control" name="fixed_begin_term" disabled="disabled">
											<option value="0">当天</option>
											<?php for($i=1;$i<91;$i++):?>
											<option value="<?php echo $i;?>"><?php echo $i;?>天</option>
											<?php endfor;?>
										</select>
									</div>
									<div class="col-md-4 select middle">&nbsp;&nbsp;生效,有效天数 </div>
									<div class="col-md-4 select left">
										<select class="form-control" name="fixed_term" disabled="disabled">
											<?php for($i=1;$i<91;$i++):?>
											<option value="<?php echo $i;?>" <?php if($i==30) echo 'selected';?>><?php echo $i;?>天</option>
											<?php endfor;?>
										</select>
									</div>
								</div>
							  </div>
						</div>
					</div>
					  
					</div>
				</div>
			</div>
			<i class="arrow arrow_out"></i>
			<i class="arrow arrow_in"></i>
		</div>
		
		<div class="js_edit_content appmsg_editor">
			<div class="inner">
				<div class="editor_section">
				 <h3 class="title">领券设置</h3>
				 
				  <div class="form-group">
						<label class="control-label col-md-2 label-right">库存</label>
						<div class="col-md-6">
						  <div class="input-icon right"> 
							<i data-container="body">份</i>
						    <input type="text" class="form-control" name="quantity"  value="" />
						    <span class="notice">库存只能是大于0的数字</span>
						  </div>
					  </div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-2 label-right">领券限制<br/>(选填)</label>
						<div class="col-md-6">
						  <div class="input-icon right"> 
							<i data-container="body">张</i>
						    <input type="text" class="form-control" name="get_limit"  value="" />
						    <span class="help-block">每个用户领券上限，如不填，则默认为1</span>
						  </div>
					  </div>
					</div>
					
					<div class="form-group">
						<div class="col-md-9">
							<div class="checkbox-list">
								<label>
								<input type="checkbox" name="can_share" value="1" checked> 用户可以分享领券链接
								</label>
								<label>
								<input type="checkbox" name="can_give_friend" value="1" checked> 用户领券后可转赠其他好友
								</label>
							</div>
						</div>
					</div>
					<h3 class="title">销券设置</h3>
					<div class="form-group">
						<label class="col-md-2 control-label label-right">销券方式</label>
						<div class="col-md-9">
							<div class="radio-list">
								<label>
								<input type="radio" name="code_type" value="0" checked> 仅卡券号<br />
								<span class="help-block">&nbsp;&nbsp;&nbsp;&nbsp;只显示卡券号，验证后可进行销券</span><br />
								</label>
								<label>
								<input type="radio" name="code_type"  value="2" > 二维码<br />
								<span class="help-block">&nbsp;&nbsp;&nbsp;&nbsp;包含卡券信息的二维码，扫描后可进行销券</span><br />
								</label>
								<label>
								<input type="radio" name="code_type" value="1"> 条形码<br />
								<span class="help-block">&nbsp;&nbsp;&nbsp;&nbsp;包含卡券信息的条形码，扫描后可进行销券</span><br />
								</label>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<label class="control-label col-md-2 label-right">操作提示</label>
						<div class="col-md-8">
						 <input type="text" class="form-control" name="notice" value="" placeholder="" maxlength="16"/>
						 <span class="help-block">建议引导用户到店出示卡券，由店员完成核销操作</span>
						</div>
					</div>
					  
		        </div>
		  </div>
		 <i class="arrow arrow_out"></i>
		 <i class="arrow arrow_in"></i>
		</div>
		
		<div class="js_edit_content appmsg_editor">
			<div class="inner">
				<div class="editor_section">
				 <h3 class="title">优惠详情</h3>
				 
				 <?php if($type):?>
				 <div class="form-group">
					<label class="control-label col-md-2 label-right">优惠详情</label>
					<div class="col-md-8">
					 <textarea name="default_detail" rows="5" cols="50"></textarea>
					</div>
				</div>
				<?php endif;?>
				 
				 <div class="form-group">
					<label class="control-label col-md-2 label-right">使用须知</label>
					<div class="col-md-8">
					 <textarea name="description" rows="5" cols="50"></textarea>
					</div>
				</div>
				
				<div class="form-group">
					<label class="control-label col-md-2 label-right">客服电话</label>
					<div class="col-md-6">
					 <input type="text" class="form-control" name="service_phone" value="" placeholder=""/>
					 <span class="help-block">手机或固话</span>
					</div>
				</div>
				
				 
		      </div>
		  </div>
		 <i class="arrow arrow_out"></i>
		 <i class="arrow arrow_in"></i>
		</div>
		
		<div class="js_edit_content appmsg_editor">
			<div class="inner">
				<div class="editor_section">
				 <h3 class="title">服务信息</h3>
				 <div class="form-group">
						<label class="col-md-2 control-label label-right">适用门店</label>
						<div class="col-md-9">
							<div class="radio-list">
								<span class="help-block">"适用门店"方便帮助用户到店消费。如有门店，请仔细配置。可在"功能"-"门店管理"管理门店信息。</span>
								<label>
								<input type="radio" name="js_shop_type" value="1" checked> 指定门店适用<br />
								</label>
								<div class="table-responsive" id="js_fix_shop" style="margin-left:30px;display:block;">
									<table class="table table-striped table-bordered table-hover" id="js_shop_table" style="display:none">
										<thead>
											<tr>
												<th width="50%">门店名称</th>
												<th width="50%">地址</th>
											</tr>
										</thead>
										<tbody>
										</tbody>
									</table>
									<a href="javascript:;" id="js_add_shop">添加适用门店</a><br />
								</div>
								<label>
								<input type="radio" name="js_shop_type"  value="2"> 无指定门店<br />
								</label>
								<label>
								<input type="radio" name="js_shop_type" value="3"> 全部门店适用<br />
								</label>
							</div>
						</div>
					</div>
				 
		        </div>
		  </div>
		 <i class="arrow arrow_out"></i>
		 <i class="arrow arrow_in"></i>
		</div>
		
	</div>
	<div class="clear"></div>
</div>
<div class="form-actions fluid">
	<div class="row">
		<div class="col-md-12">
			<div class="col-md-offset-3 col-md-9">
				<button type="submit" class="btn green"><i class="fa fa-check"></i> 提交审核</button>
			</div>
		</div>
	</div>
</div>
</div>
<?php $this->endWidget(); ?>

