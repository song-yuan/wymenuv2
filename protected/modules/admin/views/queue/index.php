<style>
    .queuesitetypelist input{
        font-size: 25px;
        width: 40%;
        height: 70px;
        background-color: darkseagreen;
        float: left;
        margin: 2%;
    }
    .queuesitepersonslist input{
        font-size: 25px;
        width: 40%;
        height: 70px;
        background-color:skyblue;
        float: left;
        margin: 2%;
    }
    .queueactive{
        background-color: red !important;
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
	<!-- BEGIN COPYRIGHT -->
	<div class="copyright">
		2014 &copy; <?php echo yii::t('app','我要点单 - 排队取号系统');?>
                <a href="<?php echo $this->createUrl('default/index',array("companyId"=>$companyId));?>"><<点击返回</a>
	</div>
		<script language="JavaScript" type="text/JavaScript">
                    var intervalQueueList;
                    var companyid=<?php echo $companyId; ?>;
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
                    
                    $('.btnSitePersons').click(function(){
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
                                        var reprint=true
                                        while(reprint)
                                        {
                                            printresulttemp=Androidwymenuprinter.printNetJob(dpid,msg.jobid,msg.address);
                                            if(!printresulttemp)
                                            {
                                                confirm("打印失败，是否重新打印？", function(result) {                  
                                                        reprint=result;
                                                });
                                            }else{
                                                reprint=false;
                                            }
                                        }
                                 }else{
                                     alert(msg.msg);
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
                </script>
                