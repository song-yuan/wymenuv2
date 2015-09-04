<div class="client_siteall_title"><span id="idclient_waitorall_title"></span>
    <input type="button" id="idclient_waitorall_close" style="float:left;font-size: 30px;padding: 15px; width: 4.0em; margin: 5px;" value="关闭">
    <input type="button" id="pad-order-submit-subbtn" style="float:right;font-size: 30px;padding: 15px; width: 4.0em; margin: 5px;" value="下单">
</div>
        <div class="client_waitorlist_in">
            <ul>
            <?php foreach ($users as $user):
                if($user->delete_flag=="0" && $user->status=="1"):?>
                <li class="waitoraction" username="<?php echo $user->username; ?>"><span style="font-size: 25px;"><?php echo $user->username; ?></span></li>        
            <?php 
                endif;
            endforeach;?>
            </ul>
        </div>
   
    
     
<script type="text/javascript">
        var curclientwaitorname=$('#id_client_waitor_name').val();
        var language = $('input[name="language"]').val();
        $('#idclient_waitorall_title').html("当前服务员："+curclientwaitorname);
        $("li[username="+curclientwaitorname+"]").addClass('bg-yellow');
	$('.waitoraction').on('click', function(){
            $('.waitoraction').removeClass("bg-yellow");
            $(this).addClass("bg-yellow");
            var username=$(this).attr("username");
            $('#id_client_waitor_name').val(username);
            $('#idclient_waitorall_title').html("当前服务员："+username);
         });      
        
              
        $('#idclient_waitorall_close').on(event_clicktouchstart,function(){
           $('#divid_client_waitorlist').hide();                         
        });
        
