
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
 	
     
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','店铺管理'),'url'=>$this->createUrl('companyset/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','双屏下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('companyset/list' , array('companyId' => $this->companyId)))));?>
    <div class="row">
        <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copyscreen-form',
				'action' => $this->createUrl('CopyScreen/StorProduct' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
                                'class' => 'form-horizontal',
                                'enctype' => 'multipart/form-data'
				),
		)); ?>
        <div class="col-md-12">

            <div class="portlet purple box">

                <div class="portlet-title">
                     <div class="caption">
                             <i class="fa fa-globe"></i>
                             <?php echo yii::t('app','双屏下发');?>
                             
                             
                     </div>
                    <div class="actions"> 
                        <div class="btn-group"> 
                                <button type="button" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','双屏下发');?></button>
                        </div>
                    </div>
                </div>
                <div class="portlet-body" id="table-manage">
                <div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
                    <table class="table table-striped table-bordered table-hover" id="sample_1">
                        <thead>
                            <tr>
                                <th class="table-checkbox">
                                    <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" />
                                </th>
                                <th><?php echo yii::t('app','双屏标题');?></th>
                                <th><?php echo yii::t('app','双屏说明');?></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php if($models):?>
                        <?php foreach ($models as $model):?>
                            <tr class="odd gradeX">
                                <td>  
                                    <input type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" lid="<?php echo $model->lid;?>" name="ids[]" /> 
                                </td>
                                <td ><?php echo $model->title;?></td>
                                <td><?php echo $model->desc;?></td>
                            </tr>
                            <?php endforeach;?>
                            <?php endif;?>
                        </tbody>
                        <div style="display: none;">
                            <input type="hidden" id="lid" name="lid" value="" />
                            <input type="hidden" id="dpids" name="dpids" value="" />
                        </div>
                    </table>
                   </div> 
                </div>
            </div>
        </div>
        <?php $this->endWidget(); ?>
    </div>
    
    <div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">		                
        <div class="modal-header">
            <h4 class="modal-title">选择需要双屏下发的店铺</h4>
        </div>
        <div class="modal-body">
            <div class="portlet-body" id="table-manage">  
                <div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
                    <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
                        <?php if($dpids):?>
                        <?php foreach($dpids as $dpid):?>
                            <li style="width:50%;float:left;">
                                    <div style="width:10%;float:left;"><?php echo (int)$dpid['dpid'];?></div>
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
        var aa = document.getElementsByName("ids[]");        
        var codep=new Array();
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                codep += aa[i].getAttribute('lid') +',';
               // layer.msg(aa[i]getAttribute('phs_code'));
            }
        }       
        if(codep!=''){
        	codep = codep.substr(0,codep.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的双屏！！！');?>");
       		return false;
       	}
    
       
        if(window.confirm("确认进行此项操作?双屏下发前，请先确认双屏设置中是否编辑明细！")){
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
	            	$("#lid").val(codep);
	    	        $("#copyscreen-form").submit();
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