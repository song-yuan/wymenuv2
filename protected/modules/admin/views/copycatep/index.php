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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','菜品设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','分类下发'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type'=>0)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'copycatep-form',
				'action' => $this->createUrl('copycatep/catep' , array('companyId' => $this->companyId)),
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
							<button type="button" id="su"  class="btn red form-control" ><i class="fa fa-share-square-o "></i> <?php echo yii::t('app','分类下发');?></button>
 						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th style="width:10%" class="table-checkbox"><input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes" /><?php echo yii::t('app','全选');?></th>
								<th style="width:25%"><?php echo yii::t('app','店铺名称');?></th>
								<th style="width:25%"><?php echo yii::t('app','店铺地址');?></th>
								<th style="width:25%"><?php echo yii::t('app','联系人');?></th>
							</tr>
						</thead>
						<tbody>

						<!-- var_dump($dpids);exit; -->
						<?php if($dpids) :?>
						<?php foreach ($dpids as $dpid):?>
							<tr class="odd gradeX">
								<td><input id="<?php echo $dpid['dpid'];?>" type="checkbox" class="checkboxes" value="<?php echo $dpid['dpid'];?>" name="ids[]" />
								</td>
								<td style="width:25%"><?php echo $dpid['company_name'];?></td>
								<td style="width:25%"><?php echo $dpid['contact_name'];?></td>
								<td style="width:25%"><?php echo $dpid['mobile'];?></td>

							</tr>
						<?php endforeach;?>
						<?php endif;?>
						</tbody>
						<div style="display: none;">
						<input type="hidden" id="phscode" name="phscode" value="" />
						<input type="hidden" id="chscode" name="chscode" value="" />
						<input type="hidden" id="dpids" name="dpids" value="" />
						<input type="hidden" id="pgroups" name="groups" value="" />
						</div>
					</table>


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
			        请勿关闭网页,正在跑步下发分类,让子弹飞一会儿......
		         </div>
	         </div>
	         <div class="modal-footer">
		         <button id="closeall" type="button" class="btn default" data-dismiss="modal">关闭</button>
	         </div>
		 </div>
	</div>



	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
	$(document).ready(function(){

	});


	$("#su").on('click',function() {
		var aa = document.getElementsByName("ids[]");
		var ids=new Array();
        for (var i = 0; i < aa.length; i++) {
            if (aa[i].checked) {
                ids += aa[i].getAttribute("value") +',';
            }
        }
		// alert(ids);
        if(ids==''){
       	 	alert("<?php echo yii::t('app','请选择店铺进行<<--分类-->>下发！！！');?>");
       		return false;
        }else{
       		$("#copycatep-form").submit();
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
       	}
	});

	</script>