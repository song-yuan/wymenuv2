		<?php 
			$isCash = 0;
		 	if($cardInfo->card_type=="CASH"){
		 		$isCash = 1;
		 		$baseInfo = $cardInfo->cash->base_info;
		 		$leastCost = isset($cardInfo->cash->least_cost)?$cardInfo->cash->least_cost:0;
		 		$reduceCost = $cardInfo->cash->reduce_cost;
		 	}else{
		 		$baseInfo = $cardInfo->general_coupon->base_info;
		 		$defaultDetail = $cardInfo->general_coupon->default_detail;
		 	}
		?>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
		<link rel="stylesheet" type="text/css" href="metronic/css/wxcarddetail.css" />
		
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="metronic/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/jquery-multi-select/css/multi-select.css" />
		<script type="text/javascript" src="metronic/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
		<script type="text/javascript" src="metronic/plugins/jquery-multi-select/js/jquery.quicksearch.js"></script>   
		<!-- BEGIN PAGE -->  
		<div class="page-content">
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
			<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
							<h4 class="modal-title">Modal title</h4>
						</div>
						<div class="modal-body">
							Widget settings form goes here
						</div>
						<div class="modal-footer">
							<button type="button" class="btn blue">Save changes</button>
							<button type="button" class="btn default" data-dismiss="modal">Close</button>
						</div>
					</div>
					<!-- /.modal-content -->
				</div>
				<!-- /.modal-dialog -->
			</div>
			<!-- /.modal -->
			<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->   
			<?php $this->widget('application.modules.brand.components.widgets.PageHeader', array('head'=>'添加卡券','subhead'=>'添加卡券','breadcrumbs'=>array(array('word'=>'营销品管理','url'=>''),array('word'=>'微信卡券','url'=>''),array('word'=>'添加卡券','url'=>'')),'back'=>array('word'=>'返回','url'=>array('/brand/wxcard','cid'=>$this->companyId))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom boxless">
						<div class="portlet box purple">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-gift"></i>卡券详情</div>
							</div>
							<div class="portlet-body form">
								<!-- BEGIN FORM-->
									<div class="row">
									  <div class="topgray">
									  卡券状态&nbsp;&nbsp;<?php 
									  					switch ($baseInfo->status){
									  								case "CARD_STATUS_NOT_VERIFY":
									  										echo '待审核';break;
									  								case "CARD_STATUS_VERIFY_FAIL":
									  										echo '未通过';break;
									  								case "CARD_STATUS_VERIFY_OK":
									  										echo '通过';break;
									  								case "CARD_STATUS_DELETE":
									  										echo '已删除';break;
									  								case "CARD_STATUS_USER_DISPATCH":
									  										echo '公众平台投放过的卡券';break;
									  					}?><br/>
									  卡券ID&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $baseInfo->id;?><br/>
									  </div>
									</div>
									<div class="row">
										<div class="main">
											<div class="media_preview_area">
											<div class="msg_card_inner">
											<p class="msg_title"><?php if($isCash) echo '代金券';else echo '优惠券';?></p>
											<div class="js_preview msg_card_section shop disabled">
												<div class="shop_panel" id="js_color_preview" style="background:<?php echo $baseInfo->color;?>">
													<div class="logo_area group">
														<span class="logo l">
														<img id="js_logo_url_preview" src="<?php echo $baseInfo->logo_url;?>">
														</span>
														<p id="js_brand_name_preview"><?php echo $baseInfo->brand_name;?></p>
													</div>
													<div class="msg_area">
														<div class="tick_msg">
														<p>
														<b id="js_title_preview"><?php echo $baseInfo->title;?></b>
														</p>
														<span id="js_sub_title_preview"><?php echo isset($baseInfo->sub_title)?$baseInfo->sub_title:'无';?></span>
														<br>
														</div>
														<p class="time"><span id="js_validtime_preview">
														有效期 <?php if($baseInfo->date_info->type==1){ echo date('Y-m-d',$baseInfo->date_info->begin_timestamp).'至'.date('Y-m-d',$baseInfo->date_info->end_timestamp);}elseif($baseInfo->date_info->type==2){ echo '领取后'.$baseInfo->date_info->fixed_begin_term?$baseInfo->date_info->fixed_begin_term:'当天'.'生效'.$baseInfo->date_info->fixed_term.'天有效';}?></span></p>
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
																<p>代金券详情</p>
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
											
										<div class="media_edit_area" >
											<div class="title">券面信息</div>
											<div class="row colorgray">
												<div class="col-md-2">卡券类型</div>
												<div class="col-md-6"><?php if($isCash) echo '代金券';else echo '通用券';?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">卡券标题</div>
												<div class="col-md-6"><?php echo $baseInfo->title;?></div>
											</div>
											<?php if($isCash):?>
											<div class="row colorgray">
												<div class="col-md-2">减免金额</div>
												<div class="col-md-6"><?php echo $reduceCost/100 .'元';?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">抵扣条件</div>
												<div class="col-md-6"><?php if($leastCost) echo  '满'. $leastCost/100 .'元可用' ; else echo '无';?></div>
											</div>
											<?php endif;?>
											<div class="row colorgray">
												<div class="col-md-2">副标题</div>
												<div class="col-md-6"><?php echo isset($baseInfo->sub_title)?$baseInfo->sub_title:'无';?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">卡券颜色</div>
												<div class="col-md-6"><span class="cardcolor" style="background:<?php echo $baseInfo->color;?>"></span></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">有效期</div>
												<div class="col-md-6"> <?php if($baseInfo->date_info->type==1){ echo date('Y-m-d',$baseInfo->date_info->begin_timestamp).'至'.date('Y-m-d',$baseInfo->date_info->end_timestamp);}elseif($baseInfo->date_info->type==2){ echo '领取后'; echo $baseInfo->date_info->fixed_begin_term >0 ?$baseInfo->date_info->fixed_begin_term:'当天'; echo '生效'.$baseInfo->date_info->fixed_term.'天有效';}?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">商家名称</div>
												<div class="col-md-6"><?php echo $baseInfo->brand_name;?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">商家Logo</div>
												<div class="col-md-6"><img src="<?php echo $baseInfo->logo_url;?>"/></div>
											</div>
											
											<div class="title">投放设置</div>
											<div class="row colorgray">
												<div class="col-md-2">库存</div>
												<div class="col-md-6"><?php echo $baseInfo->sku->quantity;?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">销券条码</div>
												<div class="col-md-6"><?php switch($baseInfo->code_type){
																			  case 'CODE_TYPE_TEXT':
																			  		echo '仅序列号';break;
																			  case 'CODE_TYPE_BARCODE':
																			  		echo '条形码';break;
																			  case 'CODE_TYPE_QRCODE':
																			  		echo '二维码';break;
																	  }?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">操作提示</div>
												<div class="col-md-6"><?php echo $baseInfo->notice;?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">领取限制</div>
												<div class="col-md-6">每个用户限领<?php echo isset($baseInfo->get_limit)?$baseInfo->get_limit:'';?>张</div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">分享设置</div>
												<div class="col-md-6"><?php if($baseInfo->can_share) echo '用户可以分享领券链接';else echo '用户不可以分享领券链接'; ?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">转赠设置</div>
												<div class="col-md-6"><?php if($baseInfo->can_give_friend) echo '用户领券后可转赠其他好友';else echo '用户领券后不可转赠其他好友';?></div>
											</div>
											
											<div class="title">代金券详情</div>
											<div class="row colorgray">
												<div class="col-md-2">使用须知</div>
												<div class="col-md-6"><?php echo $baseInfo->description;?></div>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">优惠详情</div>
												<?php if($isCash):?>
												<div class="col-md-6">价值<?php echo $reduceCost/100;?>元代金券1张，满<?php echo $leastCost/100;?>元可使用。</div>
												<?php else:?>
												<div class="col-md-6"><?php echo $defaultDetail;?></div>
												<?php endif;?>
											</div>
											<div class="row colorgray">
												<div class="col-md-2">客服电话</div>
												<div class="col-md-6"><?php echo $baseInfo->service_phone;?></div>
											</div>
											<div class="title">服务信息</div>
											<div class="row colorgray">
												<div class="col-md-2">适用门店</div>
												<div class="col-md-6">所有</div>
											</div>

										
										</div>
										<div class="clear"></div>
									</div>
									
									</div>

								<!-- END FORM--> 
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
		<script>
		jQuery(document).ready(function() {       
		   // initiate layout and plugins
		    App.init();
			jQuery(document).ready(function(){
				if( jQuery("#Gift_gift_pic_large").val()){
			           jQuery("#thumbnails_1").html("<img src='"+jQuery("#Gift_gift_pic_large").val()+"?"+(new Date()).getTime()+"' />"); 
				}
			});
	        if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	            	format: 'yyyy.mm.dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	        }
		});
		function swfupload_callback1(name,path,oldname)  {
			jQuery("#Gift_gift_pic_large").val(name);
			jQuery("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		</script>