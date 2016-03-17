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
    .queue_list {
            padding-right:10px;
    }
    .queue_list {
            display:inline-block;
    }
    .queue_list ul {
            padding-left:5px;
            padding-left:0px;
    }
    .queue_list ul li {
            float:left;
            width:8.0em;
            height:6.0em;			
            border: 1px solid #add;
            margin:5px;
            list-style:none;
            text-align:center;
            vertical-align:middle;
    }
    .queueinfolist{
        height:40px;
	width:100%;
	border:1px solid #858fa6;
	background:#4a5775;
	/* CSS3 Styling */
	background:-moz-linear-gradient(top, #606c88, #3f4c6b);
	background:-webkit-gradient(linear, left top, left bottom, from(#606c88), to(#3f4c6b));
	-moz-border-radius:5px;
	-webkit-border-radius:5px;
	border-radius:5px;
	-moz-box-shadow:0px 0px 5px #000;
	-webkit-box-shadow:0px 0px 5px #000;
	box-shadow:0px 0px 5px #000;
	/* Text Styling */
	font-family:'AirstreamRegular', Georgia, 'Times New Roman', serif;
	color:#e5edff;
	text-shadow:0px 0px 5px rgba(0, 0, 0, 0.75);
	font-size:20px;
        margin-bottom: 3px;
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
                                                                            
                                                                            <a id="manul_fresh" class="btn green"><i class="fa fa-cogs"></i> <?php echo yii::t('app','手动刷新');?></a>
                                                                            <a id="order_list" class="btn green"><i class="fa fa-archive"></i> <?php echo yii::t('app','点单界面');?></a>
                                                                            <a id="hexiao" class="btn green"><i class="fa fa-archive"></i> <?php echo yii::t('app','核销界面');?></a>
                                                                            <a  href="<?php echo $this->createUrl('orderManagement/notPay',array('companyId' => $this->companyId,'begin_time'=>date('Y-m-d',time()),'end_time'=>date('Y-m-d',time()),'page'=>1));?>" class='btn green'  ><?php echo yii::t('app','今日订单');?></a>
                                                                            <a  href="<?php echo $this->createUrl('productClean/index',array('companyId' => $this->companyId,'typeId'=>'product','from'=>'home'));?>" class="btn green"><i class="fa fa-chain-broken"></i> <?php echo yii::t('app','快速沽清');?></a>
                                                                            <a  href="<?php echo $this->createUrl('member/index',array('companyId' => $this->companyId,'from'=>'home'));?>" class="btn green"><i class="fa fa-chain-broken"></i> <?php echo yii::t('app','会员管理');?></a>
                                                                        </div>
								</div>
								<div class="portlet-body" id="table-manage">
				
                                                                    <div class="portlet-body site_list">
                                                                                <ul>
                                                                                    <li class="modalaction bg-blue" id="queue_take" typeid="others" showbutton="queue_take" style="display:block;"><span style="font-size:20px;">排队取号</span></li>
                                                                                    <li class="modalaction bg-blue" id="queue_call" typeid="others" showbutton="queue_call" style="display:block;"><span style="font-size:20px;">排队叫号</span></li>
                                                                                    
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
                                                                                                        <li class="modalaction <?php if($model->status=='1') echo 'bg-yellow'; elseif($model->status=='2') echo 'bg-blue'; elseif($model->status=='3') echo 'bg-green';?>" typeid="tempsite" showbutton="yes" istemp="1" status=<?php echo $model->status;?> sid=<?php echo $model->site_id;?> shname="<?php echo $model->site_id%1000;?>"><span style="font-size: 20px;"><?php echo $model->site_id%1000;?>&nbsp;</span><br><?php echo substr($model->update_at,5,11);?></li>
                                                                                        <?php                                                                                                 
                                                                                                endforeach;
                                                                                            endif;?>
                                                                                            <!-- CF外卖 -->
                                                                                            <?php
                                                                                            if(!empty($tempsitewModels)):
                                                                                                $tempnumber=0;
                                                                                                foreach ($tempsitewModels as $model):?>
                                                                                                    <?php if($tempnumber!=$model->number): ?>
                                                                                                        <!--  <li class="modalaction bg-red" typeid="waimai" showbutton="yes" style="width:4.0em;"><span style="font-size:20px;"><?php echo $model->number; ?>人</span></li>
                                                                                                    --><?php 
                                                                                                        $tempnumber=$model->number;
                                                                                                        endif;?>
                                                                                                        <li class="modalaction <?php if($model->order_status=='1') echo 'bg-yellow'; elseif($model->order_status=='2') echo 'bg-blue'; elseif($model->order_status=='3') echo 'bg-green';?>" typeid="waimai" showbutton="yes" istemp="1" status=<?php echo $model->order_status;?> sid=<?php echo $model->site_id;?> shname="<?php echo $model->site_id%1000;?>"><span style="font-size: 20px;"><?php echo $model->site_id%1000;?>&nbsp;</span><span typename="updateat">
                                                                                                                <?php echo '<br>'.substr($model["update_at"],5,11);?></span>
                                                                                                            <div style="width: 100%;background-color:green;height:40%;
                                                                                                                 display:block;">
                                                                                                                <img style="height:90%;" src="<?php echo Yii::app()->request->baseUrl;?>/img/weixin.png" >印</div></li>
                                                                                        <?php                                                                                                 
                                                                                                endforeach;
                                                                                            endif;?>
                                                                                            <!-- CF预约 -->
                                                                                            <?php
                                                                                            if(!empty($tempsiteyModels)):
                                                                                                $tempnumber=0;
                                                                                                foreach ($tempsiteyModels as $model):?>
                                                                                                    <?php if($tempnumber!=$model->number): ?>
                                                                                                       <!-- <li class="modalaction bg-red" typeid="yuyue" showbutton="yes" style="width:4.0em;"><span style="font-size:20px;"><?php echo $model->number; ?>人</span></li>
                                                                                                    -->  <?php 
                                                                                                        $tempnumber=$model->number;
                                                                                                        endif;?>
                                                                                                        <li class="modalaction <?php if($model->order_status=='1') echo 'bg-yellow'; elseif($model->order_status=='2') echo 'bg-blue'; elseif($model->order_status=='3') echo 'bg-green';?>" typeid="yuyue" showbutton="yes" istemp="1" status=<?php echo $model->order_status;?> sid=<?php echo $model->site_id;?> shname="<?php echo $model->site_id%1000;?>"><span style="font-size: 20px;"><?php echo $model->site_id%1000;?>&nbsp;</span><span typename="updateat">
                                                                                                                <?php echo '<br>'.substr($model["update_at"],5,11);?></span>
                                                                                                            <div style="width: 100%;background-color:green;height:40%;
                                                                                                                 display:block;">
                                                                                                                <img style="height:90%;" src="<?php echo Yii::app()->request->baseUrl;?>/img/weixin.png" >印</div></li>
                                                                                        <?php                                                                                                 
                                                                                                endforeach;
                                                                                            endif;?>
                                                                                    <?php //else:?>
                                                                                        <?php foreach ($models as $model):?>
                                                                                                        <li class="modalaction <?php if($model["min_status"]=='1'||$model["status"]=="1") echo 'bg-yellow'; elseif($model["min_status"]=='2') echo 'bg-blue'; elseif($model["min_status"]=='3') echo 'bg-green';?>"
                                                                                                            typeid="<?php echo$model["type_id"]; ?>" showbutton="yes" istemp="0" status=<?php if($model["min_status"]=="0"&& $model["status"]=="1"){echo "1";}elseif($model["min_status"]=="1"){echo "1";};?> maxstatus=<?php echo $model["max_status"];?> sid=<?php echo $model["lid"];?>
                                                                                                            shname="<?php echo $model["serial"];?>"><span style="font-size: 20px;"><?php echo $model["serial"];?>&nbsp;</span><span typename="updateat">
                                                                                                                <?php echo '<br>'.substr($model["update_at"],5,11);?></span>
                                                                                                            <div style="width: 100%;background-color:<?php if($model["newitem"]>0){echo "green"; }else{ echo "";}?>;height:40%;
                                                                                                                 display:<?php if((stripos("12",$model["order_type"])!==false)
                                                                                                                         &&(stripos("123",$model["min_status"])!==false)){echo "block";}else{echo "none";}?>">
                                                                                                                <img style="height:90%;" src="<?php echo Yii::app()->request->baseUrl;?>/img/weixin.png" >印</div></li>
                                                                                        <?php endforeach;?>
                                                                                    <?php //endif;?>                                                                                
                                                                                </ul>
                                                                        </div>
                                                                    </div>
							</div>
							<!-- END EXAMPLE TABLE PORTLET-->												
                                                        
                                                        <!-------------queue_call----------->
                                                        
			<!---------------折扣类型选择------------------>
            <div id="hexiaobox" style="display: none">
            	<div class="modal-header">
                    <h4 id="orderaccountprintresult" style="color:red;"> 请选择需要核销的卡券类型... </h4>                                                    
                </div>
                <div class="modal-footer">
                	<a href="<?php echo $this->createUrl('/admin/gift/code' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','礼品券核销');?></a>
                	<a href="<?php echo $this->createUrl('/admin/wxcard/consume',array('companyId'=>$this->companyId));?>" class="btn red"><i class="fa fa-globe"></i> <?php echo yii::t('app','卡券核销');?></a>
                    <!--<a><button type="button" class="btn green" id="giveup" style="width:10em;"><?php echo yii::t('app','取消核销');?></button></a>
                     <button type="button" class="btn default" id="btn_orderaccount_cancel" style="width:10em;">取消结单</button>
                    <button type="button" class="btn green-stripe" id="btn_orderaccount_reprint" style="width:10em;">重新打印</button>  
                    <button type="button" class="btn green" id="btn_orderaccount_sure" style="width:10em;">确定结单</button>     -->
                </div> 
            </div>		
        <script type="text/javascript">
            gtypeid="<?php echo $typeId; ?>";
            //ghasfree=<?php //echo $hasfree;?>;
            //ghaswaiting=<?php //echo $haswaiting;?>;
            var layer_queue_call=0;
            var layer_index_hexiaobox=0;
            
            $(document).ready(function(){
                //alert(gtypeid);
                $('.modalaction').css('display','none');
                $('.modalaction[typeid='+gtypeid+']').css('display','block');                            
//                if(gtypeid=="others")
//                {
//                    //alert(ghasfree);alert(ghaswaiting);
//                    if(ghasfree>0 && ghaswaiting>0)
//                    {
//                        if (typeof Androidwymenuprinter == "undefined") {
//                            //alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
//                        }else{
//                            Androidwymenuprinter.padAlarm();
//                        }
//                    }
//                }
            });           

            $('#hexiao').on(event_clicktouchstart,function(){
              
                var oprole ="<?php echo Yii::app()->user->role; ?>";
                if(oprole > '2')
                {
                    alert("没有核销权限！");
                    return;
                }
                //alert(curnum);
                //$("#selectproductnumforhurry").val(curnum);
//                 if(orderstatus!="0")//退菜是单个的
//                 {
                    
                        //var lid=$(this).parent().attr("lid");
                        //$('#hurrybox').load("<?php echo $this->createUrl('defaultOrder/addHurryOne',array('companyId'=>$this->companyId));?>/orderDetailId/"+lid);
                        if(layer_index_hexiaobox!=0)
                        {
                            return;
                        }
                        layer_index_hexiaobox=layer.open({
                             type: 1,
                             shade: false,
                             title: false, //不显示标题
                             area: ['30%', '30%'],
                             content: $('#hexiaobox'), //捕获的元素
                             cancel: function(index){
                                 layer.close(index);
                                 layer_index_hexiaobox=0;
                //                        this.content.show();
                //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                            }
                        }); 
                    
//                 }else{
//                     alert("未知错误！！！");
//                 }
            });
            
            
            $('#manul_fresh').on("click",function(){
                //site显示时才做这样的操作
                if($("#tab_sitelist").css("display")=="block")
                {                    
                    var padid="0000000046";
                    if (typeof Androidwymenuprinter == "undefined") {
                        alert("找不到PAD设备");
                        //return false;
                    }else{
                        var padinfo=Androidwymenuprinter.getPadInfo();
                        padid=padinfo.substr(10,10);
                    }
                    $.ajax({
                        url:"/wymenuv2/admin/defaultSite/getSiteAll/companyId/<?php echo $this->companyId; ?>/typeId/"+gtypeid+"/padId/"+padid,
                        type:'GET',
                        timeout:5000,
                        cache:false,
                        async:false,
                        dataType: "json",
                        success:function(msg){
                            //网络连接有错误要报错
                             if(!msg.status)
                             {
                                 alert("网络故障，请稍后重试!");
                                 return;
                             }
                            //document.write(msg.msg);
                            //$('#tabsiteindex').load(tabcurrenturl);
                            //重新修改成用ajax动态加载
                            if(gtypeid=="others")
                            {
                                //获取排队信息，并更新状态,不存在删减的
                                if($("#tab_sitelist").length > 0)
                                {
                                    $.each(msg.models,function(key,value){
                                        var siteobj=$(".modalaction[typeid='others'][sid="+value.splid+"][istemp="+value.typeid+"]");
                                        siteobj.removeClass("bg-yellow");
                                        siteobj.removeClass("bg-green");                                                    
                                        //改变背景颜色///
                                        if(value.queuepersons>0)
                                        {                                                
                                            if(value.sitefree>0)
                                            {
                                                siteobj.addClass("bg-green");                                                    
                                            }else{
                                                siteobj.addClass("bg-yellow");                                                    
                                            }
                                        }
                                        //修改排队数和空位数文字..
                                        if(value.sitefree==null)
                                        {
                                            value.sitefree=0;
                                        }
                                        if(value.queuepersons==null)
                                        {
                                            value.queuepersons=0;
                                        }
                                        siteobj.find("span[typename='sitefree']").text("空座:"+value.sitefree);
                                        siteobj.find("span[typename='queuenum']").text("排队:"+value.queuepersons); 
                                     });
                                 }
                            }else if(gtypeid=="waimai"||gtypeid=="yuyue"){
                                //获取临时座位信息，并更新状态
                                //存在删减临时座位的,暂不修改，以后添加！！                    
                                //....
                                //CF
                                //alert("123");
                            	history.go(0);
                            	//location.href="<?php echo $this->createUrl('default/index' , array('companyId'=>$this->companyId ));?>/typeId/"+gtypeid    
                            	//var loadurl='<?php echo $this->createUrl('default/index',array('companyId'=>$this->companyId,'typeId'=>'waimai'));?>';
                                //location.href=loadurl; 
                            }else{
                                //alert("456");
                                //获取座位信息，并更新状态
                                //不存在删减座位的
                                if($("#tab_sitelist").length > 0)
                                {
                                    $.each(msg.models,function(key,value){
                                        var siteobj=$(".modalaction[typeid="+value.type_id+"][sid="+value.lid+"][istemp=0]");
                                        var nowstatus=value.min_status;
                                        if(value.min_status=="1" || value.status=="1")
                                        {
                                            nowstatus=1;
                                        }
                                        siteobj.attr("status",nowstatus);
                                        siteobj.attr("maxstatus",value.max_status);
                                        siteobj.find("span[typename=updateat]").html("<br>"+value.update_at.substr(5,11));
                                        siteobj.removeClass("bg-yellow");
                                        siteobj.removeClass("bg-blue");
                                        siteobj.removeClass("bg-green");
                                        if(value.min_status=="1" || value.status=="1")
                                        {
                                            siteobj.addClass("bg-yellow");
                                        }else if(value.min_status=="2")
                                        {
                                            siteobj.addClass("bg-blue");
                                        }else if(value.min_status=="3")
                                        {
                                            siteobj.addClass("bg-green");
                                        }
                                        if(("12".indexOf(value.order_type)>=0)
                                                && ("123".indexOf(value.min_status)>=0))
                                        {
                                            siteobj.find("div").show();
                                        }else{
                                            siteobj.find("div").hide();
                                        }
                                        if(value.newitem > 0)
                                        {
                                            siteobj.find("div").css("background-color","green");
                                            //需要打印
                                        }else{
                                            siteobj.find("div").css("background-color","");
                                        }
                                    });
                                }
                                //开始打印任务
                                var printresult=false;
                                var successjobs="00000000";
                                if(typeof(Androidwymenuprinter)=="undefined")
                                {
                                    //return;
                                }
                                var times=0;
                                $.each(msg.ret9arr,function(key,value){
                                    //alert(value);
                                    setTimeout("Androidwymenuprinter.ordercall('"+value+"')", 6000*times+1000 );
                                    times++;
                                });
                                $.each(msg.ret8arr,function(key,value){
                                    //alert(value);
                                    setTimeout("Androidwymenuprinter.paycall('"+value+"')", 6000*times+1000 );
                                    times++;
                                });
                                $.each(msg.modeljobs,function(key,value){
//                                 	alert("1111");alert(value);
//                                 	var str = '';
//                                 	for(var key in value){
// 										str += 'key:'+ key + 'value:'+ value[key];
//                                     	}
//                                 	alert(str);
                                    
                                    //printresult=false;
                                    for(var itemp=1;itemp<4;itemp++)
                                    {
                                        if(printresult)
                                        {
                                            successjobs=successjobs+","+value.jobid;
                                            break;
                                        }
                                        var addressdetail=value.address.split(".");
                                        if(addressdetail[0]=="com")
                                        {
                                            var baudrate=parseInt(addressdetail[2]);
                                            printresult=Androidwymenuprinter.printComJob(value.dpid,value.jobid,addressdetail[1],baudrate);
                                        }else{
                                            //alert(value.dpid);alert(value.jobid);alert(value.address);
                                            printresult=Androidwymenuprinter.printNetJob(value.dpid,value.jobid,value.address);
                                        }                                                                        
                                    }
                                });
//                                     alert("222");
//                                 alert(msg.modeljobs);
                                
                                if("00000000"!=successjobs)
                                {
                                    $.ajax({
                                        url:"/wymenuv2/admin/defaultSite/finshPauseJobs/companyId/<?php echo $this->companyId; ?>/successjobs/"+successjobs,
                                        type:'GET',
                                        timeout:5000,
                                        cache:false,
                                        async:false,
                                        dataType: "json",
                                        success:function(msg){

                                        }
                                    });
                                }
                            }                            
                        },
                        error: function(msg){
                            alert("1网络可能有问题，再试一次！");
                        },
                        complete : function(XMLHttpRequest,status){
                            if(status=='timeout'){
                                alert("2网络可能有问题，再试一次！");                                            
                            }
                        }
                    });               
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
                    if(typeId=="others")
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
                                            
                                            var isblock=$(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").find("div").css("display");
                                            var isbackground=$(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").find("div").css("background-color");
                                            //alert(isblock);alert(isbackground);
                                            $(".modalaction[sid="+sid+"][istemp="+istemp+"]").find("div").css("background-color",isbackground);
                                            $(".modalaction[sid="+sid+"][istemp="+istemp+"]").find("div").css("display",isblock);
                                            //alert("222");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").find("div").hide();
//                                            $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>');
                                      
                                    }
                            }
                        });
                        return false;
                        
                    }
                }else if(gop=="union"){
                    if(typeId=="others")
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
                                            var tstatus=$(".modalaction[sid="+sid+"][istemp="+istemp+"]").attr("status");
                                            var isblock=$(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").find("div").css("display");
                                            var isbackground=$(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").find("div").css("background-color");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-yellow");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-blue");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").removeClass("bg-green");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").attr("status","5");
                                            $(".modalaction[sid="+gssid+"][istemp="+gsistemp+"]").find("div").hide();
                                            //alert(isbackground);
                                            if(isblock=="block")
                                            {
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").find("div").show();
                                            }
                                            if(isbackground!="none")
                                            {
                                                $(".modalaction[sid="+sid+"][istemp="+istemp+"]").find("div").css("background-color",isbackground);
                                            }        
                                            //alert(sstatus); alert(tstatus);
                                            if(sstatus < tstatus)
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
                        return;
                    }
                    else if(showbutton=="queue_take")
                    {                        
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
            
            $('.imgcall').on(event_clicktouchstart, function(){
                alert("imgcall");
            });
            
            $('.imgeat').on(event_clicktouchstart, function(){
                alert("imgeat");
            });
            
            $('.imgpass').on(event_clicktouchstart, function(){
                alert("imgpass");
            });
            
            $('#queue_take').click(function(){
                //var stlid=$(this).attr('lid');
                var randtime=new Date().getTime()+""+Math.round(Math.random()*100);
                var url='<?php echo $this->createUrl('queue/index',array("companyId"=>$this->companyId)); ?>'+'/rand/'+randtime;
                location.href=url;
            });
            $('#queue_call').click(function(){
                //var stlid=$(this).attr('lid');
                var randtime=new Date().getTime()+""+Math.round(Math.random()*100);
                var url='<?php echo $this->createUrl('queue/call',array("companyId"=>$this->companyId)); ?>'+'/rand/'+randtime;
                location.href=url;
            });
	</script>
	<!-- END PAGE CONTENT-->
        