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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','营销活动'),'url'=>$this->createUrl('entityMarket/list' , array('companyId' => $this->companyId,'type'=>0,))),array('word'=>yii::t('app','普通优惠'),'url'=>$this->createUrl('normalpromotion/index' , array('companyId' => $this->companyId,))),array('word'=>yii::t('app','活动下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('normalpromotion/index' , array('companyId' => $this->companyId,)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copyproduct-form',
				'action' => $this->createUrl('copypromotion/storProduct' , array('companyId' => $this->companyId)),
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
									<input id="<?php echo $model->lid;?>" type="checkbox" class="checkboxes" value="<?php echo $model->lid;?>" code="<?php echo $model->normal_code;?>" name="ids[]" />
								</td>
								<td style="width:25%"><?php echo $model->promotion_title;?></td>
								<td><?php echo $model->promotion_abstract;?>&nbsp&nbsp&nbsp<?php echo '活动时间：'.$model->begin_time;echo '~~'; echo $model->end_time;?></td>
							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="code" name="code" value="" />
						<input type="hidden" id="chscode" name="chscode" value="" />
						<input type="hidden" id="dpids" name="dpids" value="" />
						</div>
					</table>


				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
		<?php $this->endWidget(); ?>
	</div>


	<!-- 最后确认弹出窗 -->
	<div id="printRsultListdetail" style="margin:0;padding:0;display:none;width:96%;height:96%;">
		<div class="modal-header">
			<h4 class="modal-title">选择需要下发活动的店铺</h4>
		</div>

		<div class="modal-body">
			<div class="portlet-body" id="table-manage">
				<div id="reportlistdiv" style="display:inline-block;width:100%;font-size:1.5em;">
					<ul style="margin:0;padding:0;list-style:none;"><?php $a=1;?>
						<?php if($dpids):?>
			            <?php foreach($dpids as $dpid):?>
			    	    <li style="width:40%;float:left;">
			    		    <input style="height:20px;display:inline-block; width:10%;" type="checkbox" class="checkdpids" value="<?php echo $dpid['dpid'];?>" name="reportlist[]" id="a<?php echo $a;?>" />
			    		    <label for="a<?php echo $a;?>" style="height:20px;display:inline-block;"><?php echo $a;?>-<?php echo $dpid['company_name'];?></label>
			    		    <input type="hidden" value="<?php echo $a++;?>">
			    	    </li>
			    	    <?php endforeach;?>
					    <?php endif;?>
				        <li style="width:100%;">

						    <input style="height:20px;display:inline-block; width:10%;" type="checkbox" class="group-checkable" data-set="#reportlistdiv .checkdpids" id="all" />
						    <label for="all" style="height:20px;display:inline-block;">全选</label>
		         	        <div>
		         		        <button id="printall" type="button" class="btn blue">确认下发</button>
		         	        </div>
				        </li>
			        </ul>
		        </div>
	        </div>
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
		$('.s-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('copyproductbom/status',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('.r-btn').on('switch-change', function () {
			var id = $(this).find('input').attr('pid');
		    $.get('<?php echo $this->createUrl('copyproductbom/recommend',array('companyId'=>$this->companyId));?>/id/'+id);
		});
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('copyproductbom/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});


	$("#su").on('click',function() {

        //alert(11);
		var aa = document.getElementsByName("ids[]");
		//var aa = document.getElementsByName("ids[]");
        var codep=new Array();
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
	    		//document.write(aa[i].getAttribute("code"));return false;
                //var str = aa[i].getAttribute("chs_code");
                codep += aa[i].getAttribute("code") +',';
            }
        }//获取活动编码

        if(codep!=''){
        	codep = codep.substr(0,codep.length-1);//除去最后一个“，”
        }else{
       	 	alert("<?php echo yii::t('app','请选择要下发的活动！！！');?>");
       		return false;
       	}

     	//alert(str);

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
	            //alert("暂无权限！！！");
	            var dpids =new Array();
	            var dpids="";
	            $('.checkdpids:checked').each(function(){
	                dpids += $(this).val()+',';
	                //alert(dpids);
	            });
	            if(dpids!=''){
	            	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
	            	$("#dpids").val(dpids);
	            	$("#code").val(codep);
	    	        $("#copyproduct-form").submit();
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