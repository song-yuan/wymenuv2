
<style>
.radio-inline div{padding-top:0!important;}
</style>



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
	<?php if($type==1):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','区域内店铺列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('areaGroup/index' , array('companyId' => $this->companyId)))));?>
	<?php elseif($type==2):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','区域内仓库列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('areaGroup/index' , array('companyId' => $this->companyId)))));?>
	<?php endif;?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->


	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'price-group-detail-form',
				'action' => $this->createUrl('priceGroup/detailindex' , array('companyId' => $this->companyId,'areagroupid'=>$areagroupid,)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
	)); ?>
	<div class="col-md-12">
		<div class="tabbable tabbable-custom">

			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption" style="color:white;">
						<a href="<?php echo $this->createUrl('areaGroup/detailindex',array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid,'type'=>1,));?>">
						<span class="tab <?php if($type==1){ echo 'tab-active';}?>" style="<?php if($type==1){ echo 'color:white!important;';}else{ echo 'color:orange!important;';}?>" ><?php echo yii::t('app','区域内店铺列表');?></span>
						</a>
						<a href="<?php echo $this->createUrl('areaGroup/detailindex',array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid,'type'=>2,));?>">
						<span class="tab <?php if($type==2){ echo 'tab-active';}?>"  style="<?php if($type==2){ echo 'color:white!important;';}else{ echo 'color:orange!important;';}?>" ><?php echo yii::t('app',' 区域内仓库列表');?></span>
						</a></div>
					<div class="actions">
						<div class="btn-group" style="left:3%;">
							<a href="<?php echo $this->createUrl('areaGroup/add',array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid,'type'=>$type))?>" class="btn blue"><i class="fa fa-pencil"></i> &nbsp;<?php echo yii::t('app','添加');?></a>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<caption> 
						<h3 style="margin-top:0px;">
							<b> 
								<?php echo $groupname; ?>
									<?php if($type==1):?>
									<?php echo yii::t('app','店铺');?>
									<?php elseif($type==2):?>
									<?php echo yii::t('app','仓库');?>
									<?php endif;?>
							</b>
						</h3>
						</caption>
						<?php if($models) :  ?>
						<thead>
							<tr>
								<th class="table-checkbox" ><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th style="width:10%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','联系人');?></th>
								<th ><?php echo yii::t('app','电话');?></th>
								<th ><?php echo yii::t('app','地址');?></th>
								<?php if($type==2):?>
								<th ><?php echo yii::t('app','默认仓库');?></th>
								<?php endif; ?>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php ?>" name="ids[]" /></td>
								<td ><img style="width: 100px;" src="<?php echo $model['logo'];?>" alt=""></td>
								<td ><?php echo $model['company_name'];?></td>
								<td ><?php echo $model['contact_name'];?></td>
								<td ><?php echo $model['mobile'];?></td>
								<td ><?php echo $model['address'];?></td>
								<?php if($type==2):?>
								<td>
								<?php if ($model['is_selected']){ echo '<span style="color:red;">默认仓库</span>';}else{ echo '<span style="color:green;">普通仓库</span>';}?>
								</td>
								<?php endif; ?>
								<td style="width:20%">
									<div class="row" style="padding-left:10px;">
	                                    <?php if($type==2):?>
										<a href="<?php echo $this->createUrl('areaGroup/default' , array('companyId' => $this->companyId ,'lid'=>$model['lid'],'type'=>$type,'areagroupid'=>$areagroupid,'is_selected'=>1 ));?>" class="btn yellow" ><?php echo yii::t('app','设置默认仓库');?></a>
										<?php endif; ?>
	                                    <a href="<?php echo $this->createUrl('areaGroup/delete_detail' , array('companyId' => $this->companyId ,'lid'=>$model['lid'],'type'=>$type,'areagroupid'=>$areagroupid));?>" class="btn red" ><i class="fa fa-times"></i> &nbsp;<?php echo yii::t('app','删除');?></a> 
									</div>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else: ?>
							<tr>
								<td>请点击右上角添加 , 进行添加操作</td>
							</tr>
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
	<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
</div>

	<script type="text/javascript">
		$('.saved').click(function(){
			var lid = $(this).attr('lid');
			var num = $(this).attr('num');
			var price =$('#price'+num).val();
			var mb_price =$('#mb_price'+num).val();
			var ist =$('#ist'+num).val();
			var pid =$('#pid'+num).val();
			location.href="<?php echo $this->createUrl('priceGroup/saved',array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid));?>/lid/"+lid+"/price/"+price+"/mb_price/"+mb_price+"/ist/"+ist+"/pid/"+pid;
		});
	</script>