<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
<style>
.selectedclass{
	font-size: 14px;
	color: #333333;
	height: 34px;
	line-height: 34px;
	padding: 6px 12px;
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
	<div id="main2" name="main2" style="min-width: 500px;min-height:300px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''">
		<div id="content"></div>
	</div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMember/search' , array('companyId' => $this->companyId,)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'company-form',
				'action' => $this->createUrl('wechatMember/dpcreate', array('num'=>$num,'companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','店铺列表');?></div>
					<div class="actions">
					<div class="btn-group">
						<select id="province" name="province" class="selectedclass"></select>
						<select id="city" name="city" class="selectedclass"></select>
						<select id="area" name="area" class="selectedclass"></select>
                    </div>
                        <?php if(Yii::app()->params->master_slave=='m') : ?>
						<?php if(Yii::app()->user->role == User::POWER_ADMIN||Yii::app()->user->role <= User::ADMIN_AREA):?>
							<div class="btn-group">
	                            <button type="submit"  class="btn blue" ><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></button>
	                        </div>
	                        <a href="<?php echo $this->createUrl('WechatMember/dp', array('num'=>$num,'companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-search"></i> <?php echo yii::t('app','查看');?></a>
						<?php endif;?>
						<!-- <div class="btn-group">
							<a class="btn green" href="#" data-toggle="dropdown">
							<i class="fa fa-cogs"></i> Tools
							<i class="fa fa-angle-down"></i>
							</a>
							<ul class="dropdown-menu pull-right">
								<li><a href="#"><i class="fa fa-ban"></i> <?php echo yii::t('app','冻结');?></a></li>
							</ul>
						</div> -->
                                                
                                            <?php endif; ?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th>ID</th>
                                <th><?php echo yii::t('app','店铺名称');?></th>
								<th>logo</th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th><?php echo yii::t('app','手机');?></th>
								<th><?php echo yii::t('app','地址');?></th>
								<th><?php echo yii::t('app','创建时间');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><?php if(Yii::app()->user->role >= User::POWER_ADMIN_VICE && Yii::app()->user->role <= User::ADMIN_AREA&&$model->type=="0"):?><?php else:?><input type="checkbox" class="checkboxes" value="<?php echo $model->dpid;?>" name="companyIds[]" /><?php endif;?></td>
								<td><?php echo $model->dpid;?></td>
                                 <td><?php echo $model->company_name;?></td>
								<td ><img width="100" src="<?php echo $model->logo;?>" /></td>
								<td ><?php echo $model->contact_name;?></td>
								<td ><?php echo $model->mobile;?></td>
								<?php $address = $model->province.$model->city.$model->county_area;?>
								<td ><?php echo $address;?></td>
								<td><?php echo $model->create_at;?></td>
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
            <?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
<script>
	new PCAS("province","city","area","<?php echo $province;?>","<?php echo $city;?>","<?php echo $area;?>");
	$('#cityselect').on('click',function(){
		 var province = $('#province').children('option:selected').val();
         var city = $('#city').children('option:selected').val();
         var area = $('#area').children('option:selected').val();
		//alert(111);
         location.href="<?php echo $this->createUrl('WechatMember/createdp' , array('num'=>$num,'companyId'=>$this->companyId));?>/province/"+province+"/city/"+city+"/area/"+area;
// 		if(province == null || province == 'undefind' || province == ''){
//	       	alert("<?php echo yii::t('app','请填写店铺所处省市信息再进行查询。。。');?>");
// 	       	return false;
// 	    }else{
//	    	location.href="<?php echo $this->createUrl('company/index' , array('companyId'=>$this->companyId));?>/province/"+province+"/city/"+city+"/area/"+area;
// 		}
	});
	$('#province').change(function(){
		changeselect();
	});
	$('#city').change(function(){
		changeselect();
	});
	$('#area').change(function(){
		changeselect();
	});
	 function changeselect(){
		 var province = $('#province').children('option:selected').val();
			var city = $('#city').children('option:selected').val();
	        var area = $('#area').children('option:selected').val();
	        
			location.href="<?php echo $this->createUrl('WechatMember/createdp' , array('num'=>$num,'companyId'=>$this->companyId));?>/province/"+province+"/city/"+city+"/area/"+area;
			 
		 }
	
</script>