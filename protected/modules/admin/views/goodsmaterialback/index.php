<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
<style>
<style>
		span.tab{
			color: black;
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

		<?php 
			if ($company_info->type == 0) {
				$this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','进销存'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId'=>$this->companyId,'type'=>0))),array('word'=>yii::t('app','运输损耗列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('comgoodsorder/list' , array('companyId' => $this->companyId,'type'=>0)))));
			}elseif($company_info->type == 2){
				$this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','仓库配置'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId,'type'=>0))),array('word'=>yii::t('app','运输损耗列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('tmall/list' , array('companyId' => $this->companyId,'type'=>0)))));
			}

?>


	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">

		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption">
						<span class="tab tab-active"><?php echo yii::t('app','运输损耗列表');?></span>
					</div>
					<div class="actions">
						<select id="back_status" class="btn yellow" >
	                        <option value="2" <?php if ($back_status==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','全部');?></option>
	                        <option value="1" <?php if ($back_status==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','已退款');?></option>
	                        <option value="0" <?php if ($back_status==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','未退款');?></option>
	                    </select>
	                    <div class="btn-group">
	                        <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
	                            <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>"  >
	                            <span class="input-group-addon">~</span>
	                            <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>" >
	                        </div>
	                    </div>
	                    <div class="btn-group">
	                        <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
	                    </div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<?php if($models):?>
						<thead>
							<tr>
								<!-- <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th> -->
								<th><?php echo yii::t('app','订单编号');?></th>
								<th><?php echo yii::t('app','店铺名称');?></th>
								<th><?php echo yii::t('app','详情');?></th>
								<th><?php echo yii::t('app','退货人');?></th>
                                <th><?php echo yii::t('app','退款金额');?></th>
                                <?php if(Yii::app()->user->role < 7):?>
                                <th><?php echo yii::t('app','是否退钱');?></th>
                            	<?php endif; ?>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($models as $account_no=>$model):?>
							<tr class="odd gradeX">
								<!-- <td><input type="checkbox" class="checkboxes" value="<?php //echo $model['lid'];?>" name="lid[]" /></td> -->
								<td><?php echo $account_no;?></td>
								<td><?php echo $model[0]['store_name'];?></td>
								<td>
									<table>
										<tr>
											<th><?php echo yii::t('app','产品图片');?></th>
											<th><?php echo yii::t('app','商品名称');?></th>
											<th><?php echo yii::t('app','仓库名称');?></th>
			                                <th><?php echo yii::t('app','商品单价');?></th>
			                                <th><?php echo yii::t('app','退货数量');?></th>
											<th><?php echo yii::t('app',' 配送号');?></th>
										</tr>
										<?php $zongjia = 0; ?>
										<?php foreach ($model as $key => $value): ?>
										<tr>
											<td><img width="50" src="<?php if($value['main_picture']){echo $value['main_picture'];}else{echo 'http://menu.wymenu.com/wymenuv2/img/product_default.png';}?>" alt=""></td>
											<td><?php echo $value['goods_name'];?></td>
											<td><?php echo $value['depot_name'];?></td>
											<td><?php echo $value['price'];?></td>
											<td><?php echo $value['num'];?></td>
											<td><?php echo $value['invoice_accountno'];?></td>
										</tr>
										<?php $zongjia += $value['price']*$value['num']; ?>
										<?php endforeach; ?>
									</table>

								</td>


								<td><?php echo $model[0]['username'];?></td>
								<td><?php echo $zongjia;?></td>
								<?php if(Yii::app()->user->role <7):?>
                                <td class="center">
								<?php if($model[0]['status']==0): ?>
									<button class="status btn red" lid="<?php echo $account_no;?>" status="<?php echo $model[0]['status'];?>"> 未退款</button>
								<?php else: ?>
									<button class="status btn green" lid="<?php echo $account_no;?>" status="<?php echo $model[0]['status'];?>"> 已退款</button>
								<?php endif; ?>

								</td>
							<?php endif; ?>
							</tr>
						<?php endforeach;?>
						<?php else: ?>
							<tr class="odd gradeX"><td>暂时没有损耗信息</td></tr>
						<?php endif;?>
						</tbody>
					</table>

				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
	<script>

    jQuery(document).ready(function(){
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

	$('.status').click(function() {
		<?php if(Yii::app()->user->role < 7):?>
		if (confirm('是否修改损耗产品的退款状态?')) {
			var account_no = $(this).attr('lid');
			var status = $(this).attr('status');
			$(this).attr('id','aa');
			$.ajax({
				url: '<?php echo $this->createUrl('goodsmaterialback/changestatus',array('companyId'=>$this->companyId))?>',
				type: 'POST',
				dataType: 'json',
				data: {account_no: account_no,status:status},
				success:function(data){
					if(data){
						var clas = $('#aa').attr('class');
						if( clas == 'status btn red'){
							$('#aa').removeClass('status btn red');
							$('#aa').addClass('status btn green');
							$('#aa').text('');
							$('#aa').text('已退款');
							$('#aa').attr('status',1);
							$('#aa').removeAttr("id");
						}else{
							$('#aa').removeClass('status btn green');
							$('#aa').addClass('status btn red');
							$('#aa').text('');
							$('#aa').text('未退款');
							$('#aa').attr('status',0);
							$('#aa').removeAttr("id");
						}
					}else{
						layer.msg("因网络问题修改失败,请重新修改");
						$('#aa').removeAttr("id");
					}
				},
			});
		}
		<?php else: ?>
		alert('您没有操作权限!!!');
		<?php endif; ?>
	});

    $('#btn_time_query').click(function time() {

        var begin_time = $('#begin_time').val();
        var end_time = $('#end_time').val();
        var back_status = $('#back_status').val();
        location.href="<?php echo $this->createUrl('goodsmaterialback/index' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/back_status/"+back_status;

    });
	</script>
	<!-- END PAGE CONTENT-->