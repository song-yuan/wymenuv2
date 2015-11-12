<style>
    .queuesitetypelist input{
        font-size: 18px;
        width: 40%;
        height: 70px;
        background-color: darkseagreen;
        float: left;
        margin: 2%;
        word-wrap:break-word;
    }
    .queuesitepersonslist input{
        font-size: 15px;
        width: 40%;
        height: 70px;
        background-color:skyblue;
        float: left;
        margin: 2%;
        word-wrap:break-word;
    }
    .queueactive{
        background-color: red !important;
    }
    .mobileinput {
        width: 96%;
        display: inline-block;
        margin-top: 10px;
    }
    
    .mobileinput ul li {
    	line-height:50px;
        float: left;
        width: 28%;
        height: 2.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 20px;
        font-weight: 700;
        background-color: #add;
        list-style: none;
        text-align: center;
        vertical-align: middle;
      }
      .queueinfolist{
        height:50px;
	width:100%;
	border:1px solid #858fa6;
	background:#4a5775;
        line-height: 50px;
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
            <div style="text-align: center;width: 25%;position: absolute;top:0px;bottom: 0px;left: 0px;border:1px solid red;background-color: #add;">
                        <table style="width:100%;">
                            <tr class="queueinfolist">
                                <td style="width:70%;float: left;">座位类型</td>
                                <td style="width:30%;float: left;">等位数</td>                                
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
                                                    <td style="width:70%;float: left;"><?php echo $model["name"]."(".$model["min"]."-".$model["max"]."人)";?></td>
                                                    <td style="width:30%;float: left;">
                                                        <div style="border: 2px solid green;background-color:#F00;">
                                                            <span style="color:#00FFFFFF;padding: 5px;"><?php echo $queuepersons;?></span></div>
                                                    </td>
                                                    
                                                </tr>                                                                                            
                                <?php                                                                                                 
                                        endforeach;
                                    endif;?>                                                                                                                                                
                        </table>                                                                                                                                        
                    </div>
			<div style="text-align: center;margin-left:20%;">
                                <h1 style="color:white;"><?php echo yii::t('app','排队取号');?></h1>                 
                        </div>
			<div style="width: 100%;display: none;">
                            <div style="width: 40%;float: left;">
                                <h3 class="form-title" style="color:red;">①选择座位类型</h3>
                                <div class="queuesitetypelist">
                                    <?php if(!empty($siteTypes)):
                                        foreach($siteTypes as $siteType):?>
                                        <input type="button" lid="<?php echo $siteType->lid; ?>" class="btnSiteType <?php if($siteType->lid==$siteTypelid) echo 'queueactive'; ?>" value="<?php echo $siteType->name; ?>">
                                    <?php    endforeach;
                                    endif; ?>                                    
                                </div>
                            </div>
                            <div style="width: 60%;float: left;">
                                <h3 class="form-title" style="color:#000000;">②选择人数自动出号</h3>
                                <div class="queuesitepersonslist">
                                    <?php if(!empty($sitePersons)):
                                        foreach($sitePersons as $sitePerson):?>
                                        <input splid="<?php echo $sitePerson['splid']; ?>" stlid="<?php echo $sitePerson['typeid']; ?>" personrang="<?php echo $sitePerson['min'].'-'.$sitePerson['max']; ?>" class="btnSitePersons" type="button" value="<?php echo $sitePerson['min'].'-'.$sitePerson['max']; ?>人 (等叫:<?php echo empty($sitePerson['queuepersons'])?'0':$sitePerson['queuepersons']; ?>组)">                                        
                                    <?php    endforeach;
                                    endif; ?>                                    
                                </div>
                            </div>
                        </div>
                        
        <!--------mobile no box begin-------->
        <div id="mobilenobox" style="display:block;width:70%;margin-left:28%;">
                                        <div>
                                            <div style="width: 95%;margin:1.0em;font-size: 1.2em;">
                                                <label style="font-size:1.5em;color: #000088;">温馨提示：</label><span id="queuepersonrange" style="color:red;"></span>人。
                                                输入手机号后取号，或扫描二维码自动取号<span style="color:red;font-size:25px;">(未开通)</span>，到号时，会收到消息通知，如不需要，直接点击取号。
                                            </div>                                            
                                            <div style="float: left;width:60%;margin-top: 2.0em;">
                                                <DIV class="edit_span" selectid="minus" style="margin-left: 15%;width:70%;font-size:1.5em;">手机号码：<span style="background-color:#9acfea;display:-moz-inline-box;display:inline-block;width: 50%;" id="queuemobile">1</span></DIV>
                                                <DIV style="float:left;width:100%;border:0px solid red;">
                                                 <div style="margin-left:0px;border:0px solid red;" class="mobileinput">
                                                     <ul>
                                                         <li deal="A">1</li>
                                                         <li deal="A">2</li>
                                                         <li deal="A">3</li>
                                                         <li deal="A">4</li>
                                                         <li deal="A">5</li>
                                                         <li deal="A">6</li>
                                                         <li deal="A">7</li>
                                                         <li deal="A">8</li>
                                                         <li deal="A">9</li>
                                                         <li deal="A">0</li>
                                                         <li deal="delone">退格</li>
                                                         <li deal="delall">清除</li>
                                                     </ul>
                                                 </div>                                                
                                              </DIV> 
                                            </div>
                                            <div style="float: left;width:38%;height: 100%;">
                                                <div style="text-align:center;width: 85%;margin:1.0em;font-size:1.5em;height: 100%;">                                                    
                                                    <input style="width:80%;margin-left:10%;height:1.5em;font-size: 1.5em;" type="button" class="btn green" id="queueno" value="取  号">                                                    
                                                    <img style="width:80%;margin-left:10%;margin-top:7%;" src="/wymenuv2/img/top10/company_<?php echo $companyId; ?>/wx_barcode.jpg">
                                                    
                                                </div>    
                                                <!--<input style="position:absolute;right:3%;bottom: 4%;width:6.0em;height:3.0em;" type="button" class="btn green" id="layer2_close" value="<?php echo yii::t('app',' 关 闭 ');?>">-->
                                            </div>
                                        </div>
                                        <DIV style="position: absolute;width:60%;font-size: 2.0em;text-align: center;bottom:10px;">
                                            <marquee behavior="scroll"><a style="color:#ffffff" href="<?php echo $this->createUrl('default/index',array("companyId"=>$companyId));?>">我要点单系统，由上海物易网络科技有限公司提供！</a></marquee>
                                        </DIV>
                                    </div>
            </div>
        </div>
	
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
                        var sitetypelid="<?php echo $siteTypelid; ?>";
                        $('.btnSitePersons').css('display','none');
                        //alert(sitetypelid);
                        $(".queuesitepersonslist").find("input[stlid="+sitetypelid+"]").each(function(){
                            $(this).css('display','block');
                        });
                        //叫号后等叫的人数要减少
                        clearInterval(intervalQueueList);
                        intervalQueueList = setInterval(reloadqueuestate,"15000");
                        //reloadqueuestate();
                    });
                    
                    $('.btnSiteType').click(function(){
                        var stlid=$(this).attr('lid');
//                        var randtime=new Date().getTime()+""+Math.round(Math.random()*100);
//                        var url='<?php echo $this->createUrl('queue/index',array("companyId"=>$companyId)); ?>/siteTypelid/'+stlid+'/rand/'+randtime;
//                        location.href=url;
                        $('.btnSitePersons').css('display','none');
                        $(".queuesitepersonslist").find("input[stlid="+stlid+"]").each(function(){
                            $(this).css('display','block');
                        });
                        $('.btnSiteType').removeClass("queueactive");
                        $(this).addClass("queueactive");
                    });
                    
                    function unlock(){
                        
                    }
                    
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
                    
                    $('#queueclose').on(event_clicktouchstart,function(){
                        layer.close(layer_index_queueno);
                        layer_index_queueno=0;
                    });
                    
                </script>
                