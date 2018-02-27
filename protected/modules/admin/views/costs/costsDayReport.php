<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
   <!-- BEGIN PAGE -->
<style>
.year,.month{
	padding:10px 10px;
	float:left;
}
.datepicker.dropdown-menu {
    width: 190px;
    height: 260px;
    padding: 5px;
}
.form-control{
	width: 100px!important;
	border-radius: 4px!important;
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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','支出成本'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','支出成本');?></div>
            	<div class="actions">
                    <select id="text" class="btn yellow" >
                            <option value="0" <?php if ($cost_type==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
                            <option value="1" <?php if ($cost_type==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
                            <option value="2" <?php if ($cost_type==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
                    </select>
                    <div class="btn-group">
					    <div class="input-group date">
					    	<?php if ($cost_type==0):?>
					        <input name="time" id="time" class="form-control date-picker " placeholder="<?php echo yii::t('app','请选择日期');?>" value="<?php echo $time; ?>" readonly>
					    	<?php elseif($cost_type==1):?>
					    	<input name="month" id="month" class="form-control date-picker " value="<?php echo $month; ?>" placeholder="<?php echo yii::t('app','请选择月份');?>" readonly/>
					    	<?php elseif($cost_type==2):?>
					    	<input name="year" id="year" class="form-control date-picker " value="<?php echo $year; ?>" placeholder="<?php echo yii::t('app','请选择年份');?>" readonly/>
					    	<?php endif;?>
					    </div>
                    </div>

                    <div class="btn-group">
                        <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                    </div>
                    <div class="btn-group">
                        <button  id="create" class="btn yellow" ><i class="fa fa-pencial"></i><?php echo yii::t('app','添加');?>
                    </div>
                </div>
            </div>

			<div class="portlet-body" id="table-manage">
			<div class="table-responsive">

			<table class="table table-striped table-bordered table-hover" id="sample_1">
		        <thead>
		            <tr style="background: #666;color:white;">
		               <th><?php echo yii::t('app','项目');?></th>
		               <th><?php echo yii::t('app','描述');?></th>
		               <th><?php echo yii::t('app','日期');?></th>
		               <th><?php echo yii::t('app','类型');?></th>
		               <th><?php echo yii::t('app','款项/元');?></th>
		               <th>
		               		<?php
		               			if($cost_type==0){
		               				echo yii::t('app','日均/元(保留2位)');
								}elseif($cost_type==1){
									echo yii::t('app','单月/元(保留2位)');
								}elseif($cost_type==2){
									echo yii::t('app','整年/元(保留2位)');
								}
							?>
						</th>
		               <th><?php echo yii::t('app','操作');?></th>
		            </tr>
		        </thead>
				<tbody>
				<?php
					$pay = 0;
					$m = substr($time, 5,2);//date('n')
					$y = substr($time, 0,4);//date('Y')
					if($cost_type!=2):
					$d = cal_days_in_month(CAL_GREGORIAN,$m,$y);
					endif;
					if($model1):
				?>
				<?php foreach ($model1 as $value):?>
		        <tr class="odd gradeX">
					<td  style="color:red;"><?php echo $value['item']; ?></td>
					<td><?php echo $value['description']; ?></td>
					<td><?php echo $value['happen_at']; ?></td>
					<td><?php 
						if($value['pay_type']==0){
							echo '<span style="color:blue;">当日支出</span>';
						}elseif($value['pay_type']==1){
							echo '<span style="color:orange;">单月支出</span>';
						}elseif($value['pay_type']==2){
							echo '<span style="color:black;">整年支出</span>';
						}
					?>
					</td>
					<td><?php echo $value['price']; ?></td>
					<td class="pric">
						<?php
							if($cost_type==0){
								if($value['pay_type']==0){
									echo sprintf("%.2f", $value['price']);
									$pay += $value['price'];
								}elseif($value['pay_type']==1){
									echo sprintf("%.2f", $value['price']/$d);
									$pay += $value['price']/$d;
								}elseif($value['pay_type']==2){
									echo sprintf("%.2f", $value['price']/365);
									$pay += $value['price']/365;
								}
							}elseif($cost_type==1){
								if($value['pay_type']==0){
									echo sprintf("%.2f", $value['price']);
									$pay += $value['price'];
								}elseif($value['pay_type']==1){
									echo sprintf("%.2f", $value['price']);
									$pay += $value['price'];
								}elseif($value['pay_type']==2){
									echo sprintf("%.2f", $value['price']/12);
									$pay += $value['price']/12;
								}
							}elseif($cost_type==2){
								if($value['pay_type']==0){
									echo sprintf("%.2f", $value['price']);
									$pay += $value['price'];
								}elseif($value['pay_type']==1){
									echo sprintf("%.2f", $value['price']);
									$pay += $value['price'];
								}elseif($value['pay_type']==2){
									echo sprintf("%.2f", $value['price']);
									$pay += $value['price'];
								}
							}
						?>
					</td>

					<td>
					<a class="btn blue" href="<?php if($cost_type==0){
										echo $this->createUrl('costs/update',array('companyId'=>$this->companyId,'lid'=>$value['lid'],'time'=>$time,'cost_type'=>$cost_type,'type'=>1));
									}elseif($cost_type==1){
										echo $this->createUrl('costs/update',array('companyId'=>$this->companyId,'lid'=>$value['lid'],'month'=>$month,'cost_type'=>$cost_type,'type'=>1));
									}elseif($cost_type==2){
										echo $this->createUrl('costs/update',array('companyId'=>$this->companyId,'lid'=>$value['lid'],'year'=>$year,'cost_type'=>$cost_type,'type'=>1));
									}  ?>">编辑</a>
					<a class="btn red delete" lid="<?php echo $value['lid']; ?>">删除</a></td>
		        </tr>
		    	<?php endforeach; ?>
		    	<?php endif; ?>

		        <tr class="odd gradeX">
		               <th style="background: #ccc!important;"></th>
		               <th style="background: #ccc!important;"></th>
		               <th style="background: #ccc!important;"></th>
		               <th style="background: #ccc!important;"></th>
		               <th style="background: #ccc!important;"><?php echo yii::t('app','成本(支出)');?></th>
		               <th style="background: #ccc!important;"> - <span id="paynum"><?php
							if($cost_type==0){
								echo sprintf("%.2f", $pay);
							}elseif($cost_type==1){
								echo sprintf("%.2f", $pay);
							}elseif($cost_type==2){
								echo sprintf("%.2f", $pay);
							}
						?> </span> 元</th>
		               <th style="background: #ccc!important;"></th>
		        </tr>
	        </tbody>
    	</table>
		</div>

    </div>
</div>
			<!-- END EXAMPLE TABLE PORTLET-->
</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>
jQuery(document).ready(function(){
	 $('#time').datepicker({
        minView: "day", //  选择时间时，最小可以选择到那层；默认是‘hour’也可用0表示
        language: 'zh-CN', // 语言
        autoclose: true, //  true:选择时间后窗口自动关闭
        format: 'yyyy-mm-dd', // 文本框时间格式，设置为0,最后时间格式为2017-03-23 17:00:00
        // 窗口可选时间从今天开始
        endDate: new Date()
    });
	 $('#month').datepicker({
        language: "zh-CN",
        autoclose: true,
        format: "yyyy-mm",
        minViewMode: 1,
        endDate: new Date()
    });
	 $("#year").datepicker({
	    language: "zh-CN",
	    todayHighlight: true,
	    format: 'yyyy',
	    autoclose: true,
	    startView: 'years',
	    maxViewMode:'years',
	    minViewMode:'years',
	    endDate: new Date()
	});

	$("#text").change(function(event) {
		if($(this).val()==0){
			$(".date-picker").remove();
	    $(".input-group-addon").remove();
	    var $leftInput = $('<input name="time" id="time" class="form-control date-picker " value="<?php echo $time; ?>" placeholder="<?php echo yii::t('app','请选择日期');?>" readonly/>');
	    $(".input-group").append($leftInput);
	    $('.date-picker').datepicker({
	        minView: "day", //  选择时间时，最小可以选择到那层；默认是‘hour’也可用0表示
	        language: 'zh-CN', // 语言
	        autoclose: true, //  true:选择时间后窗口自动关闭
	        format: 'yyyy-mm-dd', // 文本框时间格式，设置为0,最后时间格式为2017-03-23 17:00:00
	        // 窗口可选时间从今天开始
	        endDate: new Date()
	    });
		}else if($(this).val()==1){
	    $(".date-picker").remove();
	    $(".input-group-addon").remove();
	    var $leftInput = $('<input name="month" id="month" class="form-control date-picker " value="<?php echo $month; ?>" placeholder="<?php echo yii::t('app','请选择月份');?>" readonly/>');
	    $(".input-group").append($leftInput);
	    $('.date-picker').datepicker({
	        language: "zh-CN",
	        autoclose: true,
	        format: "yyyy-mm",
	        minViewMode: 1,
	        endDate: new Date()
	    });
		}else if ($(this).val()==2){
		$(".date-picker").remove();
		$(".input-group-addon").remove();
		var $leftInput = $('<input name="year" id="year" class="form-control date-picker " value="<?php echo $year; ?>" placeholder="<?php echo yii::t('app','请选择年份');?>" readonly/>');
		$(".input-group").append($leftInput);
		$(".date-picker").datepicker({
		    language: "zh-CN",
		    todayHighlight: true,
		    format: 'yyyy',
		    autoclose: true,
		    startView: 'years',
		    maxViewMode:'years',
		    minViewMode:'years',
		    endDate: new Date()
		});
		}
	});

});
    $('#btn_time_query').click(function time() {
     	var text = $('#text').val();
     	if (text==0) {
     		var time = $('#time').val();
     		location.href="<?php echo $this->createUrl('costs/costsDayReport' , array('companyId'=>$this->companyId ));?>/time/"+time+"/cost_type/"+text;
     	}else if(text==1){
     		var month = $('#month').val();
     		location.href="<?php echo $this->createUrl('costs/costsDayReport' , array('companyId'=>$this->companyId ));?>/month/"+month+"/cost_type/"+text;
     	}else if(text==2){
     		var year = $('#year').val();
     		location.href="<?php echo $this->createUrl('costs/costsDayReport' , array('companyId'=>$this->companyId ));?>/year/"+year+"/cost_type/"+text;
     	}
	});


    $('#create').click(function() {
     	var time = "<?php echo $time; ?>";
     	var text = $('#text').val();
     	location.href="<?php echo $this->createUrl('costs/create' , array('companyId'=>$this->companyId ));?>/time/"+time+"/cost_type/"+text+"/type/1";
	});

	$('.delete').click(function() {
		if (confirm('确认删除该记录吗？')) {

     	var lid = $(this).attr('lid');
     	var paynum = $('#paynum').text();
     	$(this).attr('id','aa');
     	$.ajax({
     		url: "<?php echo $this->createUrl('costs/delete' , array('companyId'=>$this->companyId ));?>",
     		type: 'POST',
     		dataType: 'json',
     		data: {lid: lid},
     		success:function(data){
     			if (data==1) {
     				var juncosts = $('#aa').parent().parent().children('.pric').text();
     				var obj = paynum-juncosts;
     				$('#paynum').text(obj.toFixed(2));
     				$('#aa').parent().parent().remove();
     			}else{
     				layer.msg('删除失败!!!');
     				$('#aa').removeAttr('id');
     			}
     		}
     	});
		}
	});

</script>
