<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js');?>"></script>
<script type="text/javascript" src="<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js');?>"></script>
   
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','订单管理'),'subhead'=>yii::t('app','日结列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','日结汇总'),'url'=>'')),'back'=>array('word'=>'返回','url'=>$this->createUrl('orderManagement/notPay' , array('companyId' => $this->companyId,'begin_time'=>$begin_time,'end_time'=>$end_time)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
					<div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">
				                
                                                            <div class="modal-header">
                                                                    <h4 class="modal-title">日结打印</h4>
                                                            </div>
                                                            <div class="modal-body">
                           <!-- <div class="portlet-body" id="table-manage">
                          	<table class="table table-striped table-bordered table-hover" id="sample_1">
								<thead>
									<tr>
										<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
										<th><?php echo yii::t('app','报表名称');?></th>
										<th ><?php echo yii::t('app','');?></th>
										<th ><?php echo yii::t('app','');?></th>
									</tr>
								</thead>
								<tbody>
									<tr class="odd gradeX">
										<td><input type="checkbox" class="checkboxes" value="1" name="ids[]"/></td>
										<td>shuxer</td>
										<td ><a href="mailto:shuxer@gmail.com">shuxer@gmail.com</a></td>
										<td><span class="label label-sm label-success">Approved</span></td>
									</tr>
									<tr class="odd gradeX">
										<td><input type="checkbox" class="checkboxes" value="1" name="ids[]" /></td>
										<td>looper</td>
										<td ><a href="mailto:looper90@gmail.com">looper90@gmail.com</a></td>
										<td><span class="label label-sm label-warning">Suspended</span></td>
									</tr>
									<tr class="odd gradeX">
										<td><input type="checkbox" class="checkboxes" value="1" name="ids[]"/></td>
										<td>userwow</td>
										<td ><a href="mailto:userwow@yahoo.com">userwow@yahoo.com</a></td>
										<td><span class="label label-sm label-success">Approved</span></td>
									</tr>								
								</tbody>
							</table>
                             </div>       -->                            <div class="portlet-body" id="table-manage">  
                             										<div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
                                                                    <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','营业数据表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="businessdata" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','营业收入表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="income" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','收款统计表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="payall" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','产品销售表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="product" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','账单详情表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="orderdetail" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','台桌区域表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="table" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','退菜明细表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="retreatdetail" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                                                                <div style="width:25%;float:left;"><?php echo yii::t('app','充值记录表');?></div>
                                                                                <div style="width:14%;float:left;"><input style="height:20px;" type="checkbox" class="checkboxes" value="recharge" name="reportlist[]" /></div>
                                                                        </li>
                                                                        <li>
                                                                                <div style="width:10%;float:left;"></div>
                                                                                <div style="width:25%;float:left;"></div>
                                                                                <div style="width:14%;float:right;"><input style="height:20px;" type="checkbox" class="group-checkable" data-set="#reportlistdiv .checkboxes" />全选</div>
                                                                        </li>                                                                       
                                                                    </ul>
                                                                </div>
                                                            </div>
                                                            <div class="modal-footer">
                                                                    <button id="printall" type="button" class="btn blue">确定打印</button>
                                                                    <!-- button id="selectall" type="button" class="btn blue">全选</button> -->
                                                                    <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
                                                            </div>
					                    					</div>
				                	
				    </div>
	<div class="row">
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
				
				 <div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','汇总列表');?></div>
					 <div class="actions">
                        <div class="btn-group">
            
						   <div class="input-group input-large date-picker input-daterange" data-date="10/11/2012" data-date-format="mm/dd/yyyy">
								<input type="text" class="form-control" name="begtime" id="begin_time" placeholder="<?php echo yii::t('app','起始时间');?>" value="<?php echo $begin_time; ?>" onfocus=this.blur()>  
								<span class="input-group-addon">~</span>
							    <input type="text" class="form-control" name="endtime" id="end_time" placeholder="<?php echo yii::t('app','终止时间');?>"  value="<?php echo $end_time;?>" onfocus=this.blur()>           
						   </div>  
					    </div>
					   
					      <div class="btn-group">
					      		<button type="submit" id="btn_time_query" class="btn green" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></button>
                                                        <!-- <button type="button" style="margin-left: 40px;" class="btn green" id="btn-closeaccount-print" ><i class="fa fa-pencial"></i><?php echo yii::t('app','日结打印');?></button>   -->
                                                       <!-- <button type="submit" id="btn_submit" class="btn red" style="margin-left:10px;"><i class="fa fa-pencial"></i><?php echo yii::t('app','日 结');?></button>-->
				  	      </div>
				  	  </div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								
								<th width=100px;><?php echo yii::t('app','序号');?></th>
						        
                                <th><?php echo yii::t('app','支付方式');?></th>
                                <th><?php echo yii::t('app','金额');?></th>                                                                
                                <th><?php echo yii::t('app','备注');?></th>
								
							</tr>
						</thead>
						<tbody>
				 
						<!--foreach-->
					<?php $a=1;$sumall=0;?>
						  <?php foreach ($models as $model):?>  
						
												
								<tr class="odd gradeX">
								<td><?php echo ($pages->getCurrentPage())*10+$a;?></td>
								
								
                                                                <td><?php switch($model->paytype) {
                                                                case 0: echo  yii::t('app','现金支付');break; 
                                                                case 1: echo  yii::t('app','微信支付');break; 
                                                                case 2: echo  yii::t('app','支付宝支付');break; 
                                                                case 4: echo  yii::t('app','会员卡支付');break;  
                                                                case 5: echo  yii::t('app','银联卡支付');break; 
                                                                case 6: echo  yii::t('app','');break;
                                                                case 7: echo  yii::t('app','');break;
                                                                case 8: echo  yii::t('app','');break;
                                                                case 9: echo  yii::t('app','微信代金券');break;
                                                                case 10: echo  yii::t('app','微信会员余额支付');break;
                                                                case 3: if ($model->payment_method_id){echo  $model->paymentMethod->name;}else echo '';break;
                                                                default :echo ''; }?></td>
								<td><?php echo $model->should_all;?></td>
								<td></td>
								</tr>
						<?php $a++;$sumall=$sumall+$model->should_all;?>
						<?php endforeach;?>	
						<!-- end foreach--><?php// foreach ($moneys as $money):?>
											<?php  //var_dump($moneys);exit;
											if(!empty($moneys)):?>
													<tr>
													<td><?php echo $a;?></td>
													<td><?php echo yii::t('app','传统卡充值金额');?></td>
													<td><?php if(!empty($moneys["all_money"])) echo $moneys["all_money"];else echo "0.00";//var_dump($money);exit;?></td>
													<td><?php echo yii::t('app','总共赠送金额：'); if(!empty($moneys['all_give']))echo $moneys['all_give'];else echo "0.00";?></td>
													</tr>
											<?php $a++;?>
											<?php $sumall=$sumall+$moneys["all_money"];
												endif;?>
											<?php  //var_dump($moneys);exit;
											if(!empty($recharge)):?>
													<tr>
													<td><?php echo $a;?></td>
													<td><?php echo yii::t('app','微信会员充值金额');?></td>
													<td><?php if(!empty($recharge["all_recharge"])) echo $recharge["all_recharge"];else echo "0.00";//var_dump($money);exit;?></td>
													<td><?php echo yii::t('app','总共返现金额：'); if(!empty($recharge['all_cashback']))echo $recharge['all_cashback'];else  echo "0.00";?></td>
													</tr>
											<?php $sumall=$sumall+$recharge["all_recharge"];
												endif;?>
											<?php // endforeach;?>
                                                    <tr class="odd gradeX">
                                                    <td></td>
                                                    <td>合计：</td>
                                                    <td><?php echo $sumall;?></td>
                                                    <td></td>
                                                    </tr>
						</tbody>
					</table>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共 ');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> ,  <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
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
	
	</div>
	<!-- END PAGE CONTENT-->

