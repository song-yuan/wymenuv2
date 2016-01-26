    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
    <script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
   <!-- BEGIN PAGE -->
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','交接班管理'),'breadcrumbs'=>array(array('word'=>yii::t('app','交接班'),'url'=>''))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','交接班');?></div>
				<div class="actions">
                                    开始时间：
                                    <select id="begintimelist" class="btn yellow" >
                                        <?php if(!empty($logintime)):
                                            foreach ($logintime as $lt):
                                        ?>
                                        <option value="<?php echo $lt["create_at"];?>" <?php if($lt["create_at"]==$begin_time) {echo 'selected="selected"';} ?> ><?php echo $lt["create_at"];?></option>
					<?php  endforeach;
                                            endif;
                                        ?>
                                    </select>
                                    结束时间：<span><?php echo $end_time; ?></span> 
				</div>
                                
			 </div> 
			
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
					<!--	<thead>
							<tr>
								
								<th><?php echo yii::t('app','店铺');?></th>
								<th><?php echo yii::t('app','售出订单数');?></th>
                                <th><?php echo yii::t('app','会员充值金额');?></th>
                                <th><?php echo yii::t('app','现金结账');?></th>
                                <th><?php echo yii::t('app','会员卡结账');?></th>                                                             
                                <th><?php echo yii::t('app','微信结账');?></th>
								<th><?php echo yii::t('app','支付宝结账');?></th>	
								<th><?php echo yii::t('app','总销售额');?></th>
								<th><?php echo yii::t('app','操作员');?></th>		
							</tr>
						</thead>-->
						<tbody>
                                                    <tr>
                                                        <th>店铺</th>
                                                        <td><?php echo Company::getCompanyName($this->companyId); ?></td>
                                                        <th>操作员</th>
                                                        <td><?php echo Yii::app()->user->name; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>订单数</th>
                                                        <td><?php echo $order_number; ?></td>
                                                        <th>订单总金收入</th>
                                                        <td><?php echo $order_money; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>会员卡充值</th>
                                                        <td><?php echo $member_charge; ?></td>
                                                        <th>会员卡消费</th>
                                                        <td><?php echo $member_consume; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <th>现金总额</th>
                                                        <td><?php echo $cash_total; ?></td>
                                                        <th>银联卡总额</th>
                                                        <td><?php echo $union_total; ?></td>
                                                    </tr>                                                    
						</tbody>
					</table>
					<div class="form-actions fluid">
										<div class="col-md-offset-3 col-md-9">
											<a id="shiftandout" class="btn red">交接班并退出</a>
                                                                                        <a style="margin-left: 20px;" href="<?php echo $this->createUrl('login/logout');?>" class="btn red">直接退出</a>
											<a style="margin-left: 20px;" href="<?php echo $this->createUrl('default/index', array('companyId' => $this->companyId));?>" class="btn default">返回</a>                              
										</div>
									</div>
						
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
	
	</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>
 var url=window.location.href;
 //alert(url+"/save/1");
$("#begintimelist").change(function(){
    //alert($(this).val());
    location.href='<?php echo $this->createUrl('default/shiftlogout',array("companyId"=>$this->companyId));?>/begin_time/'+$(this).val();
});

$("#shiftandout").on("click",function(){
    //alert($(this).val());
    location.href=url+"/save/1";
});
</script> 
