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
         	<h4 class="modal-title">选择需要下发菜品的店铺</h4>
         </div>
         <div>
         <span>选择店铺类型</span>
         <select id="selectype">
         <option value="0">全部</option>
         <option value="3">微店</option>
         <option value="1">非微店</option>
         </select>
         </div>
         <div class="modal-body">
	         <div class="portlet-body" id="table-manage">
		         <div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
			         <ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
			         <?php if($dpids):?>
			         <?php foreach($dpids as $dpid):?>
				         <li style="width:50%;float:left;" class="company <?php $a=$dpid['is_rest']; if($a == 3)echo 'wxdp';else echo 'ortherdp';?>">
					         <div style="width:20%;float:left;"><?php echo (int)$dpid['dpid'];?></div>
					         <div style="width:10%;float:left;">
					         	<input style="height:20px;" type="checkbox" class="checkdpids ckall <?php if($a == 3) echo 'ckwx';else echo 'ckor';?>" value="<?php echo $dpid['dpid'];?>" name="reportlist[]" id="rep<?php echo $dpid['dpid'];?>"/>
					         </div>
					         <div style="width:70%;float:left;"><label for="rep<?php echo $dpid['dpid'];?>"><?php echo $dpid['company_name'];?></label></div>
				         </li>
				     <?php endforeach;?>
				     <?php endif;?>
				         <li style="width:100%;">
					         <div style="width:10%;float:left;"></div>
					         <div style="width:60%;float:left;"></div>
					         <div style="width:14%;float:right;">
					         	<input id="checkall" style="height:20px;" type="checkbox" class="group-checkable checkall" cate="ckall" />
					         	全选
					         </div>

				         </li>
			         </ul>
		         </div>
	         </div>
	         <div class="modal-footer">
	         	<button ctp="1" type="button" class="btn blue printall" title="会覆盖已存在的下发产品">普通下发</button>
		        <button ctp="2" type="button" class="btn blue printall" title="先清空以前下发的产品，再进行下发">覆盖下发</button>
		       	<button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
	         </div>
	         <span style="color:red;">注意 : 下发可能需要点时间,请耐心等待</span>
		 </div>

		</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','基础设置'),'subhead'=>yii::t('app','原料列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','原料信息'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>4,))),array('word'=>yii::t('app','原料下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>4)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copymaterial-form',
				'action' => $this->createUrl('copymaterial/storMaterial' , array('companyId' => $this->companyId)),
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
							<button type="button" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','品项下发');?></button>
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
								<th><?php echo yii::t('app','类别');?></th>
								<th><?php echo yii::t('app','入库单位');?></th>
								<th><?php echo yii::t('app','零售单位');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td><input id="<?php echo $model->lid;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" mname="<?php echo $model->material_name;?>" mphs_code="<?php echo $model->mphs_code;?>" mchs_code="<?php echo $model->mchs_code;?>" mulhs_code="<?php echo $model->mulhs_code;?>" mushs_code="<?php echo $model->mushs_code;?>" name="ids[]" />
								</td>
								<td style="width:25%"><?php echo $model->material_name;?></td>
								<td><?php if(!empty($model->category->category_name)) echo $model->category->category_name;?></td>
								<td ><?php echo Common::getStockName($model->stock_unit_id);?></td>
								<td ><?php echo Common::getStockName($model->sales_unit_id);?></td>
								
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="mphscode" name="mphscode" value="" />
						<input type="hidden" id="mchscode" name="mchscode" value="" />
						<input type="hidden" id="mulhscode" name="mulhscode" value="" />
						<input type="hidden" id="mushscode" name="mushscode" value="" />
						<input type="hidden" id="dpids" name="dpids" value="" />
						<input type="hidden" id="ctp" name="ctp" value="" />
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
		
	
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('copymaterial/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});

	$('#selectype').change(function(){
		var type = $(this).val();
		if(type == 0){
			$('.company').show();
			$('#checkall').attr('cate','ckall');
		}else if(type == 3){
			$('.company').hide();
			$('.wxdp').show();
			$('#checkall').attr('cate','ckwx');
		}else{
			$('.company').show();
			$('.wxdp').hide();
			$('#checkall').attr('cate','ckor');
		}
	})
	$('#checkall').change(function(){
		
		var a = $(this).attr('cate');
		var b = $(this).attr('checked');
		if(b){
			$("."+a).each(function () {
	            $(this).attr("checked", true);
	        });
		}else{
			$("."+a).each(function () {
	            $(this).attr("checked", false);
	        });
	    }
	})
	$("#su").on('click',function() {
		
        //alert(11);
		var aa = document.getElementsByName("ids[]");
		//var aa = document.getElementsByName("ids[]");
        var codemp=new Array();
        var codemc=new Array();
        var codemul=new Array();
        var codemus=new Array();
        var nullm=new Array();
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                //var str = aa[i].getAttribute("chs_code");
                //alert(str);
                if(aa[i].getAttribute("mphs_code") == '' || aa[i].getAttribute("mchs_code") =='' || aa[i].getAttribute("mulhs_code") =="" || aa[i].getAttribute("mushs_code") ==''){
                	nullm += aa[i].getAttribute("mname") +',';
					//alert('该菜品添加时出错，请删除重新添加，否则无法下发该菜品');
					//return false;
                }else{
                    codemp += aa[i].getAttribute("mphs_code") +',';
                    codemc += aa[i].getAttribute("mchs_code") +',';
                    codemul += aa[i].getAttribute("mulhs_code") +',';
                    codemus += aa[i].getAttribute("mushs_code") +',';
                }
            }
        }
        if(nullm !=''){
            alert('下列菜品在添加时出错，无法进行下发操作，如若继续下发，请重新添加下列品项：['+nullm+']');
            return false;
            }else{
                if(codemp !='' && codemc !='' && codemul !='' && codemus !=''){
                	codemp = codemp.substr(0,codemp.length-1);//除去最后一个“，”
                	codemc = codemc.substr(0,codemc.length-1);//除去最后一个“，”
                	codemul = codemul.substr(0,codemul.length-1);//除去最后一个“，”
                	codemus = codemus.substr(0,codemus.length-1);//除去最后一个“，”
                }else{
               	 	alert("<?php echo yii::t('app','部分菜品无法下发！！！');?>");
               		return false;
               	}
            }


        //进行测试，暂且屏蔽下面这段代码。。。
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
			$(".printall").on("click",function(){
	            //alert("暂无权限！！！");
	            var ctp = $(this).attr('ctp');
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
	            	$("#mchscode").val(codemc);
	            	$("#mphscode").val(codemp);
	            	$("#mulhscode").val(codemul);
	            	$("#mushscode").val(codemus);
	            	$("#ctp").val(ctp);
	    	        $("#copymaterial-form").submit();
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