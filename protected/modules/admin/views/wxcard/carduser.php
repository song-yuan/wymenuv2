		<?php 
			$baseUrl = Yii::app()->baseUrl;
		?>
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/select2/select2.min.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/select2/select2_metro.css" />
		<script src="<?php echo $baseUrl;?>/plugins/bootbox/bootbox.min.js" type="text/javascript" ></script>
		
		<link href="<?php echo $baseUrl;?>/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
		<!-- END SIDEBAR -->
		<!-- BEGIN PAGE -->
		<div class="page-content">
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'营销中心','subhead'=>'微信卡券列表','breadcrumbs'=>array(array('word'=>'营销中心','url'=>''),array('word'=>'营销品设置','url'=>''),array('word'=>'微信卡券','url'=>'')),'back'=>array('word'=>'返回','url'=>array('wxcard/index','cid'=>$this->companyId))));?>
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
				<div class="col-md-12 col-sm-12">
					<!-- BEGIN EXAMPLE TABLE PORTLET-->
					<div class="portlet box purple">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-gift"></i>卡券领取列表</div>
							<div class="actions">
								<!--<a href="javascript:;" class="btn blue addCard"><i class="fa fa-pencil"></i> 卡券</a>-->
							</div>
						</div>
						<div class="portlet-body">
								<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="sample_3">
										<thead>
											<tr>
												<th width="10%">领取人</th>
												<th width="10%">转赠人</th>
												<th width="10%">是否赠送</th>
												<th width="10%">序列号</th>
												<th width="10%">领取时间</th>
												<th width="10%">领取渠道</th>
												<th width="10%">状态</th>
												<th width="10%">是否删除</th>
											</tr>
										</thead>
										<tbody>
										<?php if($models):?>
										<?php foreach($models as $model):?>
											<tr>
												<td><?php echo $model->brandUser->nickname;?></td>
												<td><?php echo isset($model->fridndUser)?$model->fridndUser->nickname:'';?></td>
												<td><?php if($model['is_giveby_friend']) echo '是';else echo '否';?></td>
												<td><?php  echo $model['user_card_code'];?></td>
												<td><?php  echo $model['create_at'];?></td>
												<td>
												 <?php if($model['outer_id']) echo '页面领取';else echo '扫描二维码';?>
												</td>
												<td>
												 <?php if($model['status']) echo '已使用';else echo '有效卡券';?>
												</td>
												<td>
												 <?php if($model['delete_flag']) echo '已删除';else echo '否';?>
												</td>
											</tr>
										<?php endforeach;?>	
										<?php else:?>
											<tr>
												<td colspan="8">没有找到数据</td>
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
						</div>
					</div>
					<!-- END EXAMPLE TABLE PORTLET-->
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
