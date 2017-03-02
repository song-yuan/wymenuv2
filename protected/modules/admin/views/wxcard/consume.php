	
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array( 'breadcrumbs'=>array(array('word'=>yii::t('app','线上活动'),'url'=>$this->createUrl('discount/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>'营销品设置','url'=>''),array('word'=>'微信卡券','url'=>$this->createUrl('wxcard/index' , array('companyId' => $this->companyId,'type'=>1)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wxcard/index' , array('companyId' => $this->companyId,'type'=>1)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<ul class="nav nav-tabs">
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cashcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','整体设置');?></a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('privatepromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','特价优惠');?></a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cupon/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','代金券');?></a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('gift/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','礼品券');?></a></li>
						<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('wxcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','微信卡券');?></a></li>
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
		    $('#btn_search').click(function(){
                $.get('<?php echo $this->createUrl('/admin/wxcard/getwxcard',array('companyId'=>$this->companyId));?>&code='+$('#dealCode').val(),function(data){
                    if(data.status){
                        $('#offlineOrderForm').html(data.html);
                    } 
                },'json');
		   });
		});
	</script>