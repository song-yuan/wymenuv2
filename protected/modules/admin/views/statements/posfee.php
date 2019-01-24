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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','收款机结算'),'subhead'=>yii::t('app','年费收取'),'breadcrumbs'=>array(array('word'=>yii::t('app','报表中心'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','年费延期'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId)))));?>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'posfee-form',
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','续费报表');?></div>
					<div class="actions">
						<div class="btn-group">
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control" name="begintime" readonly="readonly" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begintime;?>">  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control" name="endtime" readonly="readonly" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $endtime;?>">           
						  </div> 
			            </div>
			            <div class="btn-group">
							<button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
							<button type="button" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>
					    </div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','序号');?></th>
								<th><?php echo yii::t('app','店名');?></th>
								<th><?php echo yii::t('app','序列号');?></th>
								<th><?php echo yii::t('app','续费日期');?></th>
								<th><?php echo yii::t('app','续费类型');?></th>
								<th><?php echo yii::t('app','续费时间');?></th>
								<th><?php echo yii::t('app','到期时间');?></th>
								<th><?php echo yii::t('app','续费金额');?></th>
								<th><?php echo yii::t('app','续费方式');?></th>
								<th><?php echo yii::t('app','联系人');?></th>
								<th><?php echo yii::t('app','联系电话');?></th>
								<th><?php echo yii::t('app','联系地址');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $key=>$model):?>
							<tr class="odd gradeX">
								<td ><?php echo $key+1;?></td>
								<td ><?php echo $model['company_name'];?></td>
								<td><?php echo $model['poscode'];?></td>
								<td><?php echo $model['create_at'];?></td>
								<td><?php if($model['type']==1){echo '年';}else{ echo '月';}?></td>
								<td><?php echo $model['add_time'];?></td>
								<td><?php echo $model['expire_time'];?></td>
								<td><?php echo $model['price'];?></td>
								<td><?php if($model['add_type']==0){echo '后台续费';}else{echo '扫码续费';};?></td>
								<td><?php echo $model['contact_name'];?></td>
								<td><?php echo $model['mobile'];?></td>
								<td><?php echo $model['province'].$model['city'].$model['county_area'].$model['address'];?></td>
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
	});
	$(document).ready(function(){
		$('#excel').click(function(){
			var begin_time = $('#begin_time').val();
		   	var end_time = $('#end_time').val();
	       	if(confirm('确认导出并且下载Excel文件吗？')){
	    	   location.href = "<?php echo $this->createUrl('statements/posfee' , array('companyId'=>$this->companyId));?>/begintime/"+begin_time+"/endtime/"+end_time+"/d/1";
	       	}
		});
	});
	</script>	