<style type="text/css">
        
        .popBox1{
            margin: 50px auto;
            text-align: center;
            width: 40em;
            height: 20em;
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
                                                            <span id="site_button_cancel"><?php echo yii::t('app','关 闭');?></span>
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
                                                                    <div class="caption"><i class="fa fa-cogs"></i><span id="selectsite">请选择餐桌：</span></div>
                                                                        <div class="col-md-3">
                                                                                <input id="barscanid" type="text" class="form-control" placeholder="<?php echo yii::t('app','扫描小票条码，快速查看订单');?>">
                                                                                
                                                                        </div>
                                                                        <div class="actions">
                                                                            
                                                                            <a id="order_list" class="btn green"><i class="fa fa-archive"></i> <?php echo yii::t('app','点单界面');?></a>
                                                                            <a  href="<?php echo $this->createUrl('orderManagement/notPay',array('companyId' => $this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>" class='btn green'  ><?php echo yii::t('app','今日订单');?></a>
                                                                            <a  href="<?php echo $this->createUrl('productClean/index',array('companyId' => $this->companyId,'typeId'=>'product','from'=>'home'));?>" class="btn green"><i class="fa fa-chain-broken"></i> <?php echo yii::t('app','快速沽清');?></a>
                                                                            <a  href="<?php echo $this->createUrl('member/index',array('companyId' => $this->companyId,'from'=>'home'));?>" class="btn green"><i class="fa fa-chain-broken"></i> <?php echo yii::t('app','会员管理');?></a>
                                                                        </div>
								</div>
								<div class="portlet-body" id="table-manage">
				
                                                                    <div class="portlet-body site_list">
                                                                                <ul>
                                                                                    <li class="modalaction bg-red" typeid="queue" showbutton="queue_reserve" style="width:4.0em;"><span style="font-size:20px;">保留号码</span></li>
                                                                                    <?php $hasfree=0;$haswaiting=0;
                                                                                            //if($typeId == 'queue'): ?>
                                                                                        <?php
                                                                                            if(!empty($queueModels)):
                                                                                                $temptype=0;
                                                                                                foreach ($queueModels as $model):?>
                                                                                                    <?php if($temptype!=$model["typeid"]): ?>
                                                                                                        <li class="modalaction bg-blue" typeid="queue" showbutton="no" style="width:4.0em;"><span style="font-size:20px;"><?php echo $model["name"]; ?></span></li>
                                                                                                    <?php 
                                                                                                        $temptype=$model["typeid"];
                                                                                                        endif;
                                                                                                        $queuepersons=empty($model["queuepersons"])?0:$model["queuepersons"];
                                                                                                        $sitefree=empty($model["sitefree"])?0:$model["sitefree"];
                                                                                                        if($queuepersons>0){$haswaiting=1;};
                                                                                                        if($sitefree>0){$hasfree=1;};
                                                                                                        ?>
                                                                                                        <li class="modalaction <?php if($queuepersons>0 && $sitefree==0) echo 'bg-yellow'; elseif($queuepersons>0 && $sitefree>0) echo 'bg-green';?>" typeid="queue" showbutton="queue_call" status="q" sid="<?php echo $model["splid"]; ?>"  istemp="<?php echo $model["typeid"]; ?>" splid=<?php echo $model["splid"];?>>
                                                                                                            <span style="font-size: 20px;" typename="sitefree">空座:<?php echo $sitefree; ?></span>
                                                                                                            <br><span style="font-size: 20px;" typename="queuenum">排队:<?php echo $queuepersons; ?></span>
                                                                                                            <br><?php echo $model["min"]."-".$model["max"]; ?>
                                                                                                        </li>
                                                                                        <?php                                                                                                 
                                                                                                endforeach;
                                                                                            endif;?>
                                                                                    <?php //elseif($typeId == 'tempsite'): ?>
                                                                                        <li class="modalaction bg_add" typeid="tempsite" istemp="1" status="0" sid="0" shname="<?php echo yii::t('app','新增临时台');?>"></li>
                                                                                        <?php
                                                                                            if(!empty($tempsiteModels)):
                                                                                                $tempnumber=0;
                                                                                                foreach ($tempsiteModels as $model):?>
                                                                                                    <?php if($tempnumber!=$model->number): ?>
                                                                                                        <li class="modalaction bg-red" typeid="tempsite" showbutton="yes" style="width:4.0em;"><span style="font-size:20px;"><?php echo $model->number; ?>人</span></li>
                                                                                                    <?php 
                                                                                                        $tempnumber=$model->number;
                                                                                                        endif;?>
                                                                                                        <li class="modalaction <?php if($model->status=='1') echo 'bg-yellow'; elseif($model->status=='2') echo 'bg-blue'; elseif($model->status=='3') echo 'bg-green';?>" typeid="tempsite" showbutton="yes" istemp="1" status=<?php echo $model->status;?> sid=<?php echo $model->site_id;?> shname="<?php echo $model->site_id%1000;?>"><span style="font-size: 20px;"><?php echo $model->site_id%1000;?>&nbsp;</span><br><?php echo $model->update_at;?></li>
                                                                                        <?php                                                                                                 
                                                                                                endforeach;
                                                                                            endif;?>
                                                                                    <?php //else:?>
                                                                                        <?php foreach ($models as $model):?>
                                                                                                        <li class="modalaction <?php if($model->status=='1') echo 'bg-yellow'; elseif($model->status=='2') echo 'bg-blue'; elseif($model->status=='3') echo 'bg-green';?>" typeid="<?php echo $model->type_id; ?>" showbutton="yes" istemp="0" status=<?php echo $model->status;?> sid=<?php echo $model->lid;?> shname="<?php echo $model->serial;?>"><span style="font-size: 20px;"><?php echo $model->serial;?>&nbsp;</span><span typename="updateat"><?php echo '<br>'.$model->update_at;?></span></li>
                                                                                        <?php endforeach;?>
                                                                                    <?php //endif;?>                                                                                
                                                                                </ul>
                                                                        </div>
                                                                    </div>
							</div>
							<!-- END EXAMPLE TABLE PORTLET-->												
                                                        <!-------------queue_reserve----------->
                                                        <div id="queue_reserve" style="display:none;">
                                                            <div style="text-align:center;width: 95%;margin:1.0em;">
                                                                <input style="margin:1.0em;width: 10.0em;" type="button" class="btn green" id="member_card_pay" value="<?php echo yii::t('app','关  闭');?>">
                                                            </div>
                                                            <div style="width: 95%;margin:2% 2% 2% 2%;font-size: 1.5em;border:1px solid red;display: inline-block;">
                                                                <DIV style="float: left;width:80px;padding: 5px;background-color: #0099FF;margin:10px;text-align: center;">A3001</DIV>
                                                                <DIV style="float: left;width:80px;padding: 5px;background-color: #0099FF;margin:10px;text-align: center;">A3001</DIV>
                                                                <DIV style="float: left;width:80px;padding: 5px;background-color: #0099FF;margin:10px;text-align: center;">A3001</DIV>
                                                                <DIV style="float: left;width:80px;padding: 5px;background-color: #0099FF;margin:10px;text-align: center;">A3001</DIV>
                                                                
                                                            </div>
                                                            
                                                        </div>
                                                        <!-------------queue_call----------->
                                                        <div id="queue_call" style="display:none;">
                                                            <div style="width: 100%;background-color: #00FFFFFF;display: inline-block;height:100%;position: fixed;overflow:scroll;">
                                                                <div style="width: 62%;margin:4.0em;font-size: 1.5em;float: left;">
                                                                    <DIV style="float:left;width:95%;font-size: 1.5em;text-align: center;">
                                                                        <label style="font-size:60px;">请卡座</label><br>
                                                                        <label style="font-size:90px;color:red;font-weight:900;">A3001号</label><br>
                                                                        <label style="font-size:60px;">宾客，前来就餐！</label>
                                                                    </DIV>  
                                                                    <DIV style="position: absolute;width:60%;font-size: 1.5em;text-align: center;bottom:10px;">
                                                                        <marquee behavior="scroll">我要点单系统，由上海物易网络科技有限公司提供！</marquee>
                                                                    </DIV>
                                                                </div>
                                                                <div style="text-align: center;width: 28%;position: absolute;top:0px;bottom: 0px;right: 0px;border:1px solid red;background-color: #0099FF;">
                                                                    <div class="site_list">
                                                                    <ul>
                                                                        <li class="modalaction bg-red" typeid="queue" showbutton="queue_reserve" style="width:4.0em;"><span style="font-size:20px;">保留号码</span></li>
                                                                        <?php $hasfree=0;$haswaiting=0;
                                                                                //if($typeId == 'queue'): ?>
                                                                            <?php
                                                                                if(!empty($queueModels)):
                                                                                    $temptype=0;
                                                                                    foreach ($queueModels as $model):?>
                                                                                        <?php if($temptype!=$model["typeid"]): ?>
                                                                                            <li class="modalaction bg-blue" typeid="queue" showbutton="no" style="width:4.0em;"><span style="font-size:20px;"><?php echo $model["name"]; ?></span></li>
                                                                                        <?php 
                                                                                            $temptype=$model["typeid"];
                                                                                            endif;
                                                                                            $queuepersons=empty($model["queuepersons"])?0:$model["queuepersons"];
                                                                                            $sitefree=empty($model["sitefree"])?0:$model["sitefree"];
                                                                                            if($queuepersons>0){$haswaiting=1;};
                                                                                            if($sitefree>0){$hasfree=1;};
                                                                                            ?>
                                                                                            <li class="modalaction <?php if($queuepersons>0 && $sitefree==0) echo 'bg-yellow'; elseif($queuepersons>0 && $sitefree>0) echo 'bg-green';?>" typeid="queue" showbutton="queue_call" status="q" sid="<?php echo $model["splid"]; ?>"  istemp="<?php echo $model["typeid"]; ?>" splid=<?php echo $model["splid"];?>>
                                                                                                <span style="font-size: 20px;" typename="sitefree">空座:<?php echo $sitefree; ?></span>
                                                                                                <br><span style="font-size: 20px;" typename="queuenum">排队:<?php echo $queuepersons; ?></span>
                                                                                                <br><?php echo $model["min"]."-".$model["max"]; ?>
                                                                                            </li>
                                                                            <?php                                                                                                 
                                                                                    endforeach;
                                                                                endif;?>
                                                                    </ul>
                                                                    </div>
                                                                    <div style="font-size:20px;">等位数4<br>
                                                                        <input style="margin:1.0em;width: 150px;" type="button" class="btn green" id="member_card_pay_close" value="呼叫"><br>
                                                                        <input style="margin:0.2em;width: 70px;" type="button" class="btn green" id="member_card_pay_close" value="保留">
                                                                        <input style="margin:0.2em;width: 80px;" type="button" class="btn green" id="member_card_pay_close" value="下一位">
                                                                        <input style="margin:0.2em;width: 70px;" type="button" class="btn green" id="member_card_pay_close" value="关闭">
                                                                    </div><hr>
                                                                    <div style="font-size:15px;">13900001111<input style="margin:1.0em;width: 70px;" type="button" class="btn green" id="member_card_pay_close" value="短信"></div>
                                                                    <div style="font-size:15px;">shelly<input style="margin:1.0em;width: 70px;" type="button" class="btn green" id="member_card_pay_close" value="微信"></div><hr>
                                                                    <img style="width:60%;" src="/wymenuv2/img/top10/company_<?php echo $this->companyId; ?>/wx_barcode.jpg">
                                                                </div>
                                                            </div>
                                                        </div>
					
        <script type="text/javascript">
            gtypeid="<?php echo $typeId; ?>";
            ghasfree=<?php echo $hasfree;?>;
            ghaswaiting=<?php echo $haswaiting;?>;
            var layer_queue_reserve=0;
            var layer_queue_call=0;
            
            $(document).ready(function(){
                //alert(gtypeid);
                $('.modalaction').css('display','none');
                $('.modalaction[typeid='+gtypeid+']').css('display','block');                            
                if(gtypeid=="queue")
                {
                    //alert(ghasfree);alert(ghaswaiting);
                    if(ghasfree>0 && ghaswaiting>0)
                    {
                        if (typeof Androidwymenuprinter == "undefined") {
                            //alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
                        }else{
                            Androidwymenuprinter.padAlarm();
                        }
                    }
                }
            });           

            $('.modalaction').on("click", function(){
                var $modal = $('#portlet-button');
                var pxbox = $('#pxbox_button'); 
                var sid = $(this).attr('sid');
                var status = $(this).attr('status');
                var istemp = $(this).attr('istemp');
                var typeId = $(this).attr('typeid');
                var op=gop;
                var that=$(this);
                if(gop=="switch")
                {
                    if(typeId=="queue")
                    {
                        alert("换台不能选择排队");
                        return;
                    }
                    if(gsistemp==istemp && gssid==sid)
                    {
                        var statu = confirm("<?php echo yii::t('app','放弃本次换台操作吗？');?>");
                        if(!statu){
                            return false;//换台操作
                        }else{
                            gop='';
                            return true;
                        }
                    }
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
                            'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":istemp,"ssid":gssid,"sistemp":gsistemp},
                            'url':'<?php echo $this->createUrl('defaultSite/switchsite',array());?>',
                            'success':function(data){
                                    if(data.status == 0) {
                                            alert(data.message);
                                    } else {
                                            gop="";
                                            gsid = sid;
                                            gistemp = istemp;
                                            gtypeid = typeId;                                            
                                            alert(data.message);
                                            //手动改变二个台子的颜色和状态
                                            var sstatus=$(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").attr("status");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-yellow");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-blue");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-green");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").attr("status","6");
                                            
                                            $(".modalaction[sid="+sid+"][istemp="+istemp+"]").attr("status",sstatus);
                                            if(sstatus=="1")
                                            {
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").addClass("bg-yellow");
                                            }else if(sstatus=="2")
                                            {
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").addClass("bg-blue");
                                            }else if(sstatus=="3")
                                            {
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").addClass("bg-green");
                                            }                                                
//                                            $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>');
                                      
                                    }
                            }
                        });
                        return false;
                        
                    }
                }else if(gop=="union"){
                    if(typeId=="queue")
                    {
                        alert("并台不能选择排队");
                        return;
                    }
                    if(gsistemp==istemp && gssid==sid)
                    {
                        var statu = confirm("<?php echo yii::t('app','放弃本次并台操作吗？');?>");
                        if(!statu){
                            return false;
                        }else{
                            gop='';
                            return true;
                        }
                    }
                    if(('034567'.indexOf(status) >=0))
                    {
                        alert("<?php echo yii::t('app','正在进行并台操作，请选择已经开台、下单的餐桌');?>");
                        return false;
                    }else if(istemp==1)
                    {
                        alert("<?php echo yii::t('app','正在进行并台操作，请选择已经开台、下单的餐桌');?>");
                        return false;
                    }else{
                        var statu = confirm("<?php echo yii::t('app','确定将该餐桌做为并台目标吗？');?>");
                        if(!statu){
                            return false;
                        }
                        $.ajax({
                            'type':'POST',
                            'dataType':'json',
                            'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":istemp,"ssid":gssid,"sistemp":gsistemp},
                            'url':'<?php echo $this->createUrl('defaultSite/unionsite',array());?>',
                            'success':function(data){
                                    if(data.status == 0) {                                            
                                            alert(data.message);
                                    } else {
                                            alert(data.message);
                                            gop="";
                                            gsid = sid;
                                            gistemp = istemp;
                                            gtypeid = typeId;
                                            //手动改变二个台子的颜色和状态
                                            var sstatus=$(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").attr("status");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-yellow");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-blue");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-green");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").attr("status","5");
                                            var tstatus=$(".modalaction[sid="+sid+"][istemp="+istemp+"]").attr("status");
                                            //alert(sstatus); alert(tstatus);
                                            if(sstatus>tstatus)
                                            {
                                                //alert(1111);
                                            	$(".modalaction[sid="+sid+"][istemp="+istemp+"]").removeClass("bg-yellow");
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").removeClass("bg-blue");
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").removeClass("bg-green");
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").attr("status",sstatus);
                                                if(sstatus=="1")
                                                {
                                                    $(".modalaction[sid="+sid+"][istemp="+istemp+"]").addClass("bg-yellow");
                                                }else if(sstatus=="2")
                                                {
                                                    $(".modalaction[sid="+sid+"][istemp="+istemp+"]").addClass("bg-blue");
                                                }else if(sstatus=="3")
                                                {
                                                    $(".modalaction[sid="+sid+"][istemp="+istemp+"]").addClass("bg-green");
                                                }
                                            }
//                                            $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>');                                            
                                    }
                            }
                        });
                        return false;                        
                    }
                }else{
                    gop="";
                    gsid = sid;
                    gistemp = istemp;
                    gtypeid = typeId;
                    var showbutton=that.attr("showbutton");
                    //alert(showbutton);
                    if(showbutton=="no")
                    {
                        return;
                    }
                    else if(showbutton=="queue_call")
                    {
                        if(layer_queue_call!=0)
                        {
                            return;
                        }
                        //alert(layer_index3);
                        layer_queue_call=layer.open({
                         type: 1,
                         shade: true,
                         title: false, //不显示标题
                         area: ['100%', '100%'],
                         content: $('#queue_call'),//$('#productInfo'), //捕获的元素
                         cancel: function(index){
                                layer.close(index);
                                layer_queue_call=0;            
                            }
                        });
                        return;
                    }else if(showbutton=="queue_reserve")
                    {
                        if(layer_queue_reserve!=0)
                        {
                            return;
                        }
                        //alert(layer_index3);
                        layer_queue_reserve=layer.open({
                         type: 1,
                         shade: false,
                         title: false, //不显示标题
                         area: ['100%', '100%'],
                         content: $('#queue_reserve'),//$('#productInfo'), //捕获的元素
                         cancel: function(index){
                                layer.close(index);
                                layer_queue_reserve=0;            
                            }
                        });
                        return;
                    }
                    pxbox.find('.button-content').load('<?php echo $this->createUrl('defaultSite/button',array('companyId'=>$this->companyId));?>/sid/'+sid+'/status/'+status+'/istemp/'+istemp+'/typeId/'+typeId, '', function(){                        
                        pxbox.children("h4").text(that.attr("shname"));
                        $("#tab_sitelist").hide();
                        pxbox.show();
                        //$('#pxbox_button').hide();
                    });
                }
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
           
            $('#order_list').on(event_clicktouchstart,function(){
                if($('.selectProduct').attr("orderid")=="0000000000")
                {
                    alert("请选择一个台号");
                    return false;
                }
                $('#site_row').hide();
                $('#order_row').show();
            });
            
            $(document).ready(function () {
                //$('#barscanid').val("222");
                $('#barscanid').focus();
                
            });
            
            $('#site_button_cancel').on(event_clicktouchstart, function(){//site_button_cancel
                $(this).parent().hide();
                //$('#tabsiteindex').load(tabcurrenturl); 
                //alert(tabcurrenturl);
                $("#tab_sitelist").show();
            });
	</script>
	<!-- END PAGE CONTENT-->
        