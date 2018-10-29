<style>
	li{list-style: none;}
	#printall{margin-top:5%;}
</style>
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



	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>$this->createUrl('entityMarket/list' , array('companyId' => $this->companyId,'type'=>0,))),array('word'=>yii::t('app','买送优惠'),'url'=>$this->createUrl('buysentpromotion/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','活动下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('buysentpromotion/index' , array('companyId' => $this->companyId,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copyproduct-form',
				'action' => $this->createUrl('copypromotion/storfullsent' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','活动下发');?></div>
					<div class="actions">

							<div class="btn-group">
							<button type="button" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','下发');?></button>
 						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th style="width:10%" class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /><?php echo yii::t('app','全选');?></th>
								<th style="width:25%"><?php echo yii::t('app','活动名称');?></th>
								<th><?php echo yii::t('app','活动详情');?></th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
 						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td>
									<input id="<?php echo $model->lid;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" code="<?php echo $model->sole_code;?>" name="ids[]" />
								</td>
								<td style="width:25%"><?php echo $model->promotion_title;?></td>
								<td><?php echo $model->promotion_abstract;?>&nbsp&nbsp&nbsp<?php echo '活动时间：'.$model->begin_time;echo '~~'; echo $model->end_time;?></td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="code" name="code" value="" />
						<input type="hidden" id="dpids" name="dpids" value="" />
						<input type="hidden" id="ckc" name="ckc" value="" />
						</div>
					</table>
					</div>

				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>


	<!-- 最后确认弹出窗 -->

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
         <span>&nbsp;清除该时间段以前的活动</span><input type="checkbox" id="ckclear" />
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
		         <button id="printall" type="button" class="btn blue">确认下发</button>
		         <!-- button id="selectall" type="button" class="btn blue">全选</button> -->
		         <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
	         </div>
	         <span style="color:red;">注意 : 下发可能需要点时间,请耐心等待</span>
		 </div>

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
		var aa = document.getElementsByName("ids[]");
        var codep='';
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                codep += aa[i].getAttribute("code") +',';
            }
        }//获取活动编码
        if(codep!=''){
        	codep = codep.substr(0,codep.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的活动！！！');?>");
       		return false;
       	}
		if(window.confirm("确认进行此项操作?")){
			//弹出店铺选择对话窗口
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

	        //获取店铺的dpid
			$("#printall").on("click",function(){
				var clear = $('#ckclear').attr('checked');
				if(clear){ckc = 1;}else{ckc = 2;}
	            var dpids =new Array();
	            var dpids="";
	            $('.checkdpids:checked').each(function(){
	                dpids += $(this).val()+',';
	            });
	            if(dpids!=''){
	            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
	            	$("#dpids").val(dpids);
	            	$("#code").val(codep);
	            	$("#ckc").val(ckc);
	    	        $("#copyproduct-form").submit();
	            }else{
					alert("请选择店铺。。。");return;
		            }
			});
	        $("#closeall").on('click',function(){
		        layer.closeAll();
		        layer_index_printerportlist = 0;
		        });
	    }else{
			return false;
			}
	});

	</script>