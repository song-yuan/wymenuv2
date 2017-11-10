
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               

	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	
	<!-- BEGIN PAGE CONTENT-->
	<?php if($type==0):?>
		<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','测试'),'url'=>''))));?>
	<?php endif;?>
	<div class="row">
		<div class="col-md-12">
			<div class="portlet purple box">
				<div class="portlet-title">
					<div class="caption"><i class=" fa fa-edit"></i><a href=""><span class="tab tab-active"><?php echo yii::t('app','收钱吧对接测试');?></span></a></div>
					<div class="actions">
						
					</div>
				</div>
				<div style="min-height: 30px;display:none" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">Code:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="code" class="form-control" value="14599168">
					</div>
				</div>
				<div style="min-height: 30px;display:" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">设备号:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="device_id" class="form-control" value="01760027562502">
					</div>
				</div>
				<div style="min-height: 30px;display:none" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">终端号:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="terminal_sn" class="form-control" value="100000880001181872">
					</div>
				</div>
				<div style="min-height: 30px;display:none" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">终端秘钥:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="terminal_key" class="form-control" value="71a81c361faef0d7c94b5daa1343ffcf">
					</div>
				</div>
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">价格:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="price" class="form-control" value="0.01">
					</div>
				</div>
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">订单号:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="clientSn" class="form-control" value="1489738207-0000000027-922">
					</div>
				</div>
				<div style="min-height: 30px;display:" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">支付流水号:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="sn" class="form-control" value="7895259476357545">
					</div>
				</div>
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">扫条码:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="dynamicId" class="form-control" value="">
					</div>
				</div>
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">店铺:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="dpid" class="form-control" value="">
					</div>
				</div>
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">开始时间:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="btime" class="form-control" value="2017-01-01 00:00:00">
					</div>
				</div>
				<div style="min-height: 30px;" class="form-group">
	                <lable style="font-size: 16px;margin-top: 10px;text-align: right; " class="col-md-3 control-label">结束时间:</lable>
					<div style="margin-top: 5px;" class="col-md-4">
						<input id="etime" class="form-control" value="2017-09-01 00:00:00">
					</div>
				</div>
				<div class="form-actions fluid">
						<div class="col-md-offset-9 col-md-3">
							<button style="display: none;" type="button" class="btn green" id="stocktaking">激活</button> 
							<button style="display: none;" type="button" class="btn green" id="stockCheck">签到</button> 
							<button type="button" class="btn green" id="stockOrder">预下单</button>  
							<button type="button" class="btn green" id="stockWappay">公众号支付</button>  
							<button type="button" class="btn green" id="stockPayWei">微信支付</button> 
							<button type="button" class="btn green" id="stockPayAli">支付宝支付</button> 
							<button type="button" class="btn green" id="stockRefundWei">微信退款</button>
							<button type="button" class="btn green" id="stockRefundAli">支付宝退款</button>   
							<button type="button" class="btn green" id="stockFind">查询</button>    
							<button type="button" class="btn green" id="stockAddordpay">添加到order_pay</button>   
							<button type="button" class="btn green" id="rijieOrder">日结</button> 
							<button type="button" class="btn green" id="rijieOrders">日结2</button>  
							<button type="button" class="btn green" id="rijieOrderReport">生成日结报表</button>                      
						</div>
					</div>
			</div>
		</div>
	</div>
	<!-- END PAGE CONTENT-->
	<script>
	$("#stocktaking").on("click",function(){
		
        var code = $("#code").val();
        var device_id = $("#device_id").val();
        var appId = $("#appId").val();
       
        $.ajax({
            type:'POST',
			url:"<?php echo $this->createUrl('cfceshi/sqbactivate',array('companyId'=>$this->companyId,));?>",
			async: false,
			//data:'{"clientSn":'+'"1234567890"'+',"totalAmount":'+'"0.01"'+',"payType":'+'"3"'+',"dynamicId":"'+price+'","abstract":"ceshi"'+',"userName":'+'"CF"}',
            data: {
            	code: code,
            	device_id: device_id,
            	appId: appId,
            },
            cache:false,
            dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            
			        layer.msg("激活成功！");
			          
		            location.reload();
	            }else{
		            layer.mag("11");
		            location.reload();
	            }
			},
            error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
        
		});
	$("#stockPayAli").on("click",function(){

		var device_id = $("#device_id").val();
        var price = $("#price").val();
        var dynamicId = $("#dynamicId").val();
        var username = '<?php echo Yii::app()->user->username?>';
        $.ajax({
            type:'GET',
			url:"<?php echo $this->createUrl('../alipay/barpay',array('companyId'=>$this->companyId,));?>/pay_price/"+price+"/auth_code/"+dynamicId+"/poscode/"+device_id+"/username/"+username,
			async: false,
            cache:false,
            dataType:'json',
			success:function(msg){
	            layer.msg(msg.status);
			},
            error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
        
	});
	$("#stockPayWei").on("click",function(){

		var device_id = $("#device_id").val();
        var price = $("#price").val();
        var dynamicId = $("#dynamicId").val();
        var username = '<?php echo Yii::app()->user->username?>';
        $.ajax({
            type:'GET',
			url:"<?php echo $this->createUrl('../weixin/microPaySingle',array('companyId'=>$this->companyId,));?>/pay_price/"+price+"/auth_code/"+dynamicId+"/poscode/"+device_id+"/username/"+username,
			async: false,
            cache:false,
            dataType:'json',
			success:function(msg){
	            layer.msg(msg.status);
			},
            error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
        
	});
	$("#stockFind").on("click",function(){

		var terminal_sn = $("#terminal_sn").val();
		var terminal_key = $("#terminal_key").val();
		var clientSn = $("#clientSn").val();
		var sn = $("#sn").val();;
        var username = '<?php echo Yii::app()->user->username?>';
        $.ajax({
            type:'GET',
			url:"<?php echo $this->createUrl('cfceshi/sqbquery',array('companyId'=>$this->companyId,));?>/terminal_sn/"+terminal_sn+"/terminal_key/"+terminal_key+"/sn/"+sn+"/clientSn/"+clientSn,
			async: false,
            cache:false,
            dataType:'json',
			success:function(msg){
	            layer.msg(msg.status);
			},
            error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
        
	});
	$("#stockCheck").on("click",function(){
		var device_id = $("#device_id").val();
    $.ajax({
        type:'POST',
		url:"<?php echo $this->createUrl('cfceshi/sqbcheck',array('companyId'=>$this->companyId,));?>",
		async: false,
		data: {device_id:device_id},
        cache:false,
        dataType:'json',
		success:function(msg){
            //alert(msg.status);
            if(msg.status=="success")
            {            
	            
		        layer.msg("成功！");
		          
	            location.reload();
            }else{
	            layer.mag("11");
	            location.reload();
            }
		},
        error:function(){
			layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
		},
	});
    
	});
	$("#stockRefundAli").on("click",function(){

		var device_id = $("#device_id").val();
        var refund_fee = total_fee = $("#price").val();
	    var out_trade_no = $("#clientSn").val();
	    var admin_id = '0000000178';
	    //layer.msg(admin_id);return false;
	    $.ajax({
	        type:'GET',
			url:"<?php echo $this->createUrl('../alipay/refund',array('companyId'=>$this->companyId,));?>/poscode/"+device_id+"/admin_id/"+admin_id+"/out_trade_no/"+out_trade_no+"/total_fee/"+total_fee+"/refund_fee/"+refund_fee,
			async: false,
			
	        cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status)
	            {            
		            
			        layer.msg("退款成功！");
			          
		            //location.reload();
	            }else{
		            layer.msg("退款失败！");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});
	$("#stockRefundWei").on("click",function(){

		var device_id = $("#device_id").val();
        var refund_fee = total_fee = $("#price").val();
	    var out_trade_no = $("#clientSn").val();
	    var admin_id = '0000000178';
	    //layer.msg(admin_id);return false;
	    $.ajax({
	        type:'GET',
			url:"<?php echo $this->createUrl('../weixin/refund',array('companyId'=>$this->companyId,));?>/poscode/"+device_id+"/admin_id/"+admin_id+"/out_trade_no/"+out_trade_no+"/total_fee/"+total_fee+"/refund_fee/"+refund_fee,
			async: false,
			
	        cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status)
	            {            
		            
			        layer.msg("退款成功！");
			          
		            //location.reload();
	            }else{
		            layer.msg("退款失败！");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});
	$("#stockOrder").on("click",function(){
		return false;
	    var device_id = $("#device_id").val();
	    $.ajax({
	        type:'POST',
			url:"<?php echo $this->createUrl('cfceshi/sqbprecreate',array('companyId'=>$this->companyId,));?>/pad_code/"+device_id,
			async: false,
			data: {device_id: device_id},
	        cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            
			        layer.msg("盘点成功！");
			          
		            location.reload();
	            }else{
		            layer.mag("11");
		            location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});

	$("#stockWappay").on("click",function(){
		
	    var device_id = $("#device_id").val();
	    $.ajax({
	        type:'POST',
			url:"<?php echo $this->createUrl('cfceshi/sqbprecreate',array('companyId'=>$this->companyId,));?>/pad_code/"+device_id,
			async: false,
			data: {device_id: device_id},
	        //cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            
			        layer.msg("成功！");
			          
		            //location.reload();
	            }else{
		            layer.mag("11");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});

	$("#stockAddordpay").on("click",function(){
		
	    var dpid = $("#dpid").val();
	    //alert(dpid);
	    $.ajax({
	        type:'POST',
			url:"<?php echo $this->createUrl('cfceshi/sqbaddordpay',array('companyId'=>$this->companyId,));?>/dpid/"+dpid,
			async: false,
			
	        //cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg.status=="success")
	            {            
		            
			        layer.msg("成功！");
			          
		            //location.reload();
	            }else{
		            layer.mag("11");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});
	$("#rijieOrder").on("click",function(){
		
	    var dpid = $("#dpid").val();
	    var btime = $("#btime").val();
	    var etime = $("#etime").val();
	    //alert(dpid);
	    $.ajax({
	        type:'get',
			url:"<?php echo $this->createUrl('../allfunc/selfrj',array('companyId'=>$this->companyId,));?>/dpid/"+dpid+"/btime/"+btime+"/etime/"+etime,
			async: false,
			
	        //cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg)
	            {            
		            
			        layer.msg("成功！");
			          
		            //location.reload();
	            }else{
		            layer.msg("11");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});
	$("#rijieOrders").on("click",function(){
		
	    var dpid = $("#dpid").val();
	    var btime = $("#btime").val();
	    var etime = $("#etime").val();
	    //alert(dpid);
	    $.ajax({
	        type:'get',
			url:"<?php echo $this->createUrl('cfceshi/selfrjs',array('companyId'=>$this->companyId,));?>/dpid/"+dpid+"/btime/"+btime+"/etime/"+etime,
			async: false,
			
	        //cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg)
	            {            
		            
			        layer.msg("成功！");
			          
		            //location.reload();
	            }else{
		            layer.msg("11");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});
	$("#rijieOrderReport").on("click",function(){
		
	    var dpid = $("#dpid").val();
	    var btime = $("#btime").val();
	    var etime = $("#etime").val();
	    //alert(dpid);
	    $.ajax({
	        type:'get',
			url:"<?php echo $this->createUrl('../allfunc/rijieing',array('companyId'=>$this->companyId,));?>/dpid/"+dpid+"/btime/"+btime+"/etime/"+etime,
			async: false,
			
	        //cache:false,
	        dataType:'json',
			success:function(msg){
	            //alert(msg.status);
	            if(msg)
	            {            
		            
			        layer.msg("成功！");
			          
		            //location.reload();
	            }else{
		            layer.msg("11");
		            //location.reload();
	            }
			},
	        error:function(){
				layer.msg("<?php echo yii::t('app','失败'); ?>"+"2");                                
			},
		});
	    
	});
	</script>