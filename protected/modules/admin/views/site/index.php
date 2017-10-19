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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','餐桌设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','座位管理'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' => '1',)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
			<div class="row">
			<?php $form=$this->beginWidget('CActiveForm', array(
						'id' => 'site-form',
						'action' => $this->createUrl('site/delete' , array('companyId' => $this->companyId)),
						'errorMessageCssClass' => 'help-block',
						'htmlOptions' => array(
							'class' => 'form-horizontal',
							'enctype' => 'multipart/form-data'
						),
				)); ?>
				<div class="col-md-12">
				<?php if($siteTypes):?>
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
						<?php foreach ($siteTypes as $key=>$siteType):?>
							<li class="<?php if($key == $typeId) echo 'active';?>"><a href="#tab_1_<?php echo $key;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('site/index' , array('typeId'=>$key , 'companyId'=>$this->companyId));?>'"><?php echo $siteType ;?></a></li>
						<?php endforeach;?>	
						</ul>
						<div class="tab-content">
							<div class="portlet box purple">
								<div class="portlet-title">
									<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','座位列表');?></div>
									<div class="actions">
										<a href="<?php echo $this->createUrl('site/create' , array('typeId'=>$typeId , 'companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
										<div class="btn-group">
											<button type="submit"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
										</div>
									</div>
								</div>
								<div class="portlet-body" id="table-manage">
								<div class="dataTables_wrapper form-inline">
									<div class="table-responsive">
									<table class="table table-striped table-bordered table-hover" id="sample_1">
										<thead>
											<tr>
												<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
												<th><?php echo yii::t('app','二维码');?></th>
												<th><?php echo yii::t('app','座位号');?></th>
												<th><?php echo yii::t('app','类型');?></th>
												<th><?php echo yii::t('app','渠道');?></th>
												<th><?php echo yii::t('app','楼层');?></th>
												<th><?php echo yii::t('app','等级');?></th>
												<th><?php echo yii::t('app','人数');?></th>
												<th>&nbsp;</th>
											</tr>
										</thead>
										<tbody>
										<?php if(!empty($models)):?>
										<?php foreach ($models as $model):?>
											<tr class="odd gradeX">
												<td><input type="checkbox" class="checkboxes"  value="<?php echo $model->lid;?>" name="ids[]" /></td>
												<td ><?php if($model->qrcode):?><img style="width:100px;" src="<?php echo '/wymenuv2/./'.$model->qrcode;?>" /><?php endif;?><br /><a class="btn btn-xs blue" onclick="genQrcode(this);" href="javascript:;" lid="<?php echo $model->lid;?>"><i class="fa fa-qrcode"></i> 生成二维码</a></td>
												<td ><?php echo $model->serial ;?></td>
												<td ><?php echo $model->siteType->name ;?></td>
												<td ><?php if(!empty($model->channel->channel_name)) echo $model->channel->channel_name;else echo yii::t('app',"堂食") ; ?></td>
												<td ><?php if(!empty($model->floor->name)) echo $model->floor->name;?></td>
												<td><?php echo $model->site_level;?></td>
												<td ><?php if(!empty($model->sitePersons)) echo $model->sitePersons->min_persons."_".$model->sitePersons->max_persons;?></td>
												<td class="center">
												<a href="<?php echo $this->createUrl('site/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
												</td>
											</tr>
										<?php endforeach;?>
										<?php endif;?>
										</tbody>
									</table>
									</div>
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
							</div>
							<!-- END EXAMPLE TABLE PORTLET-->												
						</div>
					</div>
				<?php endif;?>
			</div>
		</div>
		<?php $this->endWidget(); ?>	
</div>
	<script type="text/javascript">
	function genQrcode(that){
		id = $(that).attr('lid');
		var $parent = $(that).parent();
		$.get('<?php echo $this->createUrl('/admin/site/genWxQrcode',array('companyId'=>$this->companyId));?>/id/'+id,function(data){
			if(data.status){
				$parent.find('img').remove();
				$parent.prepend('<img style="width:100px;" src="/wymenuv2/./'+data.qrcode+'">');
			}
			alert(data.msg);
		},'json');
	}
	$(document).ready(function(){
		$('#site-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
	});
	</script>