</div>
<script>
        var layer_index_printreportlist=0;
        jQuery(document).ready(function(){
            TableManaged.init();
            if (jQuery().datepicker) {
                $('.date-picker').datepicker({
                    format: 'yyyy-mm-dd',
                    language: 'zh-CN',
                    rtl: App.isRTL(),
                    autoclose: true
                });
                $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal	            
            }
        });
        
        $('#btn_time_query').click(function() {  
            var begin_time = $('#begin_time').val();
            var end_time = $('#end_time').val();
            location.href="<?php echo $this->createUrl('orderManagement/orderDaliyCollect' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/page/";    
         });
         
        $('#btn_submit').click(function() {
            var begin_time = $('#begin_time').val();
                var end_time = $('#end_time').val();
            $.get("<?php echo $this->createUrl('orderManagement/dailyclose',array('companyId'=>$this->companyId ));?>",{begin_time:begin_time,end_time:end_time},function(msg){
                    if(parseInt(msg)){
                            alert('日结成功!');
                            history.go(0);
                    }else{
                            alert('日结失败,请重新日结!');
                    }
            });
        });		
                
        $('#btn-closeaccount-print').on('click',function() {
            if(layer_index_printreportlist!=0)
            {
                return;
            }
            layer_index_printreportlist=layer.open({
                type: 1,
                shade: false,
                title: false, //不显示标题
                area: ['60%', '60%'],
                content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
                cancel: function(index){
                    layer.close(index);
                    layer_index_printreportlist=0;                                                                                                     
                }
            });
            return;
            var padid="0000000046";
            if (typeof Androidwymenuprinter == "undefined") {
                alert("找不到PAD设备");
                //return false;
            }else{
                var padinfo=Androidwymenuprinter.getPadInfo();
                padid=padinfo.substr(10,10);
            }
            var begin_time = $('#begin_time').val();
            var end_time = $('#end_time').val();
            var url = "<?php echo $this->createUrl('orderManagement/orderDaliyCollectPrint',array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/padid/"+padid;
            //var url="<?php echo $this->createUrl('defaultOrder/orderPrintlist',array('companyId'=>$this->companyId));?>/orderId/"+orderid+"/padId/"+padid;
            var statu = confirm("<?php echo yii::t('app','确定要打印日结单吗？');?>");
            if(!statu){
                return false;
            } 
            $.ajax({
                url:url,
                type:'GET',
                data:"",
                async:false,
                dataType: "json",
                success:function(msg){
    //                            var waittime=0;
                    var data=msg;
                    //alert(data.msg);
                    var printresult=false;
                    if(data.status){
                        //alert(data.jobid);
                        var index = layer.load(0, {shade: [0.3,'#fff']});
                        //var wait=setInterval(function(){ 
                        for(var itemp=1;itemp<4;itemp++)
                        {
                            if(printresult)
                            {
                                break;
                            }
                            printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);                                  
                             //alert(itemp);                                  
                        }                           
                        layer.close(index);
                        if(!printresult)
                        {
                            alert("请重试！");
                        }
                    }else{
                        alert(data.msg);                                
                    }
                   //以上是打印
                   //刷新orderPartial	                 
                },
                error: function(msg){
                    alert("保存失败2");
                }
            });                	
         });
        
        $("#closeall").on("click",function(){
            layer.close(layer_index_printreportlist);
            layer_index_printreportlist=0;
        });
        
        $("#printall").on("click",function(){
            //alert("暂无权限！！！");
            var reportlist =new Array();
            var reportlist="0000000000";
            $('.checkboxes:checked').each(function(){
                reportlist=reportlist+","+$(this).val();
            });
            //alert(reportlist);
            var padid="0000000046";
            if (typeof Androidwymenuprinter == "undefined") {
                alert("找不到PAD设备");
                //return false;
            }else{
                var padinfo=Androidwymenuprinter.getPadInfo();
                padid=padinfo.substr(10,10);
            }
            var begin_time = $('#begin_time').val();
            var end_time = $('#end_time').val();
            var url = "<?php echo $this->createUrl('orderManagement/orderDaliyCollectPrint',array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/padid/"+padid+"/rl/"+reportlist;
            //var url="<?php echo $this->createUrl('defaultOrder/orderPrintlist',array('companyId'=>$this->companyId));?>/orderId/"+orderid+"/padId/"+padid;
            var statu = confirm("<?php echo yii::t('app','确定要打印日结单吗？');?>");
            if(!statu){
                return false;
            } 
            $.ajax({
                url:url,
                type:'GET',
                data:"",
                async:false,
                dataType: "json",
                success:function(msg){
    //                            var waittime=0;
                    var data=msg;
                    //alert(data.msg);
                    var printresult=false;
                    if(data.status){
                        //alert(data.jobid);
                        var index = layer.load(0, {shade: [0.3,'#fff']});
                        //var wait=setInterval(function(){ 
                        for(var itemp=1;itemp<4;itemp++)
                        {
                            if(printresult)
                            {
                                break;
                            }
                            printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);                                  
                             //alert(itemp);                                  
                        }                           
                        layer.close(index);
                        if(!printresult)
                        {
                            alert("请重试！");
                        }
                    }else{
                        alert(data.msg);                                
                    }
                   //以上是打印
                   //刷新orderPartial	                 
                },
                error: function(msg){
                    alert("保存失败2");
                }
            });
        });
		
</script> 