<style>
	span.tab{
		color: black;
		border-right:1px dashed white;
		margin-right:10px;
		padding-right:10px;
		display:inline-block;
	}
	span.tab-active{
		color:white;
	}
	.ku-item{
		width:100px;
		height:100px;
		margin-right:20px;
		margin-top:20px;
		margin-left:20px;
		border-radius:5px !important;
		border:2px solid black;
		box-shadow: 5px 5px 5px #888888;
		vertical-align:middle;
	}
	.ku-item-info{
		width:144px;
		font-size:2em;
		color:black;
		text-align:center;
	}
	.ku-purple{
		background-color:#852b99;
	}
	.ku-grey{
		background-color:rgb(68,111,120);
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
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
<!-- /.modal -->
<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
<!-- BEGIN PAGE HEADER-->
<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','指令对应'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId)))));?>
<!-- END PAGE HEADER-->
<!-- BEGIN PAGE CONTENT-->
<div class="row">
	<div class="col-md-12">
		<!-- BEGIN EXAMPLE TABLE PORTLET-->
		<div class="portlet box purple">
			<div class="portlet-title">
				<div class="caption"><i class="fa fa-cogs"></i><a href="<?php echo $this->createUrl('instruct/index',array('companyId'=>$this->companyId));?>"><span class="tab" ><?php echo yii::t('app','指令设置');?></span></a><a href="<?php echo $this->createUrl('instruct/productInstruct',array('companyId'=>$this->companyId));?>"><span class="tab tab-active" ><?php echo yii::t('app','指令对应');?></span></a></div>
			</div>
			<div class="portlet-body tabbable-custom" id="table-manage">
				<ul class="nav nav-tabs">
					<li class=""><a href="<?php echo $this->createUrl('instruct/productInstruct',array('companyId'=>$this->companyId));?>">菜品指令对应</a></li>
					<li class="active"><a href="javascrip:;">口味指令对应</a></li>
				</ul>
				<table class="table table-striped table-bordered table-hover" id="sample_1">
					<thead>
						<tr>
                         	<th class="col-md-3"><?php echo yii::t('app','口味名称');?></th>
                            <th><?php echo yii::t('app','口味指令');?></th>
                            <th class="col-md-1">&nbsp;</th>    	                               
						</tr>
					</thead>
					<tbody>
					<?php if($models):?>
					<?php foreach ($models as $model):?>
						<tr class="odd gradeX">
							<td ><?php echo $model->name;?></td>
							<td>
								<?php 
									$instructName = '';
									foreach($model->productInstruct as $val){
										if(!$val['is_taste']){
											continue;
										}
										$instrucntId = $val['instruction_id'];
										if(isset($instruct['lid-'.$instrucntId])){
											$instructName .= $instruct['lid-'.$instrucntId]['instruct_name'].' ';
										}
									}
									echo $instructName;
								?>
							</td>
							<td class="center">
								<?php if(!empty($model->productInstruct)):?>
								<a href="<?php echo $this->createUrl('instruct/tasteInstructList',array('lid' => (int)$model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>
								<a href="<?php echo $this->createUrl('instruct/updateTasteInstruct',array('lid' => (int)$model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','添加');?></a>
								<?php else:?>
								<a href="<?php echo $this->createUrl('instruct/updateTasteInstruct',array('lid' => (int)$model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','添加');?></a>
								<?php endif;?>
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
<script type="text/javascript">
$(document).ready(function(){
	$('#selectCategory').change(function(){
		var cid = $(this).val();
		location.href="<?php echo $this->createUrl('instruct/productInstruct' , array('companyId'=>$this->companyId));?>/cid/"+cid;
	});
});
</script>