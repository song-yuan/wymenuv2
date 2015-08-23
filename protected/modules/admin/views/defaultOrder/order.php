	<!-- BEGIN PAGE -->  
		<div class="page-content">
                        <div class="modal fade" id="modal-wide" tabindex="-1" role="basic" aria-hidden="true">
                                <div class="modal-dialog modal-wide">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal Title</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Modal body goes here
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn blue">Save changes</button>
                                                </div>
                                        </div>
                                        <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                        </div>
                    <div class="modal fade" id="modal-fullwide" tabindex="-1" role="basic" aria-hidden="true">
                                <div class="modal-dialog modal-full" style="height:100%;">
                                        <div class="modal-content" style="height:100%;">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal Title</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Modal body goes here
                                                </div>
                                                <div class="modal-footer">
                                                        <button type="button" class="btn default" data-dismiss="modal">Close</button>
                                                        <button type="button" class="btn blue">Save changes</button>
                                                </div>
                                        </div>
                                        <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                        </div>
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
			<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
                        <div class="modal fade" id="portlet-config2" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                        <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal title2</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Widget settings form goes here2
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
                        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
                        <div class="modal fade" id="portlet-print-loading" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                                <div class="modal-header">
                                                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                        <h4 class="modal-title">Modal title2</h4>
                                                </div>
                                                <div class="modal-body">
                                                        Widget settings form goes here2
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
                        <!-- BEGIN PAGE CONTENT-->
			<div class="row">
                                <div class="col-md-4">
                                    <h3 class="page-title"><?php switch($model->order_status) {case 1:{echo yii::t('app','未下单');break;} case 2:{echo yii::t('app','下单未支付');break;} case 3:{echo yii::t('app','已支付').$model->reality_total;break;} }?></h3>                                    
                                </div>
                                <div class="col-md-8">
                                    <h4>
                                       <?php echo yii::t('app','');?><?php echo $model->create_at;?> 
                                       &nbsp;&nbsp;&nbsp;&nbsp; <?php echo yii::t('app','应付金额（元）：');?><?php echo number_format($total['total'], 2);?>
                                       &nbsp;&nbsp;&nbsp;&nbsp; <?php echo yii::t('app','实付金额（元）：');?><?php echo $model->reality_total;?>
                                    </h4>    
                                </div>
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i>
                                                            <?php echo $total['remark'] ;?>
                                                        </div>
                                                        <div class="col-md-3 ">
                                                                <input id="callbarscanid" type="text" class="form-control" placeholder='<?php if($syscallId!='0') echo yii::t('app','扫描呼叫器条码快速收银、结算'); else echo yii::t('app','扫描呼叫器条码快速下单、厨打'); ?>'>
                                                        </div>
                                                        <div class="actions">
                                                            <?php if($model->order_status=='3' || $model->order_status=='4'): ?>
                                                                <a class="btn purple" id="btn_payback"><i class="fa fa-adjust"></i> <?php echo yii::t('app','退款');?></a>
                                                            <?php endif;?>
                                                            <?php if($model->order_status!='4'): ?>
                                                            <a class="btn purple" id="btn_account"><i class="fa fa-pencil"></i> <?php echo yii::t('app','结单&收银');?></a>
                                                            <a id="kitchen-btn" class="btn purple"><i class="fa fa-cogs"></i> <?php echo yii::t('app','下单&厨打');?></a>
                                                            <a id="print-btn" class="btn purple"><i class="fa fa-print"></i> <?php echo yii::t('app','打印清单');?></a>
                                                            <a id="alltaste-btn" class="btn purple"><i class="fa fa-pencil"></i> <?php echo yii::t('app','全单口味');?></a>
                                                            <?php endif; ?>
                                                            <a href="<?php echo $this->createUrl('default/index' , array('companyId' => $model->dpid,'typeId'=>$typeId));?>" class="btn red"><i class="fa fa-times"></i> <?php echo yii::t('app','返回');?></a>
                                                        </div>
						</div>
						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php echo $this->renderPartial('_form', array('model'=>$model,'orderProducts' => $orderProducts,'productTotal' => $productTotal,'total' => $total,'typeId'=>$typeId,'allOrderTastes'=>$allOrderTastes,'allOrderProductTastes'=>$allOrderProductTastes)); ?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
                
                    <script type="text/javascript">
                        var syscallid='<?php echo $syscallId; ?>';
                        var sysautoaccount='<?php echo $autoaccount; ?>';
                        //alert(sysautoaccount);
                        var scanon=false;
                        $(document).ready(function(){
                            $('body').addClass('page-sidebar-closed');
                            if(syscallid>"Ca000" && syscallid<"Ca999")
                            {
                                accountmanul();
                            }
                            if(sysautoaccount=="1")
                            {
                                accountmanul();
                            }
                        });
                        
                        function accountmanul(){
                            var pad_id="0000000000";
                            if (typeof Androidwymenuprinter == "undefined") {
                                alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
                                //return false;
                            }else{
                                var padinfo=Androidwymenuprinter.getPadInfo();
                                pad_id=padinfo.substr(10,10);
                            }
                            var loadurl='<?php echo $this->createUrl('defaultOrder/accountManul',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'total'=>$total['total']));?>/padId/'+pad_id;
                            
                            var callid= $('#callbarscanid').val();
                            if(callid>"Ca000" && callid<"Ca999")
                            {
                                loadurl=loadurl+'/callId/'+callid;
                            }
                            //alert(loadurl);
                            //var $modalconfig = $('#portlet-config');
                            var $modalconfig = $('#modal-wide');
                                $modalconfig.find('.modal-content')
                                        .load(loadurl
                                            , ''
                                            , function(){
                                                $modalconfig.modal();
                                });
                        }
                        
                        function openaccount(payback){
                            var pad_id="0000000000";
                            if (typeof Androidwymenuprinter == "undefined") {
                                alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
                                //return false;
                            }else{
                                var padinfo=Androidwymenuprinter.getPadInfo();
                                pad_id=padinfo.substr(10,10);
                            }
                            var loadurl='<?php echo $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'total'=>$total['total']));?>/padId/'+pad_id;
                            if(payback==1)
                            {
                                loadurl=loadurl+'/payback/1'
                            }
                            var callid= $('#callbarscanid').val();
                            if(callid>"Ca000" && callid<"Ca999")
                            {
                                loadurl=loadurl+'/callId/'+callid;
                            }
                            //alert(loadurl);
                            var $modalconfig = $('#modal-wide');
                                $modalconfig.find('.modal-content')
                                        .load(loadurl
                                            , ''
                                            , function(){
                                                $modalconfig.modal();
                                });
                        }
                        
                        $('#btn_account').on(event_clicktouchstart,function(){
                                 //openaccount('0');
                                 accountmanul();
                        });
                        $('#btn_payback').on(event_clicktouchstart,function(){
                            //alert(0);
                                 openaccount('1');
                        });
                        /*
                        $('#btn_pay').click(function(){
                                var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/pay',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid,'total'=>$total['total']));?>', '', function(){
                                            $modalconfig.modal();
                                          }); 
                        });
                        */
                        $('#print-btn').on(event_clicktouchstart,function(){
                            var pad_id="0000000000";
                            if (typeof Androidwymenuprinter == "undefined") {
                                alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
                                //return false;
                            }else{
                                var padinfo=Androidwymenuprinter.getPadInfo();
                                pad_id=padinfo.substr(10,10);
                            }
                            //var pad_id="0000000016";
                            var $modal=$('#portlet-config');
                            $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printList',array('companyId'=>$this->companyId));?>/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"+'/padId/'+pad_id
                                    ,'', function(){
                                                $modal.modal();
                                        });
                            /*
                            $.get('<?php echo $this->createUrl('defaultOrder/printList',array('companyId'=>$this->companyId,'orderId'=>$model->lid));?>/padId/'+pad_id,function(data){
                                    if(data.status) {
                                        if(data.type='local')
                                        {
                                            if(Androidwymenuprinter.printJob(company_id,data.jobid))
                                            {
                                                alert("<?php echo yii::t('app','打印成功');?>");
                                            }
                                            else
                                            {
                                                alert("<?php echo yii::t('app','PAD打印失败！，请确认打印机连接好后再试！');?>");                                                                        
                                            }
                                        }else{
                                            var $modal=$('#portlet-config');
                                            $modal.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printListNet',array('companyId'=>$this->companyId));?>/orderId/'+"<?php echo $model->lid; ?>"+'/typeId/'+"<?php echo $typeId; ?>"
                                                    ,'', function(){
                                                                $modal.modal();
                                                        });
                                        }
                                    } else {
                                            alert(data.msg);
                                    }
                            },'json');*/
                        });
                        
                        function printKiten(callid){
                            var $modalloading = $('#portlet-print-loading');                                
                           $modalloading.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printKitchen',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid));?>/callId/'+callid, '', function(){
                                $modalloading.modal();
                            });
                        }
                        
                        function printKitenAll(callid){
                            var $modalloading = $('#portlet-print-loading');                                
                           $modalloading.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/printKitchenAll',array('companyId'=>$this->companyId,'typeId'=>$typeId,'orderId'=>$model->lid));?>/callId/'+callid, '', function(){
                                $modalloading.modal();
                            });
                        }
                        
                        $('#kitchen-btn').on(event_clicktouchstart,function(){
                            var statu = confirm("<?php echo yii::t('app','下单，并厨打，确定吗？');?>");
                            if(!statu){
                                return false;
                            }
                             //由于打印机不能连续厨打，
                            //printKiten('0');       
                            //采用以下函数
                            printKitenAll('0'); 
                        });
                        
                        $('#alltaste-btn').on(event_clicktouchstart,function(){
                                var $modalconfig = $('#portlet-config');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/productTaste',array('companyId'=>$this->companyId,'typeId'=>$typeId,'lid'=>$model->lid,'isall'=>'1'));?>', '', function(){
                                            $modalconfig.modal();
                                          }); 
                        });
                        
                        $('#callbarscanid').keyup(function(){
                            if($(this).val().length==5 && scanon==false)
                            {
                                scanon=true;
                                var callid=$(this).val();
                                //alert(callid);
                                if(callid>"Ca000" && callid<"Ca999")
                                {
                                    
                                    if(syscallid!='0')
                                    {
                                        if(syscallid==callid)
                                        {
                                            openaccount('0');
                                        }else{
                                            alert("<?php echo yii::t('app','请再次扫描呼叫器：');?>"+syscallid+"<?php echo yii::t('app','，系统自动结单！');?>");
                                            $('#callbarscanid').val("");
                                            scanon=false;
                                            return false;
                                        }
                                    }else{   
                                        //菜品分单子打印时调用此函数
                                        //printKiten(callid);
                                        //由于打印机问题厨打，暂时用清单，调用以下函数
                                        printKitenAll(callid);
                                    }
                                }else{
                                    alert("<?php echo yii::t('app','呼叫器编码不正确！');?>");
                                    $('#callbarscanid').val("");
                                    scanon=false;
                                    return false;
                                }
                            }
                        });
                        
                        $(document).ready(function () {
                            //$('#barscanid').val("222");
                            $('#callbarscanid').focus();
                        });
                    </script>