<style>
	.shangjia{
		background-color: #00ffad;
		color: #fff;
		font-weight:600;
		border-radius: 5px;
	}
	.xiajia{
		background-color: #e02222;
		color: #fff;
		font-weight:600;
		border-radius: 5px;
	}
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
		</div>
	</div>
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','原料商城广告列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId' => $this->companyId,'type' => '0',)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'materialAd-form',
				'action' => $this->createUrl('materialAd/delete' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','原料商城广告列表');?></div>
					<div class="actions">
						<div class="btn-group">
						<a href="<?php echo $this->createUrl('materialAd/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						</div>
						<div class="btn-group">
							<button type="button" id="deleteprod"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<span style="color: red;font-size:1.5em;">为了更好的用户体验,建议显示的广告(图片)不要超过5张</span>
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                <th><?php echo yii::t('app','排序号');?></th>
                                <th style="width:16%"><?php echo yii::t('app','标题');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','创建时间');?></th>
								<th><?php echo yii::t('app','更新时间');?></th>
								<th><?php echo yii::t('app','显示状态');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td>
									<?php if( Yii::app()->user->role >=11):?>
									<?php else:?>
									<input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" />
									<?php endif;?>
								</td>
								 <td ><?php echo $model->sort;?></td>
                                 <td style="width:16%"><?php echo $model->name;?></td>
								<td ><img width="100" src="<?php echo $model->main_picture;?>" /></td>
								<td ><?php echo $model->create_at;?></td>
								<td ><?php echo $model->update_at;?></td>
								<td >
									<span class="aa btn <?php echo $model->is_show?'green':'red'; ?>" lid ="<?php echo $model->lid;?>"  ><?php echo $model->is_show?'显示':'隐藏';?></span>
								</td>

								<td class="center">
								<a href="<?php echo $this->createUrl('materialAd/update',array('companyId' => $this->companyId ,'id' => $model->lid , 'papage' => $pages->getCurrentPage()+1 ));?>"><?php echo yii::t('app','编辑');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?> , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
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
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		$(document).keydown(function(event){
		  switch(event.keyCode){
		     case 13:return false;
		     }
		});
		$('#materialAd-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});


		$('.aa').on('click',function(){
			var lid = $(this).attr('lid');
			var str = $(this).html();
			var is_show ='';
			// alert(str);
			if(str == "显示"){
				is_show=0;
			}else if(str == "隐藏"){
				is_show=1;
			}
			$(this).attr('id', 'dd');
			if(window.confirm("确认进行此项操作?")){
				$.ajax({
		            type:'GET',
					url:"<?php echo $this->createUrl('materialAd/show',array('companyId'=>$this->companyId,));?>/lid/"+lid+"/is_show/"+is_show,
					async: false,
		            cache:false,
		            dataType:'json',
					success:function(data){
			            if(data==1)
			            {
				            layer.msg('状态更改成功!!!');
							if(str == "显示"){
								$('#dd').removeClass('green');
								$('#dd').addClass('red');
								$('#dd').html('');
								$('#dd').html('隐藏');
								$('#dd').removeAttr('id');
							}else if(str == "隐藏"){
								$('#dd').removeClass('red');
								$('#dd').addClass('green');
								$('#dd').html('');
								$('#dd').html('显示');
								$('#dd').removeAttr('id');
							}
			            }else{
				            alert("<?php echo yii::t('app','失败'); ?>"+"1");
				            location.reload();
			            }
					},
		            error:function(){
						alert("<?php echo yii::t('app','失败'); ?>"+"2");
					},
				});
			}
		})

		$('#deleteprod').on('click',function(){
			if(window.confirm("确认删除勾选菜品?")){
				$('#materialAd-form').submit();
			}
		})
	});
	</script>