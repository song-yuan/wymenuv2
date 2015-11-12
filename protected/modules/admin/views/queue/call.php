<style>
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
        height:50px;
	width:100%;
        line-height: 50px;
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
	font-size:15px;
        margin-bottom: 3px;
    }
    
</style>		
        <div id="queue_call_layer" style="background: url(wymenuv2/img/bg-white-lock.png) repeat;">
                <div style="width: 100%;background-color: #00FFFFFF;display: inline-block;height:100%;position: fixed;overflow:scroll;">
                    <div style="width: 52%;margin:4.0em;font-size: 1.5em;float: left;">
                        <DIV style="float:left;width:95%;font-size: 1.5em;text-align: center;margin-top:1.0em;">
                            <label style="font-size:40px;">请卡座</label><br>
                            <label style="font-size:90px;color:red;font-weight:900;">A3001号</label><br>
                            <label style="font-size:40px;">前来就餐！</label>
                        </DIV>  
                        <DIV style="position: absolute;width:50%;font-size: 1.5em;text-align: center;bottom:10px;">
                            <marquee behavior="scroll"><a style="color:#ffffff" href="<?php echo $this->createUrl('default/index',array("companyId"=>$companyId));?>">我要点单系统，由上海物易网络科技有限公司提供！</a></marquee>
                        </DIV>
                    </div>
                    <div style="text-align: center;width: 38%;position: absolute;top:0px;bottom: 0px;right: 0px;border:1px solid red;background-color: #add;overflow:scroll;">
                        
                        <table id="queue_pass_list" style="width:100%;display: none;">
                            <tr class="queueinfolist">                                
                                <td colspan="6" style="text-align:right;">                                                                                
                                    <a id="queue_call_btn" class="btn blue" style="margin-right: 9%;"><i class="fa fa-archive"></i>排队叫号>></a>
                                </td>
                            </tr>
                            <tr class="queueinfolist">
                                <td style="width:23%;font-size:15px;"><?php echo "A3002";?></td>
                                <td style="width:10%;">
                                    <div class="imgeat" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>
                                <td style="width:23%;font-size:15px;"><?php echo "A3001";?></td>
                                <td style="width:10%;">
                                    <div class="imgeat" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>
                                <td style="width:23%;font-size:15px;"><?php echo "A3001";?></td>
                                <td style="width:10%;">
                                    <div class="imgeat" style="width:30%;float:left;">
                                        <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                    </div>
                                </td>
                            </tr>
                        </table>
                        
                        <table id="queue_call_list" style="width:100%;">
                            <tr class="queueinfolist">
                                <td style="width:40%;float: left;">座位类型</td>
                                <td style="width:20%;float: left;">等/空</td>
                                <td style="width:40%;float: left;">                                                                                
                                    <a id="queue_pass_btn" class="btn blue"><i class="fa fa-archive"></i>过号记录>></a>
                                </td>
                            </tr>
                            <?php $hasfree=0;$haswaiting=0;
                                    //if($typeId == 'queue'): ?>
                                <?php
                                    if(!empty($queueModels)):
                                        $temptype=0;
                                        foreach ($queueModels as $model):?>
                                            <?php                                                                                             
                                                $queuepersons=empty($model["queuepersons"])?0:$model["queuepersons"];
                                                $sitefree=empty($model["sitefree"])?0:$model["sitefree"];
                                                if($queuepersons>0){$haswaiting=1;};
                                                if($sitefree>0){$hasfree=1;};
                                                ?>
                                                <tr class="queueinfolist">
                                                    <td style="width:40%;float: left;font-size:15px;"><?php echo $model["name"]."/".$model["min"]."-".$model["max"];?></td>
                                                    <td style="width:22%;float: left;">
                                                        <div style="float:left;border: 2px solid green;background-color:#F00;width:50%;">
                                                            <span style="color:#00FFFFFF;padding: 3px;"><?php echo $queuepersons."00";?></span></div>
                                                        <div style="float:left;border: 2px solid green;background-color:#858fa6;width:50%;">
                                                            <span style="color:#00FFFFFF;padding: 3px;"><?php echo $sitefree."00"; ?></span></div></td>
                                                    <td style="width:38%;float: left;">
                                                        <div style="width:100%;text-align:right;">
                                                            <div class="imgcall" style="width:30%;float:left;">
                                                            <img src="/wymenuv2/img/queue/call.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                                            </div>
                                                            <div class="imgeat" style="width:30%;float:left;">
                                                            <img src="/wymenuv2/img/queue/eat.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                                            </div>
                                                            <div class="imgpass" style="width:30%;float:left;">
                                                            <img src="/wymenuv2/img/queue/pass.png" style="width:60px;padding:5px;margin:0 5px 0 5px;">
                                                            </div>
                                                        </div>
                                                    </td>
                                                </tr>                                                                                            
                                <?php                                                                                                 
                                        endforeach;
                                    endif;?>                                                                                                                                                
                        </table>                                                                                                                                        
                    </div>
                </div>
            </div>
	<!-- BEGIN COPYRIGHT -->
	
		<script language="JavaScript" type="text/JavaScript">
                    var layer_index_queueno=0;
                    var intervalQueueList;
                    var companyid=<?php echo $companyId; ?>;
                    var btnlock=false;
                    var event_clicktouchstart="click";
                    var event_clicktouchend="click";
                    if (typeof Androidwymenuprinter == "undefined") {
                        //alert("click");
                          event_clicktouchstart="click";
                          event_clicktouchend="click";
                    }else{
                        //alert("touch");
                        event_clicktouchstart="touchstart";
                        event_clicktouchend="touchend";
                    }
                    function reloadqueuestate()
                    {
                        $.ajax({
                            url:"/wymenuv2/admin/queue/getSitePersonsAll/companyid/"+companyid,
                            type:'GET',
                            timeout:5000,
                            cache:false,
                            async:false,
                            dataType: "json", 
                            success:function(msg){
                                $.each(msg,function(key,value){
                                    var siteobj=$("input[splid="+value.splid+"][stlid="+value.typeid+"]");
                                    if(value.queuepersons==null)
                                    {
                                        value.queuepersons=0;
                                    }
                                    siteobj.val(value.min+'-'+value.max+'人 (等叫:'+value.queuepersons+'组)');
                                 });
                            },
                            error: function(msg){
                                //alert("网络可能有问题，再试一次！");
                            },
                            complete : function(XMLHttpRequest,status){
                                if(status=='timeout'){
                                    //alert("网络可能有问题，再试一次！");                                            
                                }
                            }
                        });
                    }
                    
                    $(document).ready(function(){
                        
                        //叫号后等叫的人数要减少
                        clearInterval(intervalQueueList);
                        intervalQueueList = setInterval(reloadqueuestate,"15000");
                        //reloadqueuestate();
                    });
                    
                    $('.btnSitePersons').click(function(){
                        
                        if(layer_index_queueno!=0)
                        {
                            return;
                        }
                        //出现收银界面
                        $("#queuemobile").text("1");
                        layer_index_queueno=layer.open({
                             type: 1,
                             shade: false,
                             title: false, //不显示标题
                             area: ['70%', '90%'],
                             content: $('#mobilenobox'),//$('#productInfo'), //捕获的元素
                             cancel: function(index){
                                 layer.close(index);
                                 layer_index_queueno=0;
                //                        this.content.show();
                //                        layer.msg('捕获就是从页面已经存在的元素上，包裹layer的结构',{time: 5000});
                             }
                        });
                        $(".btnSitePersons").removeClass("selectsiteperson");
                        $(this).addClass("selectsiteperson");
                        $("#queuepersonrange").text($(this).attr("personrang"));
                        return;
                        ////////////
                        if(btnlock)
                        {
                            return;
                        }else{
                            btnlock=true;
                            setTimeout("btnlock=false", 3000);
                        }
                        var stlid=$(this).attr('stlid');
                        var splid=$(this).attr('splid');
                        var dpid="<?php echo $companyId; ?>";
                        var personrang=$(this).attr('personrang');
                        var that=$(this);
                        var printresulttemp=false;
                        var padid="0000000046";
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("找不到PAD设备");
                            //return false;
                        }else{
                            var padinfo=Androidwymenuprinter.getPadInfo();
                            padid=padinfo.substr(10,10);
                        }
                        //alert(stlid);alert(splid);alert(dpid);alert(personrang);
                        $.ajax({
                            url:"/wymenuv2/admin/queue/getSitePersons/companyid/"+dpid+"/stlid/"+stlid+"/splid/"+splid+'/padid/'+padid,
                            type:'GET',
                            timeout:5000,
                            cache:false,
                            async:false,
                            dataType: "json",
                            success:function(msg){
                                 if(msg.status)
                                 {
                                    that.val(personrang+"人(等叫:"+msg.waitingnum+"组)");                                                                        
                                        var reprint=true;
                                        while(reprint)
                                        {
                                            var addressdetail=msg.address.split(".");
                                            if(addressdetail[0]=="com")
                                           {
                                               var baudrate=parseInt(addressdetail[2]);
                                               //alert(baudrate);
                                                printresulttemp=Androidwymenuprinter.printComJob(dpid,msg.jobid,addressdetail[1],baudrate);
                                            }else{
                                                printresulttemp=Androidwymenuprinter.printNetJob(dpid,msg.jobid,msg.address);
                                            }
//                                            printresulttemp=true;
                                            if(!printresulttemp)
                                            {
                                                var reprint = confirm("打印失败，是否重新打印？");
                                                
                                            }else{
                                                reprint=false;
                                            }                                            
                                        }
                                 }else{
                                     alert(msg.msg);
                                 }
                                  //btnlock=false;
                            },
                            error: function(msg){
                                //alert("网络可能有问题，再试一次！");
                                //btnlock=false;
                            },
                            complete : function(XMLHttpRequest,status){
                                if(status=='timeout'){
                                    //alert("网络可能有问题，再试一次！");                                            
                                }
                                //btnlock=false;
                            }
                        });
                        //btnlock=false;
                    });
                    
                    $('.mobileinput').on(event_clicktouchend,'li',function(){
                        var num=$(this).text();
                        var deal=$(this).attr("deal");
                        var mobileno=$("#queuemobile").text();
                        if(deal=="A")
                        {
                            if(mobileno.length<11)
                            {
                                mobileno=mobileno+num;
                                $("#queuemobile").text(mobileno);
                            }
                        }else if(deal=="delone")
                        {
                            if(mobileno.length>1)
                            {                              
                                mobileno=mobileno.substr(0,mobileno.length-1);
                                $("#queuemobile").text(mobileno);
                            }
                        }else if(deal=="delall")
                        {
                            $("#queuemobile").text("1");
                        }else
                        {
                            return;
                        }
                    });
                    
                    $('#queueno').on(event_clicktouchstart,function(){
//                        ////////////
//                        if(btnlock)
//                        {
//                            return;
//                        }else{
//                            btnlock=true;
//                            setTimeout("btnlock=false", 3000);
//                        }
                        var that=$(".selectsiteperson");
                        //alert(that.attr('stlid'));
                        var stlid=that.attr('stlid');
                        var splid=that.attr('splid');
                        var dpid="<?php echo $companyId; ?>";
                        var personrang=that.attr('personrang');
                        var printresulttemp=false;
                        var padid="0000000046";
                        if (typeof Androidwymenuprinter == "undefined") {
                            alert("找不到PAD设备");
                            //return false;
                        }else{
                            var padinfo=Androidwymenuprinter.getPadInfo();
                            padid=padinfo.substr(10,10);
                        }
                        var mobileno=$("#queuemobile").text();//格式未做判断，发送短信时再判断。
                        
                        $.ajax({
                            url:"/wymenuv2/admin/queue/getSitePersons/companyid/"+dpid+"/stlid/"+stlid+"/splid/"+splid+'/padid/'+padid+'/mobileno/'+mobileno,
                            type:'GET',
                            timeout:5000,
                            cache:false,
                            async:false,
                            dataType: "json",
                            success:function(msg){
                                 if(msg.status)
                                 {
                                    that.val(personrang+"人(等叫:"+msg.waitingnum+"组)");                                                                        
                                        var reprint=true;
                                        while(reprint)
                                        {
                                            var addressdetail=msg.address.split(".");
                                           if(addressdetail[0]=="com")
                                           {
                                               var baudrate=parseInt(addressdetail[2]);
                                               //alert(baudrate);
                                                printresulttemp=Androidwymenuprinter.printComJob(dpid,msg.jobid,addressdetail[1],baudrate);
                                            }else{
                                                //alert(dpid);alert(msg.jobid);alert(msg.address);
                                                printresulttemp=Androidwymenuprinter.printNetJob(dpid,msg.jobid,msg.address);
                                            }
//                                            printresulttemp=true;
                                            if(!printresulttemp)
                                            {
                                                var reprint = confirm("打印失败，是否重新打印？");                                                
                                            }else{
                                                reprint=false;
                                            }                                            
                                        }
                                 }else{
                                     alert(msg.msg);
                                 }
                                 layer.close(layer_index_queueno);
                                 layer_index_queueno=0;
                                  //btnlock=false;
                            },
                            error: function(msg){
                                alert("网络可能有问题，再试一次！");
                                //btnlock=false;
                            },
                            complete : function(XMLHttpRequest,status){
                                if(status=='timeout'){
                                    alert("网络可能有问题，再试一次！");                                            
                                }
                                //btnlock=false;
                            }
                        });
                        
                    });
                    
                    $('#queue_pass_btn').on(event_clicktouchstart,function(){
                        $('#queue_call_list').hide();
                        $('#queue_pass_list').show();
                    });
                    $('#queue_call_btn').on(event_clicktouchstart,function(){
                        $('#queue_pass_list').hide();
                        $('#queue_call_list').show();                        
                    });
                    
                </script>
                