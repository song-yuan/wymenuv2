<style>
    .print{
        font-size: 20px;
        margin-top:30px;
        margin-bottom:30px;
    }
    .print span{
        display: inline-block;
        margin-right: 40px;
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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','打印设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','一键下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' => '2',)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <?php $form=$this->beginWidget('CActiveForm', array(
                'id' => 'CopyPrinter-form',
                'action' => $this->createUrl('CopyPrinter/storPrinter' , array('companyId' => $this->companyId)),
                'errorMessageCssClass' => 'help-block',
                'htmlOptions' => array(
                        'class' => 'form-horizontal',
                        'enctype' => 'multipart/form-data'
                ),
            )); ?>
        <div class="col-md-12">
                <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box purple">                
                <div class="portlet-body" id="table-manage">   
                    <div class="print col-md-offset-2 col-md-7">
                        <span>1、打印机 </span>
                        <span>2、厨打方案</span>	
                    </div>
                    <div class="col-md-offset-2 col-md-7">                        
                        <button type="button" id="su" class="btn green" ><?php echo yii::t('app','一键下发');?></button>
                    </div>
                    <div style="display: none;">                       
                        <input type="hidden" id="dpids" name="dpids" value="" />
                    </div>
                </div>
            </div>
			<!-- END EXAMPLE TABLE PORTLET-->
        </div>
        <?php $this->endWidget(); ?>
    </div>
	<!-- END PAGE CONTENT-->
    <div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">		                
        <div class="modal-header">
            <h4 class="modal-title">选择需要下发打印机的店铺</h4>
        </div>
        <div class="modal-body">
            <div class="portlet-body" id="table-manage">  
                <div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
                    <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
                        <?php if($dpids):?>
                        <?php foreach($dpids as $dpid):?>
                            <li style="width:50%;float:left;">
                                    <div style="width:10%;float:left;"><?php echo $a++;?></div>
                                    <div style="width:70%;float:left;"><?php echo $dpid['company_name'];?></div>
                                    <div style="width:10%;float:left;">
                                           <input style="height:20px;" type="checkbox" class="checkdpids" value="<?php echo $dpid['dpid'];?>" name="reportlist[]" />
                                    </div>
                            </li>
                        <?php endforeach;?>
                        <?php endif;?>
                            <li style="width:100%;">
                                    <div style="width:10%;float:left;"></div>
                                    <div style="width:60%;float:left;"></div>
                                    <div style="width:14%;float:right;">
                                           <input style="height:20px;" type="checkbox" class="group-checkable" data-set="#reportlistdiv .checkdpids" />
                                           全选
                                    </div>

                            </li>                                                                       
                    </ul>
                </div>
            </div>
            <div class="modal-footer">
                    <button id="printall" type="button" class="btn blue">确认下发</button>
                    <!-- button id="selectall" type="button" class="btn blue">全选</button> -->
                    <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>

            </div>
        </div>				                	
    </div>        
</div>
<script type="text/javascript">
$("#su").on('click',function() {		        
        
    
       
        if(window.confirm("确认进行此项操作?")){
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
            
            $("#printall").on("click",function(){
	            //alert("暂无权限！！！");
	            var dpids =new Array();
	            var dpids="";
	            $('.checkdpids:checked').each(function(){
	                dpids += $(this).val()+',';
	                //alert(dpids);
	            });
	            if(dpids!=''){
	            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
	            	//alert(dpids);
	            	$("#dpids").val(dpids);
	            	
	    	        $("#CopyPrinter-form").submit();
                    }else{
                            alert("请选择店铺。。。");return;
                        }
                });
	        $("#closeall").on('click',function(){
		        //alert("123");
		        layer.closeAll();
		        layer_index_printerportlist = 0;
		        });
        }else{
			return false;
            }
	});	
</script>	

