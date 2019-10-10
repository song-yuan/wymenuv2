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
    <div class="portlet purple box">
      <div class="portlet-title">
             <div class="caption"><i class="fa fa-group"></i>产品原料消耗</div>
             <div class="actions"></div>
        </div>
        <div class="portlet-body">
        	<?php $form=$this->beginWidget('CActiveForm', array(
		                    'id'=>'Promote',
		                    'clientOptions'=>array(
		                            'validateOnSubmit'=>true,
		                    ),
		                    'htmlOptions'=>array(
		                            'class'=>'form-inline'
		                    ),
		            )); ?>
            <div class="row">
            	<div class="col-md-4 col-sm-4"></div>
            	<div class="col-md-4 col-sm-4">
		            <div class="form-group more-condition" style="float:left;width:400px;">
		                 <div class="input-group" style="width:95%;">
		                 <span class="input-group-addon">选择分类</span>
		                       <select class="form-control" name="category">
		                       		<option value="0">全部</option>
		                       		<?php if(isset($categorys['0000000000'])): 
		                       			foreach ($categorys['0000000000'] as $cat):
		                       			$clid = $cat['lid'];
		                       			if(isset($categorys[$clid])):?>
		                       			<optgroup label="<?php echo $cat['category_name'];?>">
			                       		<?php foreach ($categorys[$clid] as $cate):?>
			                       		<option value="<?php echo $cate['lid'];?>"><?php echo $cate['category_name'];?></option>
			                       		<?php endforeach;?>
			                       		</optgroup>
			                       		<?php endif;
			                       		 endforeach;
			                       		 endif;?>
		                       </select>
		                </div>
		            </div>
		         </div>
		         <div class="col-md-4 col-sm-4"></div>
            </div>
            <br>
            <div class="row">
            	<div class="col-md-4 col-sm-4"></div>
            	<div class="col-md-4 col-sm-4">
		            <div class="form-group more-condition" style="float:left;width:400px;">
		                 <div class="input-group" style="width:95%;">
		                 <span class="input-group-addon">选择产品</span>
		                       <select class="form-control" name="product">
		                       	<?php foreach ($products as $product):?>
		                       	<option category-id="<?php echo $product['category_id'];?>" value="<?php echo $product['lid'];?>"><?php echo $product['product_name'];?></option>
		                       	<?php endforeach;?>
		                       </select>
		                </div>
		            </div>
		         </div>
		         <div class="col-md-4 col-sm-4"></div>
            </div>
            <br>
            <div class="row">
            	<div class="col-md-4 col-sm-4"></div>
            	<div class="col-md-4 col-sm-4">
		            <div class="form-group more-condition" style="float:left;width:400px;">
		                 <div class="input-group" style="width:95%;">
		                 <span class="input-group-addon">产品数量</span>
		                  <input type="text" name="number" class="form-control" style="width:200px;" placeholder="请输入产品数量" value="1"/>     
		                </div>
		            </div>
		         </div>
		         <div class="col-md-4 col-sm-4"></div>
            </div>
            <br>
            <div class="row">
	            <div class="col-md-6 col-sm-6"></div>
	            <div class="col-md-6 col-sm-6">
	            	<button type="button" id="productBom" class="btn blue">确定</button>
	            </div>
            </div>
            <?php $this->endWidget(); ?>
        </div>
        </div> 
    </div>
  </div>
</div>

<script>
    $(function(){
        $('select[name="category"]').change(function(){
            var v = $(this).val();
            var pobj = $('select[name="product"]');
            if(v=='0'){
            	pobj.find('option').show();
            }else{
            	pobj.find('option').hide();
            	pobj.find('option[category-id="'+v+'"]').show();
            }
        });
      	$('#productBom').click(function() {
          if(confirm('是否确定要为该产品手动消耗原料？')==true){
              	var pId = $('select[name="product"]').val();
              	var number = $('input[name="number"]').val();
              	if(isNaN(number)){
                  	alert('请输入正确的数量');
                  	return;
                }
                var url = "<?php echo $this->createUrl('waimai/productBom',array('companyId'=>$this->companyId));?>";
                $.ajax({
                        url:url,
                        type:'POST',
                        data:{productId:pId,number:number},
                        dataType: "json",
                        success:function(msg){
                            var data = msg;
                            if(data.status){
                                 alert(data.msg); 
                            }else{
                            	alert(data.msg);
                            }
                        }
                 });
          	}
       	});
      
    });
</script>