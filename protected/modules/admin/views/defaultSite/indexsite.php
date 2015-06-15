<style type="text/css">
        
        .popBox1{
            margin: 50px auto;
            text-align: center;
            width: 40em;
            height: 15em;
            border: 1px solid red;
            background: rgb(245,230,230);
            z-index: 11000;
            display: none;
        }
        .popBox1 h4{
            margin: 0.5em 0em;
        }
        .popBox1 span{
            display: inline-block;
            width: 5em;
            line-height: 3em;
            font-weight: bold;
            color: #fff;
            margin: 1em 1em;
            margin-bottom: 3em;
            font-size: 1em;
            background: rgb(201,65,65);
            border-radius: 5px;
            cursor: pointer;
        }
        .clear{
            clear: both;
        }
    </style>
   
                                                        <div class="popBox1" id="pxbox_button">
                                                            <h4></h4>
                                                            <div class="button-content">

                                                            </div>
                                                            <span onclick="button_cancel(this)"><?php echo yii::t('app','关 闭');?></span>
                                                        </div>
                                                        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
                                                        <div class="modal fade" id="portlet-button" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
                                                        <div class="modal fade" id="portlet-account-btn" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
							<div class="portlet box purple" id="tab_sitelist">
								<div class="portlet-title">
									<div class="caption"><i class="fa fa-cogs"></i><?php echo $title; ?></div>
                                                                        <div class="col-md-3">
                                                                                <input id="barscanid" type="text" class="form-control" placeholder="<?php echo yii::t('app','扫描小票条码，快速查看订单');?>">
                                                                        </div>
                                                                        <div class="actions">
                                                                            <a href="<?php echo $this->createUrl('productClean/index',array('companyId' => $this->companyId,'typeId'=>'product','from'=>'home'));?>" class="btn green"><i class="fa fa-chain-broken"></i> <?php echo yii::t('app','快速沽清');?></a>
                                                                            <div class="btn-group">
                                                                                    <a class="btn green" href="#" data-toggle="dropdown">
                                                                                    <i class="fa fa-archive"></i><?php echo yii::t('app','订单操作');?>
                                                                                    <i class="fa fa-angle-down"></i>
                                                                                    </a>
                                                                                    <ul class="dropdown-menu pull-right">                                                                                    
                                                                                            <li><a href="javascript:;" class='btn-edit'  ><?php echo yii::t('app','今日订单');?></a></li>
                                                                                            <li><a href="javascript:;" class="btn-del"   ><?php echo yii::t('app','支付记录');?></a></li>                                                                                            
                                                                                    </ul>
                                                                            </div>
                                                                            <!--<a href="<?php echo $this->createUrl('default/historyorder' , array('companyId' => $this->companyId));?>" class="btn green"><i class="fa fa-archive"></i> <?php echo yii::t('app','历史订单');?></a>-->
                                                                        </div>
								</div>
								<div class="portlet-body" id="table-manage">
				
                                                                        <div class="portlet-body site_list">
                                                                                <ul>
                                                                                    
                                                                                    <?php if($typeId == 'tempsite'): ?>
                                                                                        <li class="modalaction bg_add" istemp="1" status="0" sid="0"></li>
                                                                                        <?php foreach ($models as $model):?>
                                                                                                <li class="modalaction <?php if($model->status=='1') echo 'bg-yellow'; elseif($model->status=='2') echo 'bg-blue'; elseif($model->status=='3') echo 'bg-green';?>" istemp="1" status=<?php echo $model->status;?> sid=<?php echo $model->site_id;?>><?php echo $model->site_id%1000;?></li>
                                                                                        <?php endforeach;?>
                                                                                    <?php else:?>
                                                                                        <?php foreach ($models as $model):?>
                                                                                                <li class="modalaction <?php if($model->status=='1') echo 'bg-yellow'; elseif($model->status=='2') echo 'bg-blue'; elseif($model->status=='3') echo 'bg-green';?>" istemp="0" status=<?php echo $model->status;?> sid=<?php echo $model->lid;?>><?php echo $model->serial.'<br>'.$model->site_level;?></li>
                                                                                        <?php endforeach;?>
                                                                                    <?php endif;?>
                                                                                    
                                                                                       <!-- <li class="modalaction bg-yellow" istemp="0" status="1" sid="1"> 001 </li>
                                                                                        <li class="modalaction bg-blue" istemp="0" status="2" sid="2"> 002 </li>
                                                                                        <li class="modalaction bg-green" istemp="0" status="3" sid="3"> 003 </li>
                                                                                        <li class="modalaction" istemp="0" status="0" sid="4"> 004 </li>
                                                                                        <li class="modalaction" istemp="0" status="0" sid="5"> 005 </li>
                                                                                        <li class="modalaction" istemp="0" status="0" sid="21">021<br><?php echo yii::t('app','普通包厢普通包厢');?></li>-->
                                                                                                                                                                               
                                                                                </ul>
                                                                        </div>
                                                                    </div>
							</div>
							<!-- END EXAMPLE TABLE PORTLET-->												
						
					
        <script type="text/javascript">
            gssid="<?php echo $ssid; ?>";
            gsistemp="<?php echo $sistemp; ?>";
            gstypeid="<?php echo $stypeId; ?>";
            gop="<?php echo $op; ?>";
            $('.modalaction').on('touchstart', function(){
                var $modal = $('#portlet-button');
                var pxbox = $('#pxbox_button'); 
                var sid = $(this).attr('sid');
                var status = $(this).attr('status');
                var istemp = $(this).attr('istemp');
                var typeId = '<?php echo $typeId; ?>';
                var op="<?php echo $op; ?>";
                var that=$(this);
                if(op=="switch")
                {
                    if(('123'.indexOf(status) >=0))
                    {
                        alert("<?php echo yii::t('app','正在进行换台操作，请选择没有开台、下单的餐桌');?>");
                        return false;
                    }else if(istemp==1)
                    {
                        alert("<?php echo yii::t('app','正在进行换台操作，请选择没有开台、下单的餐桌');?>");
                        return false;
                    }else{
                        var statu = confirm("<?php echo yii::t('app','确定将该餐桌做为换台目标吗？');?>");
                        if(!statu){
                            return false;
                        }
                        $.ajax({
                            'type':'POST',
                            'dataType':'json',
                            'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php if($typeId=='tempsite') echo '1'; else echo '0'; ?>',"ssid":'<?php echo $ssid; ?>',"sistemp":'<?php echo $sistemp; ?>'},
                            'url':'<?php echo $this->createUrl('defaultSite/switchsite',array());?>',
                            'success':function(data){
                                    if(data.status == 0) {
                                            alert(data.message);
                                    } else {
                                            alert(data.message);
                                            location.href='<?php echo $this->createUrl('default/index',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>';
                                    }
                            }
                        });
                        return false;
                        
                    }
                }
                if(op=="union")
                {
                    //alert("<?php echo yii::t('app','正在进行并台操作，请选择已经开台、下单的餐桌');?>");
                    return false;//20150422休息
                    if(('034567'.indexOf(status) >=0))
                    {
                        alert("<?php echo yii::t('app','正在进行并台操作，请选择已经开台、下单的餐桌');?>");
                        return false;
                    }else if(istemp==1)
                    {
                        alert("<?php echo yii::t('app','正在进行并台操作，请选择没有开台、下单的餐桌');?>");
                        return false;
                    }else{
                        var statu = confirm("<?php echo yii::t('app','确定将该餐桌做为换台目标吗？');?>");
                        if(!statu){
                            return false;
                        }
                        $.ajax({
                            'type':'POST',
                            'dataType':'json',
                            'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php if($typeId=='tempsite') echo '1'; else echo '0'; ?>',"ssid":'<?php echo $ssid; ?>',"sistemp":'<?php echo $sistemp; ?>'},
                            'url':'<?php echo $this->createUrl('defaultSite/unionsite',array());?>',
                            'success':function(data){
                                    if(data.status == 0) {
                                            alert(data.message);
                                    } else {
                                            alert(data.message);
                                            location.href='<?php echo $this->createUrl('default/index',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>';
                                    }
                            }
                        });
                        return false;                        
                    }
                }
                //$('#chatAudio')[0].play();
                //$modal.find('.modal-content').load('<?php echo $this->createUrl('defaultSite/button',array('companyId'=>$this->companyId));?>/sid/'+sid+'/status/'+status+'/istemp/'+istemp+'/typeId/'+typeId, '', function(){
                 // $modal.modal();
                  //$modal.show();
                //});
                pxbox.find('.button-content').load('<?php echo $this->createUrl('defaultSite/button',array('companyId'=>$this->companyId));?>/sid/'+sid+'/status/'+status+'/istemp/'+istemp+'/typeId/'+typeId, '', function(){
                    pxbox.children("h4").text(that.text());
                    $("#tab_sitelist").hide();
                    pxbox.show();
                });
            });
            
            $('#barscanid').keyup(function(){
                if($(this).val().length==11)
                {
                    var orderid=$(this).val().substring(1,11);
                    //var loadurl='<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>/orderId/'+orderid;
                    var loadurl='<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>'tempsite'));?>/orderId/'+orderid;
                    location.href=loadurl;
                    //defaultOrder/order/companyId/0000000001/orderId/85/typeId/0000000001
                    //$(this).val("111");
                }
            });
            
            $(document).ready(function () {
                //$('#barscanid').val("222");
                $('#barscanid').focus();
                
            });
            
            function button_cancel(obj){
                $(obj).parent().hide();
                $("#tab_sitelist").show();
            }
	</script>
	<!-- END PAGE CONTENT-->
        