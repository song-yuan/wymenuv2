		<?php 
			$baseUrl = Yii::app()->baseUrl;
		?>
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/select2/select2_metro.css" />
		<script src="<?php echo $baseUrl;?>/plugins/bootbox/bootbox.min.js" type="text/javascript" ></script>
		
		<link href="<?php echo $baseUrl;?>/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/custom.css" />
<style>
    .panel{
        padding: 0px !important;
    }
</style>

                
                
                <!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信赠券'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>'卡券','url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId' => $this->companyId,'type'=>1)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="col-md-12 panel">
			<div class="tabbable tabbable-custom">
					<ul class="nav nav-tabs">
						<!--<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cashcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','整体设置');?></a></li>
                                                <li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('normalpromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','普通优惠');?></a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('privatepromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','特价优惠');?></a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullSentPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满送优惠');?></a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('fullMinusPromotion/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','满减优惠 ');?></a></li> 
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('cupon/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','代金券');?></a></li>
						<li class=""><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('gift/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','礼品券');?></a></li>
						<li class="active"><a href="javascript:;" onclick="location.href='<?php echo $this->createUrl('wxcard/index',array('companyId'=>$this->companyId));?>'" data-toggle="tab"><?php echo yii::t('app','微信卡券');?></a></li>-->
					</ul>
				
				<div class="col-md-12 panel">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box purple">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-gift"></i>微信卡券列表</div>
							<div class="actions">
								<a href="javascript:;" class="btn blue addCard"><i class="fa fa-pencil"></i> 添加卡券</a>
								<a href="<?php echo $this->createUrl('/admin/wxcard/consume',array('companyId'=>$this->companyId));?>" class="btn red"><i class="fa fa-globe"></i> 核销卡券</a>
							</div>
						</div>
						<div class="portlet-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="sample_3">
										<thead>
											<tr>
												<th width="10%">二维码</th>
												<th width="10%">卡券名称</th>
												<th width="10%">发行数量</th>
												<th width="10%">有效期</th>
												<th width="10%">状态</th>
												<th width="10%">操作</th>
											</tr>
										</thead>
										<tbody>
										<?php if($models):?>
										<?php foreach($models as $model):?>
											<tr>
												<td><img style="width:100px;" src="<?php echo $model['qrcode']?$baseUrl.'/'.$model['qrcode']:'';?>"/><a class="btn default btn-xs blue" title="生成二维码" href="javascript:;" cardid="<?php echo $model['lid'];?>" onclick="genQrcode(this);"><i class="fa fa-edit"></i> 生成二维码</a></td>
												<td><?php echo $model['title'];?></td>
												<td><?php echo $model['sku_quantity'];?> <a href="javascript:;" class="btn btn-xs blue change-sku" data-id="<?php echo $model['lid'];?>"><i class="fa fa-pencil"></i></a></td>
												<td><?php if($model['date_info_type']==1){ echo date('Y-m-d',$model['begin_timestamp']).'至'.date('Y-m-d',$model['end_timestamp']);}else{ echo '领取后'; echo $model['fixed_begin_term'] >0 ?$model['fixed_begin_term']:'当天'; echo '生效'.$model['fixed_term'].'天有效';}?></td>
												<td><?php if($model['status']==0){ echo '审核中';}elseif($model['status']==1){echo '审核通过';}elseif($model['status']==2){echo '审核失败';}?></td>
												<td class="button-column">
												<!-- 
													<a href="<?php echo $this->createUrl('/admin/wxcard/detail',array('companyId'=>$this->companyId,'id'=>$model['lid']));?>" class="btn default btn-xs green-stripe">详情</a>
												 -->	<a href="<?php echo $this->createUrl('/admin/wxcard/cardUser',array('companyId'=>$this->companyId,'id'=>$model['lid']));?>" class="btn default btn-xs blue-stripe">统计</a>
													<a class="btn default btn-xs red btn_deleteGift" title="删除" cardid="<?php echo $model['lid'];?>" href="javascript:;"><i class="fa fa-times"></i> 删 除</a>
												</td>
											</tr>
										<?php endforeach;?>	
										<?php else:?>
											<tr>
												<td colspan="6">没有找到数据</td>
											</tr>
										<?php endif;?>
										</tbody>
									</table>
								</div>
							<?php if($pages->getItemCount()):?>
							<div class="row">
								<div class="col-md-5 col-sm-12">
									<div class="dataTables_info">
										共 <?php echo $pages->getPageCount();?> 页  , <?php echo $pages->getItemCount();?> 条数据 , 当前是第 <?php echo $pages->getCurrentPage()+1;?> 页
									</div>
								</div>
								<div class="col-md-7 col-sm-12">
									<div class="dataTables_paginate paging_bootstrap">
									<?php $this->widget('CLinkPager', array(
										'pages' => $pages,
										'header'=>'',
										'firstPageLabel' => '<<',
										'lastPageLabel' => '>>',
										'firstPageCssClass' => '',
										'lastPageCssClass' => '',
										'maxButtonCount' => 8,
										'nextPageCssClass' => '',
										'previousPageCssClass' => '',
										'prevPageLabel' => '<',
										'nextPageLabel' => '>',
										'selectedPageCssClass' => 'active',
										'internalPageCssClass' => '',
										'hiddenPageCssClass' => 'disabled',
										'htmlOptions'=>array('class'=>'pagination pull-right')
									));
									?>
									</div>
								</div>
							</div>
							<?php endif;?>
							<div class="alert alert-danger">
								<strong> 注1:</strong> 此功能需在微信公众平台处申请微信卡券功能。<br/>
								<strong> 注2:</strong> 使用前请务必阅读微信卡券使用说明，违规操作将被禁用卡券功能。<br/>
							</div>
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
				</div>
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
	 function genQrcode(that){
	 			var baseUrl = '<?php echo $baseUrl.'/';?>';
	 			var id = $(that).attr('cardid');
				var $parent = $(that).parent();
				$.get('<?php echo $this->createUrl('/admin/wxcard/printWeixinCard',array('companyId'=>$this->companyId));?>/id/'+id,function(data){
					if(data.status){
						$parent.find('img').remove();
						$parent.prepend('<img style="width:100px;" src="'+baseUrl+data.qrcode+'">');
					}
					alert(data.msg);
				},'json');
			}
		jQuery(document).ready(function() {       
		    var $modal = $('#ajax-modal');
	        $('.addCard').on('click',function(){
	        	$modal.load('<?php echo $this->createUrl('/admin/wxcard/addCard',array('companyId'=>$this->companyId));?>', '', function(){
                   $modal.modal();
                 });
	        });
	        $('.change-sku').on('click',function(){
	        	var id = $(this).attr('data-id');
	        	$modal.load('<?php echo $this->createUrl('/admin/wxcard/changeSku',array('companyId'=>$this->companyId));?>/id/'+id, '', function(){
                   $modal.modal();
                 });
	        });
            $('.btn_deleteGift').click(function(){
            	var cardid = $(this).attr('cardid');
                bootbox.confirm("你确定要删除该宝贝吗?", function(result) {
                   if(result){
                       location.href="<?php echo $this->createUrl('/admin/wxcard/delete',array('companyId'=>$this->companyId));?>/id/"+cardid;
                   }
                }); 
            });
		});
	</script>