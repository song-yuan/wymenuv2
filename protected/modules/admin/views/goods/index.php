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
			<!-- /.modal-content -->
		</div>
		<!-- /.modal-dialog -->
	</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','仓库设置'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','商品列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('tmall/list' , array('companyId' => $this->companyId,'type' => '0',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'goods-form',
				'action' => $this->createUrl('goods/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','商品列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
						<a href="<?php echo $this->createUrl('goods/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
						<div class="btn-group">
							<button type="button" id="deleteprod"  class="btn red" ><i class="fa fa-ban"></i> <?php echo yii::t('app','删除');?></button>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
                                <th><?php echo yii::t('app','排序号');?></th>
                                <th style="width:16%"><?php echo yii::t('app','名称');?></th>
								<th ><?php echo yii::t('app','图片');?></th>
								<th><?php echo yii::t('app','类别');?></th>
								<th><?php echo yii::t('app','现价');?></th>
								<th><?php echo yii::t('app','会员价');?></th>
                                <th><?php echo yii::t('app','单位');?></th>
                                <th><?php echo yii::t('app','会员折扣');?></th>
								<th><?php echo yii::t('app','可折');?></th>
                                <th><?php echo yii::t('app','可售');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php if(Yii::app()->user->role >11):?><?php else:?><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /><?php endif;?></td>
								<td ><?php echo $model['sort'];?></td>
                                <td style="width:16%"><?php echo $model['goods_name'];?></td>
								<td ><img width="100" src="<?php echo $model['main_picture'];?>" /></td>
								<td style="width:16%"><?php echo $model['category_name'];?></td>
								<td ><?php echo $model['original_price'];?></td>
								<td ><?php echo $model['member_price'];?></td>
                                <td ><?php echo $model['goods_unit'];?></td>
                                <td ><?php echo $model['is_member_discount']=='0'?yii::t('app','否'):yii::t('app','是');?></td>
								<td ><?php echo $model['is_discount']=='0'?yii::t('app','否'):yii::t('app','是');?></td>
                                <td ><?php echo $model['is_show']=='0'?yii::t('app','否'):yii::t('app','是');?></td>
                                                                
								<td class="center">
								<a href="<?php echo $this->createUrl('goods/update',array('id' => $model['lid'] , 'companyId' => $model['dpid'] , 'islock' => $model['is_lock'] ,'papage' => $pages->getCurrentPage()+1 ));?>"><?php echo yii::t('app','编辑');?></a>
								
								<?php if(yii::app()->user->role >=9):?>
									<?php if($model['is_show'] <=5):?>
									<button type="button" class = "on_off_sell xiajia" pid = "<?php echo $model['lid'];?>" pcode = "<?php echo $model['goods_code'];?>" showtype = "0" shownum = "7" >自下架</button>
									<?php elseif($model['is_show'] ==6):?>
									<button type="button" class = "on_off_sell" pid = "<?php echo $model['lid'];?>" disabled style="background-color: #cdd4d2;color: #fff;font-weight:600;">无法上架</button>
									<?php elseif($model['is_show'] ==7):?>
									<button type="button" class = "on_off_sell shangjia" pid = "<?php echo $model['lid'];?>" pcode = "<?php echo $model['goods_code'];?>" showtype = "0" shownum = "1" >自上架</button>
									<?php endif;?>
								<?php else:?>
									<?php if($model['is_show'] <=5):?>
									<button type="button" class = "on_off_sell xiajia" pid = "<?php echo $model['lid'];?>" pcode = "<?php echo $model['goods_code'];?>" showtype = "0" shownum = "7">自下架</button>
									<?php else:?>
									<button type="button" class = "on_off_sell shangjia" pid = "<?php echo $model['lid'];?>" pcode = "<?php echo $model['goods_code'];?>" showtype = "0" shownum = "1" >自上架</button>
									<?php endif;?>
									<button type="button" class = "on_off_sell shangjia" pid = "<?php echo $model['lid'];?>" pcode = "<?php echo $model['goods_code'];?>" showtype = "1" shownum = "1" >统一上架</button>
									<button type="button" class = "on_off_sell xiajia" pid = "<?php echo $model['lid'];?>" pcode = "<?php echo $model['goods_code'];?>" showtype = "1" shownum = "6" style="">统一下架</button>
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
		$('#product-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
		
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('goods/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
		$('.on_off_sell').on('click',function(){
			var pid = $(this).attr('pid');
			var showtype = $(this).attr('showtype');
			var shownum = $(this).attr('shownum');
			var pcode = $(this).attr('pcode');
			if(pid!=''&&showtype!=''&&shownum!=''&&pcode!=''){
				var istrue = 1;
			}else{
				var istrue = 0;
			}
			if(window.confirm("确认进行此项操作?")&&istrue){
				$.ajax({
		            type:'GET',
					url:"<?php echo $this->createUrl('goods/store',array('companyId'=>$this->companyId,));?>/pid/"+pid+"/showtype/"+showtype+"/shownum/"+shownum+"/pcode/"+pcode,
					async: false,
		            cache:false,
		            dataType:'json',
					success:function(msg){
			            if(msg.status=="success")
			            {            
				            layer.msg(msg.msg);              
				            location.reload();
			            }else{
				            alert("<?php echo yii::t('app','失败'); ?>"+"1");
				            location.reload();
			            }
					},
		            error:function(){
						alert("<?php echo yii::t('app','失败'); ?>"+"2");                                
					},
				});
			}else{
				alert('该菜品信息有误！无法进行上下架操作！');
			}
		})

		$('.on_off_sellwx').on('click',function(){
			var pid = $(this).attr('pid');
			var shownum = $(this).attr('shownum');
			var pcode = $(this).attr('pcode');
			if(pid!=''&&shownum!=''&&pcode!=''){
				var istrue = 1;
			}else{
				var istrue = 0;
			}
			if(window.confirm("确认进行此项操作?")&&istrue){
				$.ajax({
		            type:'GET',
					url:"<?php echo $this->createUrl('goods/storewx',array('companyId'=>$this->companyId,));?>/pid/"+pid+"/shownum/"+shownum+"/pcode/"+pcode,
					async: false,
		            cache:false,
		            dataType:'json',
					success:function(msg){
			            if(msg.status=="success")
			            {            
				            layer.msg(msg.msg);              
				            location.reload();
			            }else{
				            alert("<?php echo yii::t('app','失败'); ?>"+"1");
				            location.reload();
			            }
					},
		            error:function(){
						alert("<?php echo yii::t('app','失败'); ?>"+"2");                                
					},
				});
			}else{
				alert('该菜品信息有误！无法进行上下架操作！');
			}
		})
		$('#deleteprod').on('click',function(){
			if(window.confirm("确认删除勾选商品?")){
				$('#goods-form').submit();
			}
		})
	});
	</script>	