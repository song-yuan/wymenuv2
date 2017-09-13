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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','数据中心'),'subhead'=>yii::t('app','营业数据'),'breadcrumbs'=>array(array('word'=>yii::t('app','营业数据'),'url'=>$this->createUrl('statements/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','支付方式（员工营业额）报表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('statements/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
    <div class="col-md-12">
        <!-- BEGIN EXAMPLE TABLE PORTLET-->
        <div class="portlet box purple">
            <div class="portlet-title">
                <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','支付方式（员工营业额）报表');?></div>
            	<div class="actions">
                    <select id="userid" class="btn yellow" >
                            <option value="0" <?php if ($userid==0){?> selected="selected" <?php }?> ><?php echo yii::t('app','--请选择服务员--');?></option>
                            <option value="-1" <?php if ($userid==-1){?> selected="selected" <?php }?> ><?php echo yii::t('app','--列出所有--');?></option>
                            <?php if($username):?>
                            <?php foreach ($username as $user):?>
                            <option value="<?php echo $user['username'];?>" <?php if ($userid==$user['username']){?> selected="selected" <?php }?> ><?php echo $user['username'].'('.$user['staff_no'].')';?></option>
                            <?php endforeach;?>
                            <?php endif;?>
                    </select>
                    <select id="text" class="btn yellow" >
                            <option value="1" <?php if ($text==1){?> selected="selected" <?php }?> ><?php echo yii::t('app','年');?></option>
                            <option value="2" <?php if ($text==2){?> selected="selected" <?php }?> ><?php echo yii::t('app','月');?></option>
                            <option value="3" <?php if ($text==3){?> selected="selected" <?php }?> ><?php echo yii::t('app','日');?></option>
                    </select>
                    <div class="btn-group">
	                    <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
	                         <input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>">  
	                         <span class="input-group-addon">~</span>
	                         <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>">           
	                    </div>  
                    </div>	
					
                    <div class="btn-group">
                                    <button type="submit" id="btn_time_query" class="btn red" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                                    <button type="submit" id="excel"  class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel');?></button>				
                                    <!-- <a href="<?php echo $this->createUrl('statements/export' , array('companyId' => $this->companyId));?>/text/<?php echo $text;?>/begin_time/<?php echo $begin_time;?>/end_time/<?php echo $end_time;?>" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','导出Excel2');?></a> -->
                    </div>			
                </div>
            </div> 
			
			<div class="portlet-body" id="table-manage">
			<table class="table table-striped table-bordered table-hover" id="sample_1">
		        <thead>
		            <tr>
		                <?php  $grouppay_item = 0;?>
		               <th><?php echo yii::t('app','时间');?></th>
		               <th><?php echo yii::t('app','总单数');?></th> 
		               <th><?php echo yii::t('app','金额');?></th> 
		               <th><?php echo yii::t('app','类型');?></th>
		
		            </tr>
		        </thead>
				<tbody>
		        <?php if( $models) :?>
		        <!--foreach-->
		        <?php $a=1;?>
		        <?php 
		         $orders_total=0;      // 总单数
		         $grossprofit_total=0; // 总毛利润
		         $discount_total=0;    // 总优惠
		         $gather_total=0;      // 实收款 
		         $cash_total=0;        // 现金
		         $wechat_total = 0;    // 微信
		         $wxorder_total = 0;    // 微信点单
		         $wxwaimai_total = 0;    // 微信外卖
		         $alipay_total = 0;    // 支付宝
		         $unionpay_total=0;    // 银联
		         $vipcard_total = 0;   // 会员卡 
		         $meituan_total = 0;   // 对接美团
		         $eleme_total = 0;   // 对接饿了么
		         $grouppay_arr = array();   //支付宝/美团
		        for($i =0;$i<$grouppay_item;$i++){
		           $grouppay_arr[$i] =0; 
		           // $grouppay.$i =0;
		        }
		        $all_wxcards = 0;
		        $all_wxcharges = 0;
		        $all_wxpoints = 0;
		        $retreats = 0;
		        foreach ($models as $model): ?>
		
		        <tr class="odd gradeX">
		            <td><?php echo $model['create_at'];?></td>
		            <td><?php echo $model['pay_order_num'];?></td>
					<td><?php echo $model['pay_amount_total'];?></td>	
					<td><?php switch($model['paytype']){
						case 0: echo '现金';break;
						case 1: echo '微信';break;
						case 2: echo '支付宝';break;
						case 3: echo '后台';break;
						case 4: echo '会员卡';break;
						case 5: echo '银联';break;
						case 6: echo '';break;
						case 7: echo '';break;
						case 8: echo '积分';break;
						case 9: echo '系统券';break;
						case 10: echo '微信储值';break;
						case 11: echo '找零';break;
						case 12: echo '微点单';break;
						case 13: echo '微外卖';break;
						case 14: echo '美团';break;
						case 15: echo '饿了么';break;
					}?></td>				
		        </tr>
		       
		        <?php endforeach;?>	
		      
		      <?php endif;?> 
		       
		        
		        </tbody>
    	</table>

					
    </div>
</div>
			<!-- END EXAMPLE TABLE PORTLET-->
</div>
	<!-- END PAGE CONTENT-->
</div>
<!-- END PAGE -->

<script>
//var str=new array();						
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
     $('#btn_time_query').click(function time() {  

     	var begin_time = $('#begin_time').val();
     	var end_time = $('#end_time').val();
     	var text = $('#text').val();
     	var userid = $('#userid').val();
     	location.href="<?php echo $this->createUrl('statements/rijie' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid    

	});
	
	$('#excel').click(function excel(){

		var begin_time = $('#begin_time').val();
		var end_time = $('#end_time').val();
		var text = $('#text').val();
		var userid = $('#userid').val();
		if(confirm('确认导出并且下载Excel文件吗？')){
			location.href="<?php echo $this->createUrl('statements/paymentExport' , array('companyId'=>$this->companyId));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/text/"+text+"/userid/"+userid;
		}else{
			// location.href="<?php echo $this->createUrl('statements/export' , array('companyId'=>$this->companyId ));?>/str/"+str+"/begin_time/"+begin_time+"/end_time/"+end_time +"/text/"+text;
		}
	});

</script> 
