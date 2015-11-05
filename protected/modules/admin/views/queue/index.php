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
    .calc_num {
        width: 96%;
        display: inline-block;
        margin-top: 10px;
    }
    .calc_button {
        width: 24%;
        display: inline-block;
        margin-top: 10px;
    	margin-left:0px;
    }
    .calc_num ul li {
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
      .calc_button ul li {
      	padding-left:0px;
      	line-height:50px;
        float: left;
        width: 100%;
        height: 3.5em;
        border: 1px solid #add;
        margin: 5px;
        font-size: 15px;
        font-weight: 700;        
        list-style: none;
        text-align: center;
       
      }
 .calc_dan {
		width:70%;
		display:inline-block;
		margin:5px;
}
	.calc_dan ul li{
		line-height:30px;
        float: left;
        width: 30%;
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

.dan_button {
		width:25%;
		display:inline-block;
		margin:5px;
}	
.dan_button ul li {
    line-height:45px;
    float: left;
    width: 90%;
    height: 3.5em;
    border: 1px solid #add;
    margin: 5px;
    font-size: 15px;
    font-weight: 700;        
    list-style: none;
    text-align: center;
}
.edit_span_hide {
    display: none;
}
.edit_span_select {
    border:1px solid red;
    background-color:#ED9F9F !important;    
}
.edit_span_select_zero {
    border:1px solid red;
    background-color:#add !important;
}

.edit_span_select_member {
    border:1px solid red;
    background-color:#ED9F9F !important;
}
</style>
			
			<div style="width: 100%;">
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
                        <div style="clear:both;"></div>
        <!--------mobile no box begin-------->
        <div id="mobilenobox" style="display:none;">
                                        <div>
                                            <div style="width: 95%;margin:1.0em;font-size: 1.0em;">
                                                <label style="font-size:1.5em;color: #000088;">温馨提示：</label>用餐人数，3-4人。
                                                ——输入手机号后取号，或扫描二维码自动取号，在到号时，会收到消息通知，如不需要，直接点击取号。
                                            </div>
                                            <div style="float: left;width:38%;height: 100%;">
                                                <div style="width: 85%;margin:1.0em;font-size:1.5em;height: 100%;">                                                    
                                                    <img style="width:80%;margin-left:10%;" src="/wymenuv2/img/top10/company_<?php echo $companyId; ?>/wx_barcode.jpg">
                                                    <input style="position:absolute;left:3.2%;bottom: 5%;width:28%;height:1.5em;font-size: 1.5em;" type="button" class="btn green" id="queueno" value="取  号">
                                                </div>    
                                                <!--<input style="position:absolute;right:3%;bottom: 4%;width:6.0em;height:3.0em;" type="button" class="btn green" id="layer2_close" value="<?php echo yii::t('app',' 关 闭 ');?>">-->
                                            </div>
                                            <div style="float: left;width:60%;margin-top: 2.0em;">
                                                <DIV class="edit_span" selectid="minus" style="margin-left: 15%;width:70%;font-size:1.5em;">手机号码：<span style="background-color:#9acfea;display:-moz-inline-box;display:inline-block;width: 50%;" id="payMinusAccount">13011113333</span></DIV>
                                                <DIV style="float:left;width:100%;border:0px solid red;">
                                                 <div style="margin-left:0px;border:0px solid red;" class="calc_num">
                                                     <ul>
                                                         <li>1</li>
                                                         <li>2</li>
                                                         <li>3</li>
                                                         <li>4</li>
                                                         <li>5</li>
                                                         <li>6</li>
                                                         <li>7</li>
                                                         <li>8</li>
                                                         <li>9</li>
                                                         <li>0</li>
                                                         <li>退格</li>
                                                         <li>清除</li>
                                                     </ul>
                                                 </div>                                                
                                              </DIV> 
                                            </div>                                            
                                        </div>
                                    </div>
            </div>
        </div>
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		2014 &copy; <?php echo yii::t('app','我要点单 - 排队取号系统');?>
                <a href="<?php echo $this->createUrl('default/index',array("companyId"=>$companyId));?>"><<点击返回</a>
	</div>
		<script language="JavaScript" type="text/JavaScript">
                    var layer_index_queueno=0;
                    var intervalQueueList;
                    var companyid=<?php echo $companyId; ?>;
                    var btnlock=false;
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
                                alert("网络可能有问题，再试一次！");
                            },
                            complete : function(XMLHttpRequest,status){
                                if(status=='timeout'){
                                    alert("网络可能有问题，再试一次！");                                            
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
                        //btnlock=false;
                    });
                </script>
                