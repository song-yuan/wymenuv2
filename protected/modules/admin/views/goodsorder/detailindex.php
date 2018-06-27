<div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">
    <div class="modal-header">
        <h4 class="modal-title">发货单页脚编辑</h4>
    </div>
    <div class="modal-body">
        <div class="portlet-body" id="table-manage">
            <div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">

                <table id="nb_bottom" style="width: 100%;">
                    <tr class='indexx'>
                        <td style="border:1px solid #ccc!important;width:10%;">排序</td>
                        <td style="border:1px solid #ccc!important;width:80%;">内容</td>
                        <td style="border:1px solid #ccc!important;width:10%;">操作</td>
                    </tr>
                    <?php if ($bottoms): ?>
                        <?php foreach($bottoms as $key => $bottom): ?>
                            <tr class='indexx'>
                                <td style="border:1px solid #ccc!important;width:10%;"><input style='color:red;font-weight:900;width:100%;' name="sort_no[]" value='<?php echo $bottom['sort_no']; ?>'></td>
                                <td style="border:1px solid #ccc!important;width:10%;">
                                    <input type="text" name="content[]" value="<?php echo $bottom['content']; ?>" class="form-control" lid="<?php echo $bottom['lid']; ?>">
                                </td>
                                <td style="border:1px solid #ccc!important;width:10%;">
                                    <i class="fa fa-plus btn btn-xs green add_btn"></i>
                                    <i class="fa fa-times btn btn-xs red btn_delete del" val="<?php echo $bottom['lid']; ?>"></i>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <!-- 新添加标签 -->
                    <?php else: ?>
                        <tr class='indexx'>
                            <td style="border:1px solid #ccc!important;width:10%;">
                                <input style='color:red;font-weight:900;width:100%;' name="sort_no[]" value='1'>
                            </td>
                            <td style="border:1px solid #ccc!important;width:80%;">
                                <input type="text" name="content[]" value="<?php  ?>" lid="" class="form-control">
                            </td>
                            <td style="border:1px solid #ccc!important;width:10%;">
                                <i class="fa fa-plus btn btn-xs green add_btn"></i>
                                <i class="fa fa-times btn btn-xs red btn_delete" val=''></i>
                            </td>
                        </tr>
                    <?php endif; ?>
                </table>
                <!-- <input type="submit" value="提交保存" class="btn blue" id="btn1"> -->
                <?php //$this->endWidget(); ?>

            </div>
        </div>
        <div class="modal-footer">
            <button id="printall" type="button" class="btn blue">确认</button>
            <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
        </div>
        <span style="color:red;">注意 : 顺序将会影响页脚显示的次序</span>
    </div>

