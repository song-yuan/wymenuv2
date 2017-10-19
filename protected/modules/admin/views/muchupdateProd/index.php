<script type="text/javascript">
function fun()
		{
			
			if($(this).checked){
				document.$("FirstItem").checked;
			}

		};
		
		
		
</script>
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
	
	
	<div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">		                
         <div class="modal-header">
         	<h4 class="modal-title">选择需要修改的单品属性</h4><span style="color: red;">(请在需要修改的选项前打钩，否则，设置无效!)</span>
         </div>
         <div class="modal-body">
	         <div class="portlet-body" id="table-manage">  
		         <div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
			         <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
			         
				         <li style="width:50%;height:33px;float:left;margin-bottom:10px;">
					         <div style="width:5%;float:left;margin-left:5%;"><?php echo $a++;?></div>
					         <input style="height:20px;" type="checkbox" class="checkdpids" value="price" name="reportlist[]" />
					         <div style="width:30%;float:left;"><?php echo '原价';?></div>
					         <input style="width:50%;float:right;height:30px;" type="text" class="" id="price" />
				         </li>
				     	 <li style="width:50%;height:33px;float:left;margin-bottom:10px;">
					         <div style="width:5%;float:left;margin-left:5%;"><?php echo $a++;?></div>
					         <input style="height:20px;" type="checkbox" class="checkdpids" value="memberprice" name="reportlist[]" />
					         <div style="width:30%;float:left;"><?php echo '会员价';?></div>
					         <input style="width:50%;float:right;height:30px;" type="text" class="" value="" id="memberprice" />
				         </li>
				         <li style="width:50%;height:33px;float:left;margin-bottom:10px;">
					         <div style="width:5%;float:left;margin-left:5%;"><?php echo $a++;?></div>
					         <input style="height:20px;" type="checkbox" class="checkdpids" value="sort" name="reportlist[]" />
					         <div style="width:30%;float:left;"><?php echo '排序大小';?></div>
					         <input style="width:50%;float:right;height:30px;" type="text" class="" value="" id="sort" />
				         </li>
				         <li style="width:50%;height:33px;float:left;margin-bottom:10px;">
					         <div style="width:5%;float:left;margin-left:5%;"><?php echo $a++;?></div>
					         <input style="height:20px;" type="checkbox" class="checkdpids" value="dabaofee" name="reportlist[]" />
					         <div style="width:30%;float:left;"><?php echo '打包费';?></div>
					         <input style="width:50%;float:right;height:30px;" type="text" class="" value="" id="dabaofee" />
				         </li>  
				         <li style="width:50%;height:33px;float:left;margin-bottom:10px;">
					         <div style="width:5%;float:left;margin-left:5%;"><?php echo $a++;?></div>
					         <input style="height:20px;" type="checkbox" class="checkdpids" value="ismemberdiscount" name="reportlist[]" />
					         <div style="width:45%;float:left;"><?php echo '是否参与会员折扣';?></div>
					         <select style="width:35%;float:right;height:30px;" type="text" class="" value="" id="ismemberdiscount" >
					         	<option value="0">否</option>
					         	<option value="1">是</option>
					         </select>
				         </li>
				         <li style="width:50%;height:33px;float:left;margin-bottom:10px;">
					         <div style="width:5%;float:left;margin-left:5%;"><?php echo $a++;?></div>
					         <input style="height:20px;" type="checkbox" class="checkdpids" value="isdiscount" name="reportlist[]" />
					         <div style="width:30%;float:left;"><?php echo '可折';?></div>
					         <select style="width:50%;float:right;height:30px;" type="text" class="" id="isdiscount" >
					         	<option value="0">否</option>
					         	<option value="1">是</option>
					         </select>
				         </li>
				         <li style="width:50%;height:33px;float:left;margin-bottom:10px;">
					         <div style="width:5%;float:left;margin-left:5%;"><?php echo $a++;?></div>
					         <input style="height:20px;" type="checkbox" class="checkdpids" value="isshow" name="reportlist[]" />
					         <div style="width:30%;float:left;"><?php echo '可售';?></div>
					         <select style="width:50%;float:right;height:30px;" type="text" class="" id="isshow" >
					         	<option value="0">否</option>
					         	<option value="1">是</option>
					         </select>
				         </li> 
				         <li style="width:40%;height:33px;float:right;margin-left:50%;">
					         <div style="width:5%;float:left;margin-left:5%;"></div>
					         <div style="width:30%;float:left;"><?php echo '全选';?></div>
					         <input style="height:20px;float:right;" type="checkbox" class="group-checkable" value="<?php ?>" data-set="#reportlistdiv .checkdpids" />
				         </li>                                                                    
			         </ul>
		         </div>
	         </div>
	         <div class="modal-footer">
		         <button id="printall" type="button" class="btn blue">确认修改</button>
		         <!-- button id="selectall" type="button" class="btn blue">全选</button> -->
		         <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
		         
	         </div>
		 </div>
				                	
		</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','产品批量修改'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'muchupdateProd-form',
				'action' => $this->createUrl('muchupdateProd/storProduct' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
	<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','产品列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
<!-- 						<a href="<?php echo $this->createUrl('product/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>-->
							<div class="btn-group"> 
							<button type="button" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','菜单批量修改');?></button>
 						</div> 
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /><?php echo yii::t('app','全选');?></th>
								<th style="width:15%"><?php echo yii::t('app','名称');?></th>
								<th><?php echo yii::t('app','类别');?></th>
								<th><?php echo yii::t('app','现价');?></th>
								<th><?php echo yii::t('app','会员价');?></th>
								<th><?php echo yii::t('app','排序号');?></th>
								<th><?php echo yii::t('app','打包费');?></th>
								<th><?php echo yii::t('app','是否参与会员折扣');?></th>
								<th><?php echo yii::t('app','是否允许折扣');?></th>
								<th><?php echo yii::t('app','是否允许销售');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input id="<?php echo $model->lid;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" phs_code="<?php echo $model->phs_code;?>" chs_code="<?php echo $model->chs_code;?>" name="ids[]" />
								</td>
								<td style="width:25%"><?php echo $model->product_name;?></td>
								<td><?php if(!empty($model->category->category_name)) echo $model->category->category_name;?></td>
								<td ><?php echo $model->original_price;?></td>
								<td ><?php echo $model->member_price;?></td>
								<td ><?php echo $model->sort;?></td>
								<td ><?php echo $model->dabao_fee;?></td>
								<td ><?php echo $model->is_member_discount=='0'?yii::t('app','否'):yii::t('app','是');?></td>
								<td ><?php echo $model->is_discount=='0'?yii::t('app','否'):yii::t('app','是');?></td>
                                <td ><?php echo $model->is_show=='0'?yii::t('app','否'):yii::t('app','是');?></td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="pids" name="pids" value="" />
						<input type="hidden" id="prodids" name="prodids" value="" />
						<input type="hidden" id="nums" name="nums" value="" />
						</div>
					</table>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		
		$('#product-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
		$('.s-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('muchupdateProd/status',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('muchupdateProd/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('muchupdateProd/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});


	$("#su").on('click',function() {
		var aa = document.getElementsByName("ids[]");
        var pids = new Array();
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                pids += aa[i].getAttribute("value") +',';
            }
        }
        if(pids!=''){
        	pids = pids.substr(0,pids.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的菜品！！！');?>");
       		return false;
       	}
        
		if(window.confirm("确认进行此项操作?")){
			layer_index_printreportlist=layer.open({
	            type: 1,
	            shade: false,
	            title: false, //不显示标题
	            area: ['60%', '60%'],
	            content: $('#printRsultListdetail'),//$('#productInfo'), //捕获的元素
	            cancel: function(index){
	                layer.close(index);
	                layer_index_printreportlist=0;                                                                                                     
	            }
	        });
			$("#printall").on("click",function(){
	            //alert("暂无权限！！！");
	            var dpids =new Array();
	            var prodids="";
	            var nums = "";
	            $('.checkdpids:checked').each(function(){
	                prodids += $(this).val()+',';
	                var id = $(this).val();
	                var num = $('#'+id).val();
	                if(num==""){
						alert("请填写勾选的选项！");
						return false;
		                }
	                nums += id+'@'+$('#'+id).val()+',';
	                //alert(ids);alert(nums);
	            });
	            if(prodids!=''&&nums!=''){
	            	prodids = prodids.substr(0,prodids.length-1);//除去最后一个“，”
	            	nums = nums.substr(0,nums.length-1);//除去最后一个“，”
	            	//alert(dpids);
	            	$("#pids").val(pids);
	            	$("#prodids").val(prodids);
	            	$("#nums").val(nums);
	            	//alert(ids);alert(prodids);alert(nums);
	    	        $("#muchupdateProd-form").submit();
		            }else{
						alert("请勾选需要修改的选项。。。");return;
			            }
			});
	        $("#closeall").on('click',function(){
		        //alert("123");
		        layer.closeAll();
		        layer_index_printerportlist = 0;
		        });
	    }else{
			return false;
			}
	});
	
	</script>	