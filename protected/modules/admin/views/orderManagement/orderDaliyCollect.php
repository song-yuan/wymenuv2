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
                                                        <button type="button" style="margin-left: 40px;" class="btn green" id="btn-closeaccount-print" ><i class="fa fa-pencial"></i><?php echo yii::t('app','日结打印');?></button>
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
								
								
                                                                <td><?php switch($model->paytype) {case 0: echo  yii::t('app','现金支付');break; 
                                                                case 1: echo  yii::t('app','微信支付');break; 
                                                                case 2: echo  yii::t('app','支付宝支付');break; 
                                                                case 3: echo  yii::t('app','后台手动支付');break;  
                                                                case 4: echo  yii::t('app','会员卡支付');break;  
                                                                case 5: echo  yii::t('app','银联卡支付');break;  
                                                                default :echo ''; }?></td>
								<td><?php echo $model->should_all;?></td>
								<td></td>
								</tr>
						<?php $a++;$sumall=$sumall+$model->should_all;?>
						<?php endforeach;?>	
						<!-- end foreach-->
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
		jQuery(document).ready(function(){
		    if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	            	format: 'yyyy-mm-dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	            
           }
          $('#btn_time_query').click(function() {  
			   var begin_time = $('#begin_time').val();
			   var end_time = $('#end_time').val();
			   location.href="<?php echo $this->createUrl('orderManagement/orderDaliyCollect' , array('companyId'=>$this->companyId ));?>/begin_time/"+begin_time+"/end_time/"+end_time+"/page/"    
			  
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
		});
                
                $('#btn-closeaccount-print').on('click',function() {
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
                
		
</script> 