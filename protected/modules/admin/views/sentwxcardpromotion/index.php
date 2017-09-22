	<link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-1.8.17.custom.css');?>" rel="stylesheet" />
    <link type="text/css" href="<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/jquery-ui-timepicker-addon.css');?>" rel="stylesheet" />
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.7.1.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-1.8.17.custom.min.js');?>"></script>
	<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-addon.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-ui-timepicker-zh-CN.js');?>"></script>

<style>
	.modal-dialog{
		width: 80%;
		height: 70%;
	}
	.wxcardbg{
		width: 220px;
		height: 115px;
		margin-top: 10px;
		margin-left: 15px;
		border:1px solid red;
		border-radius: 5px;
		float: left;
		color: red;
		background-color: #ff00bc;
		position: relative;
	}
	.wxcardhead{
		width: 100%;
		height: 85px;
		font-size: 22px;
	}
	.wxcardheadl{
		width: 50%;
		height: 85px;
		float: left;
		border-right: 1px dashed white;
	}
	.wxcardheadll{
		width: 90px;
		height: 75px;
		margin-top: 5px;
		marin-left: 5px;
	}
	.wxcardheadll .money{
		width: 75px;
		height: 75px;
		line-height: 75px;
		float: left;
		font-size: 40px;
		font-weight: 900;
		color: white;
		text-align: center;
	}
	.wxcardheadll .unit{
		width: 15px;
		height: 75px;
		line-height: 100px;
		font-size: 22px;
		float: left;
		color: black;
	}
	.wxcardheadr{
		width: 48%;
		height: 85px;
		float: left;
	}
	.wxcardheadr .top{
		width: 100%;height: 30px;line-height: 30px;font-size: 16px;text-align: center;color: #000;
	}
	.wxcardheadr .cen{
		width: 100%;
		height: 20px;
		line-height: 20px;
		font-size: 16px;
		text-align: center;
		color: #000;
		display: none;
	}
	.wxcardheadr .bot{
		width: 100%;
		height: 35px;
		line-height: 35px;
		font-size: 18px;
		text-align: center;
		color: #000;
		font-weight: 600;
	}
	.wxcardend{
		width: 100%;
		height: 30px;
		line-height: 30px;
		font-size: 12px;
		border-top: 1px dashed pink;
		text-align: center;
		color: #fff;
	}
	.wxcardactive{
		position: absolute;
		top: 30px;
		left: 80px;
	}
	.addsave{
		float: right;
		margin: 10px 10px 0px 0px;
	}
	.addsave button{
		font-size: 18px;
		padding: 4px 10px;
		border-radius: 5px;
		background-color: #6beaff;
	}
	.uhide{
		display: none;
	}
	.glyphicon {
	    top: 2px;
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
		<!-- END BEGIN STYLE CUSTOMIZER -->
		<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','微信赠券'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','开卡赠券'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('wechatMarket/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
		<!-- END PAGE HEADER-->
		<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
			'id' => 'sentwxcardpromotion-form',
			'action'=>$this->createUrl('sentwxcardpromotion/delete',array('companyId'=>$this->companyId)),
			'errorMessageCssClass' => 'help-block',
			'htmlOptions' => array(
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			),
		)); ?>
	<div class="col-md-12">
		<div class="tabbable tabbable-custom">
			<div class="tab-content">
				<!-- BEGIN EXAMPLE TABLE PORTLET-->
				<div class="portlet box purple">
					<div class="portlet-title">
						<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','开卡赠券列表');?></div>
						<div class="actions">
							<div class="btn-group">
								<a href=" <?php echo $this->createUrl('sentwxcardpromotion/create',array('companyId'=>$this->companyId)) ?>" class="staclose btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>
							</div>
							<div class="btn-group">
								<div class="btn red" id="submit"><i class="glyphicon glyphicon-remove"></i>  删除</div>
							</div>
						</div>
					</div>
					<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th><?php echo yii::t('app','标题');?></th>
                                <th><?php echo yii::t('app','摘要');?></th>
								<th><?php echo yii::t('app','发送信息');?></th>
								<th><?php echo yii::t('app','开始时间');?></th>
								<th><?php echo yii::t('app','结束时间');?></th>
								<th><?php echo yii::t('app','操作');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" name="lid[]" /></td>
								<td><?php echo $model->promotion_title;?></td>
                                <td><?php echo $model->promotion_abstract;?></td>
								<td ><?php echo $model->promotion_message;?></td>
								<td ><?php echo $model->begin_time;?></td>
								<td ><?php echo $model->end_time;?></td>
								<td class="center"><a href=" <?php echo $this->createUrl('sentwxcardpromotion/update',array('companyId'=>$this->companyId,'lid'=>$model->lid)) ?>" class="btn blue">编辑</a>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
					</table>
					</div>
				</div>
				<!-- END EXAMPLE TABLE PORTLET-->
	        </div>
        </div>
    </div>
	<?php $this->endWidget(); ?>
	</div>

</div>

 <script type="text/javascript">
	$(document).ready(function(){

		$(".ui_timepicker").datetimepicker({
		    //showOn: "button",
		    //buttonImage: "./css/images/icon_calendar.gif",
		    //buttonImageOnly: true,
		    showSecond: true,
		    timeFormat: 'hh:mm:ss',
		    stepHour: 1,
		    stepMinute: 1,
		    stepSecond: 1
		});
		$('#submit').on('click',function(){
			var ischecked = false;
			if($('.checkboxes:checked').length){
				ischecked = true;
			}
			if (ischecked) {
				$('form').submit();
			} else{
				layer.msg('请选择要删除的活动项!!!');
				return false;
			}
		});
		$('.wxcardbg').on('click',function(){
			if($(this).hasClass('activechecked')){
				$(this).removeClass('activechecked');
				$(this).find('.wxcardactive').addClass('uhide');
			}else{
				$(this).find('.wxcardactive').removeClass('uhide');
				$(this).addClass('activechecked');
			}
		});
	    $('.staclose').on('click' , function(){
		    //is_new 为1时，表示该店铺没有建立生日赠券活动；2时表示开启生日赠券活动；3表示关闭生日赠券活动。
		    if($(this).hasClass('close1')){
		    	location.href="<?php echo $this->createUrl('sentwxcardpromotion/index' , array('companyId'=>$this->companyId));?>/is_new/3";
			 }else if($(this).hasClass('start1')){
			    location.href="<?php echo $this->createUrl('sentwxcardpromotion/index' , array('companyId'=>$this->companyId));?>/is_new/1";
			 }else if($(this).hasClass('start2')){
			    location.href="<?php echo $this->createUrl('sentwxcardpromotion/index' , array('companyId'=>$this->companyId));?>/is_new/2";
			 }
		});

		function swfupload_callback(name,path,oldname)  {
			$("#SentwxcardPromotion_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />");
		}
	});
</script>