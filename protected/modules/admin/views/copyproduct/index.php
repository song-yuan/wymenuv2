<script type="text/javascript">
function fun()
		{

			if($(this).checked){
				document.$("FirstItem").checked;
			}

		};



</script>
<?php //$arr_dpid = Yii::app()->request->getParam('arr_dpid'); ?>
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
					         <div style="width:20%;float:left;"><?php echo $dpid['dpid']%1000;?></div>
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

				<select name="groups" id="groups" class="btn" style="border:1px solid gray;" disabled>
					<?php if (!$groups):?>
						<option value="">您还没有添加区域价格分组,(默认总部价格)</option>
					<?php else:?>
						<option value="0" >-默认(已设置)-</option>
						<?php foreach($groups as $group ): ?>
							<option value="<?php echo $group['lid']; ?>" >-<?php echo $group['group_name']; ?>-</option>
						<?php endforeach; ?>
					<?php endif;?>
				</select>
		         <button ctp="1" type="button" class="btn blue printall" title="会覆盖已存在的下发产品">普通下发</button>
		         <button ctp="2" type="button" class="btn blue printall" title="先清空以前下发的产品，再进行下发">覆盖下发</button>
		         <!-- button id="selectall" type="button" class="btn blue">全选</button> -->
		         <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
		         
	         </div>
	         <span style="color:red;">注意 : 下发过程不可逆，请谨慎！！！</span>
	         <span style="color:red;">注意 : 下发可能需要点时间,请耐心等待</span>
		 </div>

		</div>
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','产品下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copyproduct-form',
				'action' => $this->createUrl('copyproduct/storProduct' , array('companyId' => $this->companyId)),
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
						<a id="su"  class="btn yellow" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','菜单下发');?></a>
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
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
								<th><?php echo yii::t('app','总部价格');?></th>
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

							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="phscode" name="phscode" value="" />
						<input type="hidden" id="chscode" name="chscode" value="" />
						<input type="hidden" id="dpids" name="dpids" value="" />
						<input type="hidden" id="pgroups" name="groups" value="" />
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

	<div id="noticed" style="margin:0;padding:0;display:none;width:96%;height:96%;">
         <div class="modal-header">
         	<span style="color:red;font:900 35px '微软雅黑' ;">注意 :</span>
         </div>
         <div class="modal-body">
	         <div class="portlet-body" id="table-manage">
		         <div id="report" style="color:red;display:inline-block;width:100%;font:900 35px '微软雅黑' ;">
			        请勿关闭网页,正在跑步下发菜品,让子弹飞一会儿......
		         </div>
	         </div>
	         <div class="modal-footer">
		         <button id="closeall1" type="button" class="btn default" data-dismiss="modal">关闭</button>
	         </div>
		 </div>
	</div>	

	<div id="noticed2" style="margin:0;padding:0;display:none;width:96%;height:96%;">
         <div class="modal-header">
         	<span style="color:red;font:900 25px '微软雅黑' ;">注意 : 以下店铺请重新下发</span>
         </div>
         <div class="modal-body">
	         <div class="portlet-body" id="table-manage">
		         <div id="report" style="display:inline-block;width:100%;">
			        <table class="table table-striped table-bordered table-hover">
						<thead>
							<?php if($arr_dpid):foreach ($arr_dpid as $v):foreach ($dpids as $dpid): ?>
								<?php if ($v==$dpid['dpid']): ?>
							<tr>
								<th><?php echo yii::t('app',$dpid['company_name']);?></th>
							</tr>
							<?php endif;endforeach;endforeach;endif;?>
						</thead>
					</table>
		         </div>
	         </div>
	         <div class="modal-footer">
		         <button id="closeall2" type="button" class="btn default" data-dismiss="modal">关闭</button>
	         </div>
		 </div>
	</div>



	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){
		<?php if($arr_dpid != ''): ?>
			// alert(111);
			layer_index_printreportlist=layer.open({
	            type: 1,
	            shade: false,
	            title: false, //不显示标题
	            area: ['60%', '60%'],
	            content: $('#noticed2'),//$('#productInfo'), //捕获的元素
	            cancel: function(index){
	                layer.close(index);
	                layer_index_printreportlist=0;
	            }
	        });
	        $("#closeall2").on('click',function(){
		        //alert("123");
		        layer.closeAll();
		        layer_index_printerportlist = 0;
		        });
		<?php endif; ?>
		$('#product-form').submit(function(){
			if(!$('.checkboxes:checked').length){
				alert("<?php echo yii::t('app','请选择要删除的项');?>");
				return false;
			}
			return true;
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('copyproduct/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
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
        var codep=new Array();
        var codec=new Array();
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                //var str = aa[i].getAttribute("chs_code");
                codep += aa[i].getAttribute("phs_code") +',';
            }
        }
        if(codep!=''){
        	codep = codep.substr(0,codep.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的菜品！！！');?>");
       		return false;
       	}

        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                //var str = aa[i].getAttribute("chs_code");
                codec += aa[i].getAttribute("chs_code") +',';
            }
        }
        if(codec!=''){
        	codec = codec.substr(0,codec.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的菜品！！！');?>");
       		return false;
       	}
     	//alert(str);

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
			if(window.confirm("请将鼠标悬浮至按钮，认真阅读各下发区别，确认后，下发过程不可逆，请谨慎操作！！！")){
	            //alert("暂无权限！！！");
	            var ctp = $(this).attr('ctp');
	            var dpids =new Array();
	            var dpids="";
	            $('.checkdpids:checked').each(function(){
	                dpids += $(this).val()+',';
	            });
	            var groups = $('#groups').find("option:selected").val();
	                // alert(groups);
	            if(dpids!=''){
	            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
	            	//alert(dpids);
	            	$("#dpids").val(dpids);
	            	$("#chscode").val(codec);
	            	$("#phscode").val(codep);
	            	$("#pgroups").val(groups);
	            	$("#ctp").val(ctp);
	    	        $("#copyproduct-form").submit();

	    	        $("#printall").attr('disabled','disabled');
	    	        
					layer_index_printreportlist=layer.open({
			            type: 1,
			            shade: false,
			            title: false, //不显示标题
			            area: ['60%', '60%'],
			            content: $('#noticed'),//$('#productInfo'), //捕获的元素
			            cancel: function(index){
			                layer.close(index);
			                layer_index_printreportlist=0;
			            }
			        });
		            }else{
						alert("请选择店铺。。。");return;
			            }
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