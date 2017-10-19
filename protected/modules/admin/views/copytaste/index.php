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
         	<h4 class="modal-title">选择需要下发的店铺</h4>
         	<span style="color:red;"></span>
         </div>
         <div class="modal-body">
	         <div class="portlet-body" id="table-manage">  
		         <div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
			         <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
			         <?php if($dpids):?>
			         <?php foreach($dpids as $dpid):?>
				         <li style="width:50%;float:left;">
					         <div style="width:10%;float:left;"><?php echo $a++;?></div>
					         <div style="width:70%;float:left;"><?php echo $dpid['company_name'];?></div>
					         <div style="width:10%;float:left;">
					         	<input style="height:20px;" type="checkbox" class="checkdpids" value="<?php echo $dpid['dpid'];?>" name="reportlist[]" />
					         </div>
				         </li>
				     <?php endforeach;?>
				     <?php endif;?>
				         <li style="width:100%;">
					         <div style="width:10%;float:left;"></div>
					         <div style="width:60%;float:left;"></div>
					         <div style="width:14%;float:right;">
					         	<input style="height:20px;" type="checkbox" class="group-checkable" data-set="#reportlistdiv .checkdpids" />
					         	全选
					         </div>
					         
				         </li>                                                                       
			         </ul>
		         </div>
	         </div>
	         <div class="modal-footer">
		         <button id="printall" type="button" class="btn blue">确认下发</button>
		         <!-- button id="selectall" type="button" class="btn blue">全选</button> -->
		         <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
		         
	         </div>
		 </div>
				                	
		</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','基础设置'),'subhead'=>yii::t('app','口味列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','口味下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copytaste-form',
				'action' => $this->createUrl('copytaste/storTaste' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','口味列表');?></div>
					<div class="actions">
						
<!-- 						<a href="<?php echo $this->createUrl('product/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添加');?></a>-->
							<div class="btn-group"> 
							<button type="button" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','口味下发');?></button>
 						</div> 
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th style="width:10%" class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /><?php echo yii::t('app','全选');?></th>
								<th style="width:25%"><?php echo yii::t('app','名称');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input id="<?php echo $model->lid;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" tghs_code="<?php echo $model->tghs_code;?>" tgname="<?php echo $model->name;?>" name="ids[]" />
								</td>
								<td style="width:25%"><?php echo $model->name;?><?php switch ($model->allflae){case 0:echo '   （单品）';break;case 1 : echo '   （整单）';break;default:echo '';break;}?></td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="tghscode" name="tghscode" value="" />
						<input type="hidden" id="dpids" name="dpids" value="" />
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

	$("#su").on('click',function() {
		
		var aa = document.getElementsByName("ids[]");
        var codep=new Array();
        var name = '';
        
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                var tgname = aa[i].getAttribute("tgname");
                var tghscode = aa[i].getAttribute("tghs_code");
                if(tghscode==''){
                    name = tgname +'，'+ name;
                    }
                codep += aa[i].getAttribute("tghs_code") +',';
            }
        }
        if(codep!=''){
        	codep = codep.substr(0,codep.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的套餐！！！');?>");
       		return false;
       	}
       	if(name){
           	alert('下列口味没有编码，如要继续下发请先删除下列口味再重新添加，即可继续下发! 如下口味：'+name);
			return false;
           	}
        //alert(codep);
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
	            var dpids="";
	            $('.checkdpids:checked').each(function(){
	                dpids += $(this).val()+',';
	                //alert(dpids);
	            });
	            if(dpids!=''){
	            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
	            	//alert(dpids);
	            	$("#dpids").val(dpids);
	            	$("#tghscode").val(codep);
	    	        $("#copytaste-form").submit();
		            }else{
						alert("请选择店铺。。。");return;
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