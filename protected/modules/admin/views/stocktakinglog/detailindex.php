<style>
	.detailtop{
		width: 100%;
		height: 30px;
		line-height: 30px;
		text-align: center;
	}
	.detailbodyhead{
		width: 96%;
		margin-left: 2%;
		height: 30px;
		line-height: 30px;
		border-top: 1px solid silver;
	}
	.detailbodyend{
		width: 96%;
		margin-left: 2%;
		height: 170px;
		border: 1px solid silver;
	}
	.detailend{
		width: 96%;
		height: 33px;
		margin-left: 2%;
	}
	.detailend .endsave{
		width: 33%;
		float: right;
	}
	.detailend .endsave button{
		padding: 4px 4px;
		float: right;
	}
	.width33{
		width: 33%;
		height: 100%;
		float: left;
		text-align: center;
	}
	.bodyendwidth33{
		width: 33%;
		height: 100%;
		float: left;
		line-height: 170px;
		text-align: center;
		border-right: 1px solid silver;
	}
	.width66{
		width: 66%;
		height: 170px;
		float: left;
		text-align: center;
	}
	.width66 textarea{
		width: 96%;
		height: 92%;
		margin-top: 2%;
		margin-left: 2%;
	}
	.font18{
		font-size: 18px;
	}
	.font20{
		font-size: 20px;
	}
</style>
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
	
	<div id="main2" name="main2" style="min-width: 500px;min-height:300px;display:none;" onMouseOver="this.style.backgroundColor='rgba(255,222,212,1)'" onmouseout="this.style.backgroundColor=''"></div>
	
	<!-- /.modal -->
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','盘点记录'),'url'=>$this->createUrl('stocktakinglog/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','盘点记录详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('stocktakinglog/index' , array('companyId' => $this->companyId)))));?>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'material-form',
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
					<div class="caption"><i class="fa fa-globe"></i><?php $typeStr = '日盘'; if($stockTaking['type']==2){$typeStr = '周盘';}elseif($stockTaking['type']==3){$typeStr = '月盘';} echo yii::t('app','盘点记录详情列表(类型:'.$typeStr.')');?></div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /></th>
								<th style="width:16%"><?php echo yii::t('app','品项名称');?></th>
								<th><?php echo yii::t('app','单位规格');?></th>
								<th><?php echo yii::t('app','单位名称');?></th>
								<th><?php echo yii::t('app','原始库存');?></th>
								<th><?php echo yii::t('app','盘点库存');?></th>
								<th><?php echo yii::t('app','盈亏差值');?></th>
								<th><?php echo yii::t('app','原因备注');?></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php if($models) :?>
						<div style="display: none;" id="storagedetail" val="1"></div>
						<?php 
							foreach ($models as $model):
								$material = Common::getmaterialUnit($model['material_id'],$model['dpid'],0);
						?>
							<tr class="odd gradeX">
								<td><input type="checkbox" class="checkboxes" value="<?php echo $model['lid'];?>" name="ids[]" /></td>
								<td style="width:16%"><?php echo $material['material_name'];?></td>
								<td><?php echo $material['unit_specifications'];?></td>
								<td><?php echo $material['unit_name'];?></td>
								<td><?php echo $model['reality_stock'];?></td>
								<td ><?php echo $model['taking_stock'];?></td>
								<td><?php echo $model['number'];?></td>
								<td><?php echo $model['reasion'];?></td>
								<td class="center">
								<a style="color: #121111;" href="javascript:;"><span id="" detailid="<?php echo $model['lid']; ?>" detailname="<?php echo Common::getmaterialName($model['material_id']);?>" realitystock="<?php echo $model['reality_stock'];?>" takingstock="<?php echo $model['taking_stock'];?>" number="<?php echo $model['number'];?>" reason="<?php echo $model['reasion'];?>" class="reason" style="border:1px solid silver;padding: 4px 6px;background-color: rgb(92, 226, 200);">编辑</span></a>
								</td>
							</tr>
						<?php endforeach;?>
						<?php else:?>
						<div style="display: none;" id="storagedetail" val="0"></div>
						<?php endif;?>
						</tbody>
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
		$('.reason').click(function() {
			  	//alert(111);
			  	$('#orderdetaildiv').remove();
			  	var detaillid = $(this).attr('detailid');
			  	var detailname = $(this).attr('detailname');
			  	var realitystock = $(this).attr('realitystock');
			  	var takingstock = $(this).attr('takingstock');
			  	var number = $(this).attr('number');
			  	var reason = $(this).attr('reason');
				//alert(detailname);
				var prodDetailDivAll = '<div id="orderdetaildiv" detaillid="'+detaillid+'"><div class="detailtop font20">原料名称：'+detailname+'</div>'
										+'<div class="detailbodyhead"><div class="width33 font18">原始库存</div><div class="width33 font18">盘点库存</div><div class="width33 font18">盈亏差值</div><div class="clear"></div></div>'
										+'<div class="detailbodyhead"><div class="width33 font18">'+realitystock+'</div><div class="width33 font18">'+takingstock+'</div><div class="width33 font18">'+number+'</div><div class="clear"></div></div>'
										+'<div class="detailbodyend"><div class="bodyendwidth33 font18">在右侧填写原因</div><div class="width66 font18"><textarea name="reason" id="reasontext" style="ime-mode: active;" class="active"></textarea></div><div class="clear"></div></div>';
				var prodDetailEnd = '<div class="detailend"><div class="endsave" detaillid="'+detaillid+'"><button id="savereason" >确认保存</button></div><div class="clear"></div></div></div>';
				var proDetailpayAll = prodDetailDivAll + prodDetailEnd;
				
				$("#main2").append(proDetailpayAll);
			   layer_reasondiv=layer.open({
				     type: 1,
				     //shift:5,
				     shade: [0.5,'#000'],
				     move:'.detailtop',
				     moveOut:true,
				     offset:['10px','350px'],
				     shade: false,
				     title: false, //不显示标题
				     area: ['auto', 'auto'],
				     content: $('#main2'),//$('#productInfo'), //捕获的元素
				     cancel: function(index){
				         layer.close(index);
				         layer_reasondiv=0;
				     }
				 });
			   layer.style(layer_reasondiv, {
				   backgroundColor: 'rgba(255,255,255,0.7)',
				 });  

			   $('#savereason').click(function() { 
					   var reason = $('#reasontext').val();
					   var detaillid = $('.endsave').attr('detaillid');
					   //alert(reason);
					   //alert(detaillid);
					   //alert(originalp); alert(shouldp);
					   var url = "<?php echo $this->createUrl('stocktakinglog/savereason',array('companyId'=>$this->companyId));?>/detaillid/"+detaillid+"/reason/"+reason;
			            $.ajax({
			                url:url,
			                type:'POST',
			                data:reason,//CF
			                //async:false,
			                dataType: "json",
			                success:function(msg){
			                    var data=msg;
			                    if(data.status){
									alert('成功');
									layer.close(layer_reasondiv);
							         layer_reasondiv=0;
							         location=location;
			                    }else{
			                       alert('失败');
			                    }
			                },
			                error: function(msg){
			                    layer.msg('网络错误！！！');
			                }
			            });
					   
					   
			        });

	        });



		
	});
	</script>	