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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','商城配置'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','原料上架'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('tmall/list' , array('companyId' => $this->companyId,'type' =>'0',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
				'action' => $this->createUrl('tmall/delete' , array('companyId' => $this->companyId,'papage'=>$pages->getCurrentPage()+1)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','原料上架');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox"  class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','原料编号');?></th>
								<th ><?php echo yii::t('app','原料名称');?></th>
								<th ><?php echo yii::t('app','类型');?></th>
								<th><?php echo yii::t('app','店内码');?></th>
								<th><?php echo yii::t('app','库存单位');?></th>
								<th><?php echo yii::t('app','零售单位');?></th>
								
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
                                               
						<?php foreach ($models as $model):?>
                                                  
                                                    <?php  $is_used =0;
                                                           foreach ($model->bom as $each_material){
                                                               if($each_material->material_id == $model->lid){
                                                                   $is_used =1;
                                                                   break;
                                                               }
                                                           }
                                                    ?>        
							<tr class="odd gradeX">
								<td><input  data-used ="<?php echo $is_used; ?>" data-name ="<?php echo $model->material_name;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="ids[]" /></td>
								<td><?php echo $model->material_identifier;?></td>
								<td ><?php echo $model->material_name;?></td>
								<td><?php if(!empty($model->category->category_name)) echo $model->category->category_name;?></td>
								<td ><?php echo $model->material_private_identifier;?></td>
								<td ><?php echo Common::getStockName($model->stock_unit_id);?></td>
								<td ><?php echo Common::getStockName($model->sales_unit_id);?></td>
								<!-- <td ><php echo isset($model->material_stock)?$model->material_stock->stock:0;?></td>  -->
								<!-- <td ><?php echo ProductMaterial::getJitStock($model->lid,$model->dpid);?></td> -->
								<!--<td ><php echo $model->stock_cost;?></td>-->
								
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
    $('#material-form').submit(function(){
        if(!$('.checkboxes:checked').length){
                alert("<?php echo yii::t('app','请选择要删除的项');?>");
                return false;
        }
        else{
                var material_used_list ='';
                var materials_string = '';
                $('.checkboxes:checked').each(function(){
                   materials_string += $(this).attr("data-name")+",";
                   if($(this).attr("data-used")==1){
                       material_used_list += $(this).attr("data-name")+",";
                   }
                });
                var is_del = confirm("是否确认删除原料: "+materials_string);
                if( is_del ){
                    if( material_used_list != '' ){ 
                        is_del = confirm("原料: "+material_used_list+"在产品配方中存在,确认删除将同时删除产品配方中的原料。是否确认删除？");
                        if( is_del ){
                            return true;
                        }else{ 
                            return false;
                            }
                    }else{
                            return true;
                        }
                }else{
                       return false;
                   }
            }
        });

    
    $('#selectCategory').change(function(){
            var cid = $(this).val();
            location.href="<?php echo $this->createUrl('tmall/materialindex' , array('companyId'=>$this->companyId));?>/cid/"+cid;
    });
});
	</script>	