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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','报表中心'),'subhead'=>yii::t('app','盘损报表'),'breadcrumbs'=>array(array('word'=>yii::t('app','报表中心'),'url'=>$this->createUrl('statementstock/list' , array('companyId'=>$this->companyId,'type'=>1))),array('word'=>yii::t('app','盘损报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statementstock/list' , array('companyId' => $this->companyId,'type' => 1)))));?>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'inventorylog-form',
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','盘损日志');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php $this->widget('application.modules.admin.components.widgets.CompanySelect2', array('companyType'=>$this->comptype,'companyId'=>$this->companyId,'selectCompanyId'=>$selectDpid));?>
						</div>
						<div class="btn-group">
							<select class="form-control" id="pdname" name="reasonid">
								<option class="proname" value="0">请选择</option>
								<?php if($retreats):?>
									<?php foreach ($retreats as $val):?>
									<option class="proname" value="<?php echo $val['lid'];?>" <?php if($reasonid==$val['lid']){ echo 'selected="selected"';}?>><?php echo $val['name'];?></option>
									<?php endforeach;endif;?>
								<?php ?>
							</select>
						</div>
						<div class="btn-group">
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control" name=begintime id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begintime;?>">  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $endtime;?>">           
						  	</div> 
		            	</div>
			            <div class="btn-group">
							<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
					    </div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','品项名称');?></th>
								<th><?php echo yii::t('app','单位规格');?></th>
								<th><?php echo yii::t('app','单位名称');?></th>
								<th><?php echo yii::t('app','盘损库存');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td ><?php echo $model['material_name'];?></td>
								<td><?php echo $model['unit_specifications'];?></td>
								<td><?php echo $model['unit_name'];?></td>
								<td><?php echo $model['inventory_stock'];?></td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					</div>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(function () {
		if (jQuery().datepicker) {
			$('.date-picker').datepicker({
				format: 'yyyy-mm-dd',
				language: 'zh-CN',
				rtl: App.isRTL(),
				autoclose: true
			});
			$('body').removeClass("modal-open");
		}
		$('select[name="selectDpid"]').change(function(){
			var _this = $(this);
			var sdpid = _this.val();
			$.ajax({
					url:'<?php echo $this->createUrl('statementstock/ajaxGetRetreat',array('companyId'=>$this->companyId));?>',
					data:{sdpid:sdpid},
					success:function(data){
						var str = '<option class="proname" value="0">请选择</option>';
						for(var i in data){
							var obj = data[i];
							str += '<option class="proname" value="'+obj.lid+'">'+obj.name+'</option>';
						}
						$('select[name="reasonid"]').html(str);
					},
					dataType:'json'
				});
		});
	});
	</script>	