</div>

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
    <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','商城设置'),'url'=>$this->createUrl('tmall/list' , array('companyId'=>$this->companyId,'type'=>0))),array('word'=>yii::t('app','发货单列表'),'url'=>$this->createUrl('goodsinvoice/goodsinvoice' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','发货单明细'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('goodsinvoice/goodsinvoice' , array('companyId' => $this->companyId)))));?>

    <!-- END PAGE HEADER-->
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
        <div class="col-md-12">
            <!-- BEGIN EXAMPLE TABLE PORTLET-->
            <div class="portlet box purple">
                <div class="portlet-title">
                    <div class="caption"><i class="fa fa-globe"></i><?php echo $model['company_name'];?> => <?php echo yii::t('app','发货单明细列表');?></div>
                    <div class="actions">
                        <div class="btn-group">
                            <span class="btn blue" id="edit" lid="<?php echo $goid; ?>"><?php echo yii::t('app','编辑页脚');?></span>
                        </div>
                        <div class="btn-group">
                            <span class="btn yellow" id="excel" lid="<?php echo $goid; ?>"><?php echo yii::t('app','导出Excel');?></span>
                        </div>
                        <div class="btn-group">
                            <span class="btn red" id="btnPrint" ><?php echo yii::t('app','打印');?></span>
                        </div>
                    </div>
                </div>
                <div id="printArea" >
                    <style>
                        td{border:1px solid black!important;}
                    </style>
                    <?php if($model): $plid = $model['lid'];$status = $model['status'];?>
                        <div style="vertical-align:middle;text-align: center;" <?php echo $plid;?>>
                            <div class="actions" style="font-size: 20px;">
                                <span><?php echo $model['company_name'];?>食品销售单</span>
                                <span style="display:inline-block;float: right;margin-right:50px;font-size: 6px;margin-top: 10px;">1/1</span>
                            </div>

                            <div class="row" style="font-size: 10px; text-align: left;">
                                <span class="col-xs-3">销售日期：<?php echo date('Y-m-d',time());?> </span>
                                <span class="col-xs-3">状态：<?php  switch ($model['pay_status']){case 0: echo '未支付';break;case 1: echo '已支付';break;default:echo '';break;}?> </span>
                                <span class="col-xs-3">配送方式：<?php switch ($model['sent_type']){case 1: echo '自配送';break;case 3: echo '第三方物流';break;default:echo '';break;}?> </span>
                            </div>
                            <div class="row" style="font-size: 10px; text-align: left;">
                                <?php if ($model['sent_type'] == 3): ?>
                                    <span class="col-xs-3">快递公司:<?php echo $model['sent_personnel']?> </span>
                                    <span class="col-xs-3">快递单号：<?php echo $model['mobile'];?> </span>
                                    <span class="col-xs-3">配送员：<?php echo $model['sent_personnel_2'];?> </span>
                                    <span class="col-xs-3">电话：<?php echo $model['mobile_2'];?> </span>
                                <?php elseif ($model['sent_type'] == 1):?>
                                    <span class="col-xs-6">配送员：<?php echo $model['sent_personnel'];?> </span>
                                    <span class="col-xs-6">电话：<?php echo $model['mobile'];?> </span>
                                <?php endif;?>
                            </div>
                            <div class="actions row" style="font-size: 10px; text-align: left;">
                                <span class="col-xs-4">订单编号：<?php echo $model['goods_order_accountno'];?> </span>
                                <span class="col-xs-3">店名：<?php echo trim($model['dianming']);?> </span>
                                <span class="col-xs-2">收货人：<?php echo trim($model['ganame']);?> </span>
                                <span class="col-xs-3">联系电话：<?php echo trim($model['amobile']);?> </span>
                            </div>
                            <div class="actions row" style="font-size: 10px; text-align: left;">
                                <span class="col-xs-8">收货地址：<?php echo trim($model['pcc']).' '.$model['street'];?> </span>
                            </div>
                        </div>
                    <?php endif;?>
                    <div class="" id="table-manage">
                        <table class="table table-striped table-bordered table-hover" id="sample_1" style="font-size: 8px;">
                            <thead>
                            <tr style="background: lightblue;font-size: 8px;">
                                <td style="font-size: 6px;padding:2px;" class="table-checkbox"><?php echo yii::t('app','序号');?></th>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','商品编号');?></td>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','商品名称');?></td>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','单位');?></td>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','数量');?></td>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','单价');?></td>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','金额');?></td>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','备注');?></td>
                                <td style="font-size: 6px;padding:2px;"><?php echo yii::t('app','规格');?></td>

                            </tr>
                            </thead>
                            <tbody style="font-size: 6px;font-weight:0;">
                            <?php if($models): $num = 1; $sum = 0; $sumP = 0; ?>
                                <?php foreach ($models as $key => $value):?>
                                <tr class="odd gradeX">
                                    <td  style="padding:2px;"colspan="9"><?php echo $key; ?></td>
                                </tr>
                                <?php foreach ($value as $key => $model): ?>
                                    <tr class="odd gradeX">
                                        <td  style="padding:2px;"><?php echo $num; $num++; ?></td>
                                        <td  style="padding:2px;"><?php echo $model['erp_code'];  ?></td>
                                        <td style="padding:2px;" width=""><?php echo $model['goods_name'] ;?></td>
                                        <td  style="padding:2px;"><?php echo $model['goods_unit'];  ?></td>
                                        <td  style="padding:2px;"><?php echo  sprintf('%.2f',$model['num']); $sum+=sprintf('%.2f',$model['num']);?></td>
                                        <td  style="padding:2px;"><?php echo $model['price'] ;?></td>
                                        <td  style="padding:2px;"><?php echo sprintf('%.2f',$model['price']*$model['num']); $sumP+=$model['price']*$model['num']; ?></td>
                                        <td  style="padding:2px;"><?php echo '' ;?></td>
                                        <td  style="padding:2px;"><?php echo $model['unit_name'] ;?></td>
                                    </tr>

                                <?php endforeach;?>
                            <?php endforeach;?>

                                <tr class="odd gradeX">
                                    <td  style="padding:2px;"><?php echo '合计'; ?></td>
                                    <td  style="padding:2px;"><?php echo '';  ?></td>
                                    <td  style="padding:2px;" width=""><?php echo '';?></td>
                                    <td  style="padding:2px;"><?php echo '';  ?></td>
                                    <td  style="padding:2px;"><?php echo sprintf('%.2f',$sum) ;?></td>
                                    <td  style="padding:2px;"><?php echo  '';?></td>
                                    <td  style="padding:2px;"><?php echo sprintf('%.2f',$sumP );?></td>
                                    <td  style="padding:2px;"><?php echo '' ;?></td>
                                    <td  style="padding:2px;"><?php echo '' ;?></td>

                                </tr>
                                <?php function cny($ns) {
                                    static $cnums=array("零","壹","贰","叁","肆","伍","陆","柒","捌","玖"),
                                    $cnyunits=array("圆","角","分"),
                                    $grees=array("拾","佰","仟","万","拾","佰","仟","亿");
                                    list($ns1,$ns2)=explode(".",$ns,2);
                                    $ns2=array_filter(array($ns2[1],$ns2[0]));
                                    $ret=array_merge($ns2,array(implode("",_cny_map_unit(str_split($ns1),$grees)),""));
                                    $ret=implode("",array_reverse(_cny_map_unit($ret,$cnyunits)));
                                    return str_replace(array_keys($cnums),$cnums,$ret);
                                }
                                function _cny_map_unit($list,$units) {
                                    $ul=count($units);
                                    $xs=array();
                                    foreach (array_reverse($list) as $x) {
                                        $l=count($xs);
                                        if ($x!="0" || !($l%4)) {
                                            $index = ($l-1)%$ul >=0 ? $units[($l-1)%$ul] : '';
                                            $n=($x=='0'?'':$x).($index);
                                        }
                                        else $n=is_numeric($xs[0][0])?$x:'';
                                        array_unshift($xs,$n);
                                    }
                                    return $xs;
                                }
                                $str_yuan=cny(sprintf('%.2f',$sumP )); ?>
                                <tr class="odd gradeX">
                                    <td  style="padding:2px;"colspan="9"><?php echo '大写金额: '.$str_yuan; ?></td>

                                </tr>
                            <?php else:?>
                                <tr><td><?php echo yii::t('app','还没有添加详细产品');?></td></tr>
                            <?php endif;?>
                            </tbody>
                        </table>
                    </div>
                    <div style="">
                        <?php if ($bottoms): ?>
                            <?php foreach($bottoms as $key => $bottom): ?>
                                <div class="row" style="font-size: 6px; text-align: left;">
                                    <span class="col-xs-12"><?php echo $bottom['content']; ?></span>
                                </div>
                            <?php endforeach;?>
                        <?php endif;?>
                    </div>
                </div>
                <table class="table table-striped table-bordered table-hover">
                    <tr>
                        <input id="changestock" type="hidden" value='0'/>
                        <input id="stocks" type="hidden" value ="<?php echo $plid; ?>"/>
                        <?php if($status ==0):?>
                            <td colspan="20" style="text-align: right;border:1px solid #ccc!important;">
                                <input type="button" disabled class="btn " value="正在出库..." />&nbsp;
                                <input id="goods_invoiced" type="button" class="btn green" value="确认出库" />&nbsp;
                            </td>
                        <?php elseif($status ==1):?>
                            <td colspan="20" style="text-align: right;border:1px solid #ccc!important;">
                                <input type="button" class="btn blue" disabled value="运输中..." />&nbsp;
                            </td>
                        <?php else:?>
                            <td colspan="20" style="text-align: right;border:1px solid #ccc!important;">
                                <input type="button" class="btn pink" disabled value="已签收" />&nbsp;
                            </td>
                        <?php endif;?>
                    </tr>
                </table>
            </div>
            <!-- END EXAMPLE TABLE PORTLET-->
        </div>
    </div>

    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery-1.10.2.min.js');?>
    <?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/jquery.PrintArea.js');?>
    <!-- END PAGE CONTENT-->
    <script type="text/javascript">
        $("#btnPrint").click(function(){
            $("#printArea").printArea();
        });

        $(document).ready(function(){
        //$(document).ready(function(){...} === $(function(){...});两者同一个意思

            $('#goods_invoiced').click(function(){
                var pid = $('#stocks').val();
                if(confirm('确认商品已出库？')){
                    $.ajax({
                        url:'<?php echo $this->createUrl('goodsinvoice/store',array('companyId'=>$this->companyId));?>',
                        data:{pid:pid},
                        success:function(data){
                            var msg = eval("("+data+")");
                            if(msg.status=='success'){
                                layer.msg(msg.msg);
                            }else{
                                layer.msg('生成失败');
                            }
                            history.go(0);
                        }
                    });
                }
            });


            $('#excel').click(function excel(){
                var goid = $(this).attr('lid');
                // alert(goid);
                if(confirm('确认导出并且下载Excel文件吗？')){
                    location.href="<?php echo $this->createUrl('goodsinvoice/detailindexExport' , array('companyId'=>$this->companyId));?>/goid/"+goid;
                    // location.href="www.baidu.com";
                }
            });

            //编辑页脚
            $("#edit").on('click',function() {
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
                }else{
                    return false;
                }
            });

            $("#printall").on("click",function(){
                var contents =new Array();
                $('input[name="sort_no[]"]').each(function() {
                    var sort_no = $(this).val();
                    var content = $(this).parent('td').next('td').children('input[name="content[]"]').val();
                    var lid = $(this).parent('td').next('td').children('input[name="content[]"]').attr('lid');
                    contents.push(sort_no+'_'+content+'_'+lid);
                });
                var acon = contents.join(',');
                // console.log(acon);
                $.ajax({
                    url: "<?php echo $this->createUrl('goodsinvoice/addbottom' , array('companyId'=>$this->companyId)) ?>",
                    type: 'POST',
                    dataType: 'json',
                    data: {acon: acon},
                    success: function(data){
                        if(data){
                            layer.msg('编辑成功,请稍后.');
                            location.reload();
                        }else{
                            layer.msg('编辑失败,请重新编辑.');
                        }
                    }
                });
            });

            $("#closeall").on('click',function(){
                layer.closeAll();
                layer_index_printerportlist = 0;
            });

            i=0;
            y =$('.indexx').length;
            $(".add_btn").live('click',function() {
                var x=0;
                if ($('input:radio[name="print_bar"]:checked').val()==1) {
                    x = 1;
                }
                if ($('input:radio[name="print_date"]:checked').val()==1) {
                    x = x + 1;
                }
                i++;
                if (x+y+i<6) {
                    $("#nb_bottom").append(
                        "<tr class='indexx'><td style='width:10%;border:1px solid #ccc!important;'><input style='color:red;font-weight:900;width:100%;' name='sort_no[]' value='"+(x+i+y-1)+"'></td><td style='border:1px solid #ccc!important;'><input type='text' name='content[]' lid='' value='' class='form-control'></td><td style='border:1px solid #ccc!important;'>" +
                        "<i class='fa fa-times btn btn-xs red btn_delete' val=''></i>" +
                        "</td>" +
                        "</tr>"
                    );
                }else{
                    --i;
                    layer.msg('行数不能超过4个!',{icon: 5});
                }

            });

            $(".btn_delete").live('click',function(){
                if (confirm('确认要删除这一列吗?')) {
                    var lid = $(this).attr('val');
                    if (lid!='') {
                        $(this).attr('id','aa');
                        $.ajax({
                            url: "<?php echo $this->createUrl('goodsinvoice/delete' , array('companyId'=>$this->companyId)) ?>",
                            type: 'POST',
                            dataType: 'json',
                            data: {lid: lid},
                            success: function(data){
                                if(data){
                                    layer.msg('删除成功,请稍后.');
                                    $('#aa').parent().parent().remove();
                                    location.reload();
                                }else{
                                    layer.msg('删除失败,请重新删除.');
                                }
                            }
                        });
                    }else{
                        $(this).parent().parent().remove();
                    }
                }
            });
        });
    </script>