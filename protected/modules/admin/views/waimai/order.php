<div class="page-content">
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖订单'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','订单查询'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('waimai/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
<div class="row">   
    <div class="col-md-12">
	<div class="row">
        <div class="col-md-12 col-sm-12">
           <?php $form=$this->beginWidget('CActiveForm', array(
                    'id'=>'Promote',
                    'clientOptions'=>array(
                            'validateOnSubmit'=>true,
                    ),
                    'htmlOptions'=>array(
                            'class'=>'form-inline'
                    ),
            )); ?>
            <div class="form-group more-condition" style="float:left;width:200px;">
                 <div class="input-group" style="width:95%;">
                 <span class="input-group-addon">订单类型</span>
                       <select class="form-control" name="orderType">
                           <option value="1" <?php if($orderType==1){echo 'selected';}?>>美团</option>
                           <option value="2" <?php if($orderType==2){echo 'selected';}?>>饿了么</option>
                       </select>
                </div>
            </div>
            <div class="input-group" style="float:left;width:700px;margin-bottom:15px;">
                  <span class="input-group-addon">外卖订单号</span><input type="text" name="orderId" class="form-control" style="width:200px;" placeholder="请输入订单号" value="<?php echo $orderId;?>"/>
                  <button type="submit" class="btn green">
                         <i class="fa fa-search">查找 &nbsp;</i>
                  </button>
              </div>
             <?php $this->endWidget(); ?>
         </div>
    <div class="portlet purple box">
    	<div class="portlet-title">
             <div class="caption"><i class="fa fa-group"></i>订单信息</div>
             <div class="actions"></div>
        </div>
        <div class="portlet-body">
             <?php if($hasOrder):?>
              <p>该订单已经存在</p>
             <?php else:?>
             	<?php if($data!=''):?>
                <?php 
                $dataObj = json_decode($data); 
                ?>
                <table>
                <?php if($orderType==1):
                	if(isset($dataObj->data)){
                	$obj = $dataObj->data;?>
                	<tr><td cospan="2">需要补充的订单信息,如下:</td></tr>
                   <tr><td>序号:</td><td><?php echo $obj->daySeq;?></td></tr>
	               <tr><td>订单编号:</td><td><?php echo $obj->orderId;?></td></tr>
	               <tr><td>订单时间:</td><td><?php echo date('Y-m-d H:i:s',$obj->cTime);?></td></tr>
	               <tr><td>产品详情:</td>
	               	<td><?php $detail = json_decode($obj->detail); foreach($detail as $dt){ echo $dt->food_name.' ';}?></td>
	               </tr>
	               <tr><td>收货人名称:</td><td><?php echo $obj->recipientName;?></td></tr>
	               <tr><td>收货人电话:</td><td><?php echo $obj->recipientPhone;?></td></tr>
	               <tr><td>收货人地址:</td><td><?php echo $obj->recipientAddress;?></td></tr>
	               <tr><td cospan="2"><button type="button" id="createOrder" class="btn blue">确定</button></td></tr>
                	<?php }else{?>
                	<tr><td cospan="2">未查询到订单,请确认下订单号是否输入正确</td></tr>
                	<?php }?>
                <?php else: 
                if(isset($dataObj->result)){
                $obj = $dataObj->result;?>
                  <tr><td cospan="2">需要补充的订单信息,如下:</td></tr>
                  <tr><td>序号:</td><td><?php echo $obj->daySn;?></td></tr>
                  <tr><td>订单编号:</td><td><?php echo $obj->id;?></td></tr>
                  <tr><td>订单时间:</td><td><?php echo $obj->createdAt;?></td></tr>
                  <tr><td>产品详情:</td>
	               	<td>
	               	<?php $groups = $obj->groups; 
	               	foreach($groups as $gr){ 
	               		$items = $gr->items;
	               		foreach ($items as $item){
	               			echo $item->name.' ';
	               		}
	               	}
	               	?>
	               	</td>
	               </tr>
	               <tr><td>收货人名称:</td><td><?php echo $obj->consignee;?></td></tr>
	               <tr><td>收货人电话:</td><td><?php echo $obj->phoneList[0];?></td></tr>
	               <tr><td>收货人地址:</td><td><?php echo $obj->deliveryPoiAddress;?></td></tr>
	               <tr><td cospan="2"><button type="button" id="createOrder" class="btn blue">确定</button></td></tr>
                <?php }else{?>
                <tr><td cospan="2">未查询到订单,请确认下订单号是否输入正确</td></tr>
                <?php }?>
                <?php endif;?>
                </table>
                <?php endif;?>
              <?php endif;?>
        </div>
        </div> 
    </div>
	</div>
</div>

<script>
    $(function(){
    	$('#createOrder').click(function() {
        	if(confirm('是否要确定生成外卖订单吗？')==true){
                var url = "<?php echo $this->createUrl('waimai/dealOrder',array('companyId'=>$this->companyId));?>";
                $.ajax({
                        url:url,
                        type:'POST',
                        data:{type:'<?php echo $orderType;?>',data:'<?php echo urlencode($data);?>'},//CF
                        dataType: "json",
                        success:function(msg){
                            var data=msg;
                            if(data.status){
                                 alert('订单生成成功'); 
                            }else{
                            	alert('订单生成失败');  
                            }
                        }
                 });
        	}
    	 });
    });
</script>