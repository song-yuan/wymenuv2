		<script type="text/javascript" src="metronic/plugins/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="metronic/plugins/select2/select2_metro.css" />
		<script src="metronic/plugins/bootbox/bootbox.min.js" type="text/javascript" ></script>
		
		<link href="metronic/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<script src="metronic/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
		<script src="metronic/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.brand.components.widgets.PageHeader', array('head'=>'营销中心','subhead'=>'微信卡券列表','breadcrumbs'=>array(array('word'=>'营销中心','url'=>''),array('word'=>'营销品设置','url'=>''),array('word'=>'微信卡券','url'=>''))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/cashcard',array('cid'=>$this->companyId));?>'" data-toggle="tab">商城抵用券</a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/giftcard',array('cid'=>$this->companyId));?>'" data-toggle="tab">门店兑换券</a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/gift',array('cid'=>$this->companyId));?>'" data-toggle="tab">礼品兑换</a></li>
						<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('/brand/wxcard',array('cid'=>$this->companyId));?>'" data-toggle="tab">微信卡券</a></li>
					</ul>
				</div>
				<div class="row">
					<div class="col-md-4"></div>
					<div class="col-md-4">
						<div>
							<div class="input-group">
								<div class="input-cont">
									<input type="text" placeholder="输入卡券卡号" id="dealCode" name="dealCode" class="form-control">
								</div>
								<span class="input-group-btn">
								<button type="button" id="btn_search" class="btn green">
								查询 &nbsp; 
								<i class="m-icon-swapright m-icon-white"></i>
								</button>
								</span>    
							</div>
						</div>
					</div>
					<div class="col-md-4"></div>
				</div>
				
				<div class="row">
					<div class="col-md-12" id="offlineOrderForm">
						
					</div>
				</div>
				
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- END PAGE -->
		<div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true">
			<div id="ajax-modal" class="modal fade" tabindex="-1"  style="width:600px;">
			</div>
			<div class="modal-dialog">
				<div class="modal-content">

				</div>
			</div>
		</div>
	<script>
		jQuery(document).ready(function() {       
		   App.init();
		    $('#btn_search').click(function(){
                $.get('<?php echo $this->createUrl('/brand/wxcard/getwxcard',array('cid'=>$this->companyId));?>&code='+$('#dealCode').val(),function(data){
                    if(data.status){
                        $('#offlineOrderForm').html(data.html);
                    } 
                },'json');
		   });
		});
	</script>