//        //$('#pad-order-submit-subbtn').on('click',function(){
//    function clientsaveorder(){
//        if (typeof Androidwymenuprinter == "undefined") {
//            alert(language_notget_padinfo);
//            //return false;
//        }
//        var sid=$('#id_client_site_id').val();
//        var istemp=$('#id_client_is_temp').val();
//        if(istemp=="1")
//        {
//            alert("请选择座位！");
//            return false;
//        }
//        //var username=$('#id_client_waitor_name').val();
//        //alert(username);
//        var forbidden=false;
//        var getstatusurl=$('#productmasksiteinfo').attr("action")+'/sid/'+sid+"/istemp/"+istemp;
//        //alert(getstatusurl);
//        if(sid!=0)
//        {
//            $.ajax({
//                        url:getstatusurl,
//                        type:'GET',
//                        //data:formdata,
//                        async:false,
//                        dataType: "json",
//                        success:function(msg){
//                            //alert(msg);防止前台开台，但是后台结单或撤台了，就不能继续下单
//                            if(!(msg.status == "1" || msg.status == "2" || msg.status == "3"))
//                            {
//                                alert(language_client_order_forbidden);
//                                forbidden=true;
//                                return;
//                            }
//                        },
//                        error: function(msg){
//                            alert(language_client_order_forbidden);
//                            forbidden=true;
//                            return;
//                        }
//                    });
//            //if(jobid)存在，说明是重新打印，不用下单
//        }
//        if(forbidden)
//        {
//            return;
//        }
//        var formdata=$('#padOrderForm').formSerialize();
//            $.ajax({
//                    url:$('#padOrderForm').attr('action'),
//                    type:'POST',
//                    data:formdata,
//                    async:false,
//	            dataType: "json",
//	            success:function(msg){
//                        var data=msg;
//	                var printresult=false;
//                        var printresultfail=false;
//                        var printresulttemp;
//                        var waittime=0;
//	    		if(data.status){
//                            if(istemp=="0")
//                            {
//                                $.each(data.jobs,function(skey,svalue){ 
//                                    data.jobs[skey]="0_"+svalue;
//                                });
//                            }
//                            //alert(data.orderid);
//                            var index = layer.load(0, {shade: [0.3,'#fff']});
//                            //var wait=setInterval(function(){ 
//                            var waitfun=function(){
//                                waittime++;
//                                //alert(waittime);
//                                if(istemp=="1")
//                                {
//                                    printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);
//                                    //printresult=true;
//                                    if(printresult)
//                                    {
//                                        //clearInterval(wait);
//                                        waittime=10;
//                                        $('#id_client_reprint').val("0");
//                                        //layer.close(index);
//                                    }                                      
//                                }else{
//                                    printresultfail=false;
//                                    $.each(data.jobs,function(skey,svalue){                                        
//                                        detaildata=svalue.split("_");
//                                        if(detaildata[0]=="0")//继续打印
//                                        {
//                                            printresulttemp=Androidwymenuprinter.printNetJob(data.dpid,detaildata[1],detaildata[2]);
//                                            //printresulttemp=true;
//                                            if(printresulttemp)
//                                            {
//                                                data.jobs[skey]="1_"+svalue.substring(2);                                                
//                                            }else{
//                                                printresultfail=true;                                                                                               
//                                            }
//                                        }
//                                     }); 
//                                     if(!printresultfail)
//                                     {
//                                        //clearInterval(wait);
//                                        waittime=10;
//                                        $('#id_client_reprint').val("0");
//                                        //layer.close(index);
//                                     }
//                                }
////                                
//                                if(waittime>3)
//                                {
//                                     //clearInterval(wait);
//                                     //$('#id_client_reprint').val("1");
//                                     layer.close(index);
//                                     //alert(language_print_pad_fail);
//                                     
//                                    if(istemp=="1"&&!printresult)
//                                    {
//                                        alert("有打印失败，请去收银台查看1！");
//                                        //如果失败，就把打印任务插入到数据库
//                                        $.ajax({
//                                            url:'/wymenuv2/product/saveFailJobs/orderid/'+data.orderid+'/dpid/'+data.dpid+'/jobid/'+data.jobid+"/address/"+data.address,
//                                            type:'GET',
//                                            //data:formdata,
//                                            async:false,
//                                            dataType: "json",
//                                            success:function(msg){
//
//                                            },
//                                            error: function(msg){
//                                                alert("网络故障！")
//                                            }
//                                        });
//                                    }
//                                    if(istemp=="0"&&printresultfail)
//                                    {
//                                        alert("有打印失败，请去收银台查看2！");
//                                        //如果失败，就把打印任务插入到数据库
//                                        $.each(data.jobs,function(skey,svalue){                                        
//                                                detaildata=svalue.split("_");
//                                                if(detaildata[0]=="0")
//                                                {
//                                                    $.ajax({
//                                                        url:'/wymenuv2/product/saveFailJobs/orderid/'+data.orderid+'/dpid/'+data.dpid+'/jobid/'+detaildata[1]+"/address/"+detaildata[2],
//                                                        type:'GET',
//                                                        //data:formdata,
//                                                        async:false,
//                                                        dataType: "json",
//                                                        success:function(msg){
//
//                                                        },
//                                                        error: function(msg){
//                                                            alert("网络故障！")
//                                                        }
//                                                    });
//                                                }
//                                            });
//                                    }
//                                     
//                                }else{
//                                    //waitfun();
//                                    setTimeout(waitfun, 2000);
//                                }
//                            }//定义函数
//                            //},3000); 	
//                            waitfun();                 
//                       //if(istemp=="1")
//                            
////	                 if(printresult)
////	                 {
//                            $('#padOrderForm').find('.input-product').each(function(){
//                            var _this = $(this);
//                            var productId = _this.attr('name');
//                            var productIdArr = productId.split(","); //字符分割 
//                            productId = productIdArr[0];
//                            var parents = $('.blockCategory a[lid="'+productId+'"]').parents('.blockCategory');
//                            var category = parents.attr('category');//分类id
//                            parents.find('.single-num-circel').css('display','none').html(0);
//                            
//                            if(parents.find('.product-taste').hasClass('hasClick')){
//	                             parents.find('.product-taste').removeClass('hasClick'); //去掉口味点击类
//	                             parents.find('.taste-list').each(function(eq){
//	                               if(eq > 0){
//	                                       $(this).remove();
//	                               }else{
//	                                       $(this).find('.item').removeClass('active'); //去掉第一个口味选中
//	                               }
//	                       	     });
//                            }
//                             $('input[name^="'+productId+'"]').remove();
//	                     });
//	                     
//	                     //清空订单
//	                     $('#padOrderForm').find('.info').html('');
//	                    
//	                     //清空全单口味
//	                     $('input[name^="quandan"]').each(function(e){
//	                  		$(this).remove();
//	                  	 });
//	                      
//	                     $('.product-pad-mask').hide();
//	                     var total = 0;
//	                     if(!parseInt(language)){
//	             			total = total.toFixed(2);
//	             		}
//	                     $('.total-price').html(total);
//	                        $('.total-num').html(0);
//                                alert("下单成功");
//                                $('#divid_client_waitorlist').hide();
////	                 }else{
////	                     alert(language_print_pad_fail);
////	                 }                                                
//	                }else{
//	                    alert(data.msg);
//	                }
//                        $('#padOrderForm').resetForm();
//                    },
//                    error: function(msg){
//                        alert(language_client_order_fail);
//                    }
//	     	});
//            }//functon到此
//    //});on click到此
        
                //$('#pad-order-submit-subbtn').on('click',function(){
    function clientsaveorder2(){
        var index = layer.load(0, {shade: [0.3,'#fff']});
        if (typeof Androidwymenuprinter == "undefined") {
//            layer.close(index);
            alert(language_notget_padinfo);
            //return false;
        }
        var sid=$('#id_client_site_id').val();
        var istemp=$('#id_client_is_temp').val();
        if(istemp=="1")
        {
            layer.close(index);
            alert("请选择座位！");
            return false;
        }
        //var username=$('#id_client_waitor_name').val();
        //alert(username);
        
        var forbidden=false;
        var getstatusurl=$('#productmasksiteinfo').attr("action")+'/sid/'+sid+"/istemp/"+istemp;
        //alert(getstatusurl);
        if(sid!=0)
        {
            $.ajax({
                        url:getstatusurl,
                        type:'GET',
                        //data:formdata,
                        async:false,
                        dataType: "json",
                        success:function(msg){
                            //alert(msg);防止前台开台，但是后台结单或撤台了，就不能继续下单
                            if(!(msg.status == "1" || msg.status == "2" || msg.status == "3"))
                            {
                                layer.close(index);
                                alert(language_client_order_forbidden);
                                forbidden=true;
                                return;
                            }
                        },
                        error: function(msg){
                            layer.close(index);
                            alert(language_client_order_forbidden);
                            forbidden=true;
                            return;
                        }
                    });
            //if(jobid)存在，说明是重新打印，不用下单
        }
        if(forbidden)
        {
            layer.close(index);
            return;
        }
        var formdata=$('#padOrderForm').formSerialize();
            $.ajax({
                    url:$('#padOrderForm').attr('action'),
                    type:'POST',
                    data:formdata,
                    async:false,
	            dataType: "json",
	            success:function(msg){
                        var data=msg;
	                var printresult=false;
                        var printresultfail=false;
                        var printresulttemp;
                        if(data.status){
                            
//                            var index = layer.load(0, {shade: [0.3,'#fff']});
                                if(istemp=="1")
                                {
                                    printresult=Androidwymenuprinter.printNetJob(data.dpid,data.jobid,data.address);
                                    //printresult=true;                                                                          
                                }else{
                                    printresultfail=false;
                                    $.each(data.jobs,function(skey,svalue){ 
                                        //alert(svalue);
                                        detaildata=svalue.split("_");                                        
                                        if(detaildata[0]=="0")//继续打印
                                        {
                                            printresulttemp=Androidwymenuprinter.printNetJob(data.dpid,detaildata[1],detaildata[2]);
                                            //printresulttemp=false;
                                            if(printresulttemp)
                                            {
                                                data.jobs[skey]="1_"+svalue.substring(2);                                                
                                            }else{
                                                printresultfail=true;                                                                                               
                                            }
                                        }
                                     });                                      
                                }                               
                                
//                                     layer.close(index);
                                     //alert(language_print_pad_fail);                                     
                                    if(istemp=="1"&&!printresult)
                                    {
                                        alert("有打印失败，请去收银台查看1！");
                                        //如果失败，就把打印任务插入到数据库
                                        $.ajax({
                                            url:'/wymenuv2/product/saveFailJobs/orderid/'+data.orderid+'/dpid/'+data.dpid+'/jobid/'+data.jobid+"/address/"+data.address,
                                            type:'GET',
                                            //data:formdata,
                                            async:false,
                                            dataType: "json",
                                            success:function(msg){

                                            },
                                            error: function(msg){
                                                layer.close(index);
                                                alert("网络故障！")
                                            }
                                        });
                                    }
                                    //alert(istemp);alert(printresultfail);
                                    if(istemp=="0"&& printresultfail)
                                    {
                                        alert("可能有打印失败，请去打印机处确认，如果失败，请去收银台查看并重打！");
                                        //如果失败，就把打印任务插入到数据库
                                        $.each(data.jobs,function(skey,svalue){                                        
                                                detaildata=svalue.split("_");
                                                if(detaildata[0]=="0")
                                                {
                                                    $.ajax({
                                                        url:'/wymenuv2/product/saveFailJobs/orderid/'+data.orderid+'/dpid/'+data.dpid+'/jobid/'+detaildata[1]+"/address/"+detaildata[2],
                                                        type:'GET',
                                                        //data:formdata,
                                                        async:false,
                                                        dataType: "json",
                                                        success:function(msg){

                                                        },
                                                        error: function(msg){
                                                            layer.close(index);
                                                            alert("网络故障！")
                                                        }
                                                    });
                                                }
                                            });
                                    }
//	                 if(printresult)
//	                 {
                            $('#padOrderForm').find('.input-product').each(function(){
                            var _this = $(this);
                            var productId = _this.attr('name');
                            var productIdArr = productId.split(","); //字符分割 
                            productId = productIdArr[0];
                            var parents = $('.blockCategory a[lid="'+productId+'"]').parents('.blockCategory');
                            var category = parents.attr('category');//分类id
                            parents.find('.single-num-circel').css('display','none').html(0);
                            
                            if(parents.find('.product-taste').hasClass('hasClick')){
	                             parents.find('.product-taste').removeClass('hasClick'); //去掉口味点击类
	                             parents.find('.taste-list').each(function(eq){
	                               if(eq > 0){
	                                       $(this).remove();
	                               }else{
	                                       $(this).find('.item').removeClass('active'); //去掉第一个口味选中
	                               }
	                       	     });
                            }
                             $('input[name^="'+productId+'"]').remove();
	                     });
	                     
	                     //清空订单
	                     $('#padOrderForm').find('.info').html('');
	                    
	                     //清空全单口味
	                     $('input[name^="quandan"]').each(function(e){
	                  		$(this).remove();
	                  	 });
	                      
	                     $('.product-pad-mask').hide();
	                     var total = 0;
	                     if(!parseInt(language)){
	             			total = total.toFixed(2);
	             		}
	                     $('.total-price').html(total);
	                        $('.total-num').html(0);
                                alert("下单成功");
                                $('#divid_client_waitorlist').hide();
//	                 }else{
//	                     alert(language_print_pad_fail);
//	                 }                                                
	                }else{
	                    alert(data.msg);
	                }
                        $('#padOrderForm').resetForm();
                        layer.close(index);
                    },
                    error: function(msg){
                        layer.close(index);
                        alert(language_client_order_fail);
                    }
	     	});
            }//functon到此
    //});on click到此
        
         $('#pad-order-submit-subbtn').on('click',function(){
           clientsaveorder2(); 
        });
        
</script>