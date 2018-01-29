<style>
    .print{
        font-size: 20px;
        margin-bottom:30px;
    }
    .print span{
        display: inline-block;
        margin-right: 40px;
    }
    legend{
        background: #ccc;
        color:darkblue;
        font-weight: 900;
        margin-bottom: 10px;
    }
    td {
        font-size: 14px!important;
    }
.clearfix:after{content:"";height:0;line-height:0;display:block;visibility:hidden;clear:both;}
.clearfix{zoom:1;}

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
                <div class="portlet-body clearfix:after clearfix" id="table-manage">
                    <div class="print col-md-offset-2 col-md-7 " >
                        

                        <fieldset>
                            <legend>厨打方案设置</legend>
                            <table class="table table-striped table-bordered table-hover" id="sample_2">
                            <?php if($modelss):?>
                                <thead>
                                    <tr>
                                        <th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_2 .checkboxes2" /></th>
                                        <th><?php echo yii::t('app','厨打方案名称');?></th>
                                        <th><?php echo yii::t('app','是否整单打印');?></th>
                                        <th><?php echo yii::t('app','打印份数');?></th>
                                        <th><?php echo yii::t('app','备注');?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                
                                <?php foreach ($modelss as $model):?>
                                    <tr class="odd gradeX">
                                        <td><input type="checkbox" class="checkboxes2" value="<?php echo $model->lid;?>" name="printer_way_ids[]" /></td>
                                        <td ><?php echo $model->name ;?></td>
                                        <td ><?php  if($model->is_onepaper=="1") echo "整单打印"; else echo "分开打印";?></td>
                                        <td ><?php echo $model->list_no ;?></td>
                                        <td><?php echo $model->memo;?></td>
                                    </tr>
                                <?php endforeach;?>
                                </tbody>
                                <?php else:?>
                                <tr><td><?php echo yii::t('app','还没有添加打印方案');?></td></tr>
                                <?php endif;?>
                            </table>
                        </fieldset>
                        <fieldset>
                            <legend>下发方式</legend>
                            <select name="down_type" id="down_type" class="form-control" style="width:150px;display: inline-block;">
                                <option value="1" selected>有覆盖下发</option>
                                <option value="0">无覆盖下发</option>
                            </select>
                            <span style="color:red;display: inline-block;font-size: 14px;">
                                <span id="notice1" style="display: inline-block;"> <b>注:</b>有覆盖下发会将下发店铺中的配置覆盖 !</span>
                                <span id="notice0" style="display: none;"> <b>注:</b>无覆盖下发会跳过已下发过的店铺 !</span>
                            </span>
                        </fieldset>
                    </div>
                    <div class="print col-md-offset-3 col-md-7">
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
                                <div style="width:10%;float:left;">
                                       <input style="height:20px;" type="checkbox" class="checkdpids" value="<?php  echo $dpid['dpid'];?>" id="check<?php echo $a; ?>" name="reportlist[]" />
                                </div>
                                <label for="check<?php echo $a; ?>" style="font-size:1em;width:80%;">
                                    <div style="width:10%;float:left;"><?php echo $a; $a++;?></div>
                                    <div style="width:90%;float:left;"><?php echo $dpid['company_name'];?></div>
                                </label>
                            </li>
                        <?php endforeach;?>
                        <?php endif;?>
                            <li style="width:100%;">
                                <div style="width:10%;float:left;"></div>
                                <div style="width:60%;float:left;"></div>
                                <div style="width:14%;float:right;">
                                       <input style="height:20px;" id="all" type="checkbox" class="group-checkable" data-set="#reportlistdiv .checkdpids" />
                                       <label for="all" style="font-size:1em;">全选</label>
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
    if ( $('.checkboxes2:checked').length>0) {
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
                	// $("#down_type").val();

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
    }else{
        layer.msg('请选择要下发的厨打方案设置');
    }
});



$('#down_type').change(function(event) {
    /* Act on the event */
    if(this.value==1){
        $('#notice1').css({
            display: 'inline-block'
        });
        $('#notice0').css({
            display: 'none'
        });
    }else if(this.value==0){
        $('#notice0').css({
            display: 'inline-block'
        });
        $('#notice1').css({
            display: 'none'
        });
    }
});
</script>

