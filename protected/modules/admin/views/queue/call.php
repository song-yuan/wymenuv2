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
                    <div style="width: 52%;margin:3.0em;font-size: 1.5em;float: left;">
                        <div id="queue_call_content" style="display:none;">
                            <DIV style="float:left;width:95%;font-size: 1.5em;text-align: center;margin-top:2.0em;">
                                <label style="font-size:40px;">请<span id="callsitetypename"></span></label><br>
                                <label style="font-size:90px;color:red;font-weight:900;"><span id="callqueueno" lid="0000000000">A3001</span>号</label><br>
                                <label style="font-size:40px;">前来就餐！</label>
                            </DIV> 
                        </div>
                        <DIV style="position: absolute;width:50%;font-size: 1.5em;text-align: center;bottom:10px;">
                            <marquee behavior="scroll"><a style="color:#ffffff" href="<?php echo $this->createUrl('default/index',array("companyId"=>$companyId));?>">我要点单系统，由上海物易网络科技有限公司提供！</a></marquee>
                        </DIV>
                        
                        <div id="queue_call_img" style="width:60%;margin-left:20%;margin-top:7%;">
                            <img src="/wymenuv2/img/top10/company_<?php echo $companyId; ?>/wx_barcode.jpg">
                        </div>
                    </div>
                    <div style="text-align: center;width: 38%;position: absolute;top:0px;bottom: 0px;right: 0px;border:1px solid red;background-color: #add;overflow:scroll;">
                        
                        <table id="queue_pass_list" style="width:100%;display: none;">
                            
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
                                                    <td style="width:40%;float: left;font-size:15px;line-height:25px;">
                                                        
                                                        <?php echo $model["name"]."/".$model["min"]."-".$model["max"];?>
                                                        :<span>OOOOO</span>                                                        
                                                    </td>
                                                    <td style="width:22%;float: left;">
                                                        <div style="float:left;border: 2px solid green;background-color:#f00;width:50%;">
                                                            <span style="color:#00FFFFFF;padding: 3px;"><?php echo $queuepersons;?></span></div>
                                                        <div style="float:left;border: 2px solid green;background-color:#858fa6;width:50%;">
                                                            <span style="color:#00FFFFFF;padding: 3px;"><?php echo $sitefree; ?></span></div></td>
                                                    <td style="width:38%;float: left;">
                                                        <div style="width:100%;text-align:right;" lid="0000000000" queueno="00000">
                                                            <div class="imgcall" style="width:30%;float:left;" stlid="<?php echo $model["stlid"] ?>" splid="<?php echo $model["splid"] ?>" >
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
//                        if(ghasfree>0 && ghaswaiting>0)
//                        {
//                            if (typeof Androidwymenuprinter == "undefined") {
//                                //alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
//                            }else{
//                                Androidwymenuprinter.padAlarm();
//                            }
//                        }
                    }
                    
                    $(document).ready(function(){                        
                        //叫号后等叫的人数要减少
                        clearInterval(intervalQueueList);
                        intervalQueueList = setInterval(reloadqueuestate,"15000");
                        //reloadqueuestate();
                    });
                                        
                    $('#queue_pass_btn').on(event_clicktouchstart,function(){
                        $('#queue_call_list').hide();
                        $('#queue_pass_list').load("/wymenuv2/admin/queue/getPassCall/companyId/<?php echo $companyId;?>")
                        $('#queue_pass_list').show();
                    });
                    $('#queue_call_btn').live(event_clicktouchstart,function(){
                        $('#queue_pass_list').hide();
                        $('#queue_call_list').show();                        
                    });
                    
                    $('.imgcall').on(event_clicktouchstart,function(){
                        //var lid=$("#callqueueno").attr("lid");
                        var lid=$(this).parents("div").attr("lid");
                        //alert(lid);
                        var dpid="<?php echo $companyId; ?>";
                        //alert(lid);alert($(this).parents("tr").children('td').eq(1).html());
                        if(lid=="0000000000")//取最新的并叫号
                        {
                            var stlid=$(this).attr("stlid");
                            var splid=$(this).attr("splid");
                            var that=$(this);
                            $.ajax({
                                 url:"/wymenuv2/admin/queue/nextPerson/companyId/"+dpid+"/stlid/"+stlid+"/splid/"+splid+"/callno/"+callno,
                                 type:'GET',
                                 timeout:5000,
                                 cache:false,
                                 async:false,
                                 dataType: "json",
                                 success:function(msg){
                                     //alert(msg.callno);alert(msg.queuelid);
                                      if(msg.status)
                                      {
                                          //$("#callqueueno").text(msg.callno);
                                          //$("#callqueueno").attr("lid",msg.queuelid);
                                          that.parents("div").attr("lid",msg.queuelid);
                                          that.parents("div").attr("queueno",msg.callno);
                                          lid=msg.queuelid;
                                          //alert(lid);                                          
                                          that.parents("tr").children('td').eq(0).find("span").text(msg.callno);
                                          var div1=that.parents("tr").children('td').eq(1).children('div').eq(0);
                                          div1.find("span").text(msg.queuenum);
                                          if(msg.queuenum>0)
                                          {
                                              div1.css("background-color","#F00");
                                          }else{
                                              div1.css("background-color","");
                                          }
                                          var div2=that.parents("tr").children('td').eq(1).children('div').eq(1);
                                          div2.find("span").text(msg.sitefree);
                                          if(msg.sitefree>0)
                                          {
                                              div2.css("background-color","#858fa6");
                                          }else{
                                              div2.css("background-color","");
                                          }
//                                          var siteobj=$(".modalaction[typeid='queue'][sid="+gsid+"][istemp="+gistemp+"]");
//                                          siteobj.removeClass("bg-yellow");
//                                          siteobj.removeClass("bg-green");                                                    
                                          //改变背景颜色///
                                          
//                                          if(msg.queuenum>0)
//                                          {                                                
//                                              if(msg.sitefree>0)
//                                              {
//                                                  siteobj.addClass("bg-green");                                                    
//                                              }else{
//                                                  siteobj.addClass("bg-yellow");                                                    
//                                              }
//                                          }
                                          //修改排队数和空位数文字..                                             
//                                          siteobj.find("span[typename='sitefree']").text("空座："+msg.sitefree);
//                                          siteobj.find("span[typename='queuenum']").text("排队："+msg.queuenum);
                                      }
                                 },
                                 error: function(msg){
                                     alert("网络可能有问题，再试一次！");
                                 },
                                 complete : function(XMLHttpRequest,status){
                                     if(status=='timeout'){
                                         alert("网络可能有问题，再试一次！");                                           
                                     }
                                 }
                             });
                        }
                        //开始叫号
                        
                        var callno=$("#callqueueno").text();
                        //alert(callno);return;
                        if(lid=="0000000000")
                        {
                            alert("无号可叫！");
                            $("#queue_call_content").hide();
                            $("#queue_call_img").show();
                            return;
                        }
                        $("#callqueueno").text(that.parents("div").attr("queueno"));
                        $("#callqueueno").attr("lid",that.parents("div").attr("lid"));
                        $("#queue_call_content").show();
                        $("#queue_call_img").hide();
                        $("#queue_call_content").show();
                        $("#queue_call_img").hide();
                       if (typeof Androidwymenuprinter == "undefined") {
                            alert("找不到PAD设备");
                        }else{                            
                            Androidwymenuprinter.queuecall(callno);                                    
                        }                                           
                    });

                    $('#queuepass').on(event_clicktouchstart,function(){
                       var statu = confirm("<?php echo yii::t('app','确定下一个吗？如果确定本号码将不能再叫号！');?>");
                        if(!statu){
                            return false;
                        }
                       var callno=$("#callno").text();
                       var stlid=$(this).attr("stlid");
                       var splid=$(this).attr("splid");
                       var dpid="<?php echo $companyId; ?>";
                       $.ajax({
                            url:"/wymenuv2/admin/defaultSite/nextPerson/companyId/"+dpid+"/stlid/"+stlid+"/splid/"+splid+"/callno/"+callno,
                            type:'GET',
                            timeout:5000,
                            cache:false,
                            async:false,
                            dataType: "json",
                            success:function(msg){
                                 if(msg.status)
                                 {
                                     $("#callno").text(msg.callno);
                                     var siteobj=$(".modalaction[typeid='queue'][sid="+gsid+"][istemp="+gistemp+"]");
                                     siteobj.removeClass("bg-yellow");
                                     siteobj.removeClass("bg-green");                                                    
                                     //改变背景颜色///
                                     if(msg.queuenum>0)
                                     {                                                
                                         if(msg.sitefree>0)
                                         {
                                             siteobj.addClass("bg-green");                                                    
                                         }else{
                                             siteobj.addClass("bg-yellow");                                                    
                                         }
                                     }
                                     //修改排队数和空位数文字..                                             
                                     siteobj.find("span[typename='sitefree']").text("空座："+msg.sitefree);
                                     siteobj.find("span[typename='queuenum']").text("排队："+msg.queuenum);
                                 }
                            },
                            error: function(msg){
                                alert("网络可能有问题，再试一次！");
                            },
                            complete : function(XMLHttpRequest,status){
                                if(status=='timeout'){
                                    alert("网络可能有问题，再试一次！");                                           
                                }
                            }
                        });
                    });
                    
                    $('.imgeat').live(event_clicktouchstart,function(){
                        var lid=$("#callqueueno").attr("lid");
                        var dpid="<?php echo $companyId; ?>";
                        //alert(lid);
                        if(lid=="0000000000")
                        {
                            return;
                        }
                        $.ajax({
                            url:"/wymenuv2/admin/queue/setQueueStatus/companyId/"+dpid+"/lid/"+lid+"/status/2",
                            type:'GET',
                            timeout:5000,
                            cache:false,
                            async:false,
                            dataType: "json",
                            success:function(msg){
                                $("#callqueueno").attr("lid","0000000000");
                                $("#callqueueno").text("00000");
                                $("#callsitetypename").text("00000");
                                $("#queue_call_content").hide();
                                $("#queue_call_img").show();
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
                </script>
                