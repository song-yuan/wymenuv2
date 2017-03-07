
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
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','卡魔方'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->

	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','会员列表');?></div>
					<div class="actions">
					<?php if(Yii::app()->user->role<9):?>
						
						<a href="<?php echo $this->createUrl('member/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添 加');?></a>
					<?php endif;?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','会员卡号');?></th>
								<th><?php echo yii::t('app','姓名');?></th>
								<th><?php echo yii::t('app','性别');?></th>
								<th><?php echo yii::t('app','生日');?></th>
								<th><?php echo yii::t('app','联系方式');?></th>
								<th><?php echo yii::t('app','金额');?></th>
								<th><?php echo yii::t('app','积分');?></th>
								<th><?php echo yii::t('app','状态');?></th>
								<th><?php echo yii::t('app','折扣（生日折扣）');?></th>
								
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models):?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td ><?php echo $model->selfcode;?></td>
								<td ><?php echo $model->name;?></td>
								<td ><?php if($model->sex=='m') echo '男';else echo '女';?></td>
								<td ><?php echo $model->birthday;?></td>
								<td ><?php echo $model->mobile;?></td>
								<td ><?php echo $model->all_money;?></td>
								<td ><?php echo $model->all_points;?></td>
								<td ><?php switch($model->card_status){case 0:echo '正常';break;case 1: echo "挂失";break;case 2: echo '注销';break;default:echo '';break;}?></td>
								<td ><?php echo sprintf("%.2f",$model->brandUserLevel->level_discount).'('.sprintf("%.2f",$model->brandUserLevel->birthday_discount).')';?></td>
								
								<td class="center">
<!--									<a href="<?php echo $this->createUrl('member/chargeRecord',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','充值记录');?></a>&nbsp;
									<a href="<?php echo $this->createUrl('member/consumersRecord',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','消费记录');?></a>&nbsp;
									<a href="<?php echo $this->createUrl('member/pointsRecord',array('rfid' => $model->rfid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','积分记录');?></a>&nbsp;-->
									<?php if(Yii::app()->user->role <= User::SHOPKEEPER):?>
									<a href="<?php echo $this->createUrl('member/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>&nbsp;
                                    <?php endif;?>
                                    <!-- <a class="deletememberid" data-id="<?php echo $model->lid;?>" href="javascript:;"><?php echo yii::t('app','删除');?></a> -->
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<td colspan="10">没有找到数据</td>
						<?php endif;?>
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
	<script>
	jQuery(document).ready(function(){
            var $modal = $('.modal');
            $('.add_btn').on('click', function(){
                     
	    	pid = $(this).attr('pid');
	        $modal.find('.modal-content').load('<?php echo $this->createUrl('member/charge' , array('companyId' => $this->companyId));?>', '', function(){
	          $modal.modal();
	        });
            });
	});
        $(".deletememberid").on("click",function(){
               var id = $(this).attr('data-id');
               msg ='确定要删除该会员吗?';
	       	   bootbox.confirm(msg, function(result) {
                   if(result){
                       location.href="<?php echo $this->createUrl('member/delete',array('companyId' => $this->companyId));?>/id/"+id;
                   }
                }); 
        });
	</script>