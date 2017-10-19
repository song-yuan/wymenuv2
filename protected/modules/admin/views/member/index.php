
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','实体卡'),'url'=>$this->createUrl('entityCard/list' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','添加会员'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('entityCard/list' , array('companyId' => $this->companyId)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->

	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN EXAMPLE TABLE PORTLET-->
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','会员列表');?></div>
					<div class="actions">
					<?php if(Yii::app()->user->role<9):?>

						<button class="btn yellow" id="inExcel2"><i class="fa fa-pencil"></i> <?php echo yii::t('app','导入旧Excel文件');?></button>
						<button class="btn yellow" id="inExcel"><i class="fa fa-pencil"></i> <?php echo yii::t('app','导入Excel文件');?></button>
						<a href="<?php echo $this->createUrl('member/indexExport' , array('companyId' => $this->companyId));?>" class="btn green"><i class="fa fa-pencil"></i> <?php echo yii::t('app','导出Excel模版');?></a>
						<a href="<?php echo $this->createUrl('member/create' , array('companyId' => $this->companyId));?>" class="btn blue"><i class="fa fa-pencil"></i> <?php echo yii::t('app','添 加');?></a>
					<?php endif;?>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
						<table class="table table-striped table-bordered table-hover" id="sample_1">
							<thead>
								<tr>
									<th><?php echo yii::t('app','会员卡号');?></th>
									<th><?php echo yii::t('app','姓名');?></th>
									<th><?php echo yii::t('app','性别');?></th>
									<th><?php echo yii::t('app','生日');?></th>
									<th><?php echo yii::t('app','联系方式');?></th>
									<th><?php echo yii::t('app','金额');?></th>
									<th><?php echo yii::t('app','积分');?></th>
									<th><?php echo yii::t('app','状态');?></th>
									<th><?php echo yii::t('app','折扣（生日折扣）');?></th>
	
									<th>&nbsp;</th>
								</tr>
							</thead>
							<tbody>
							<?php if($models):?>
							<?php foreach ($models as $model):?>
								<tr class="odd gradeX">
									<td ><?php echo $model->selfcode;?></td>
									<td ><?php echo $model->name;?></td>
									<td ><?php if($model->sex=='m') echo '男';else echo '女';?></td>
									<td ><?php echo $model->birthday;?></td>
									<td ><?php echo $model->mobile;?></td>
									<td ><?php echo $model->all_money;?></td>
									<td ><?php echo $model->all_points;?></td>
									<td ><?php switch($model->card_status){case 0:echo '正常';break;case 1: echo "挂失";break;case 2: echo '注销';break;default:echo '';break;}?></td>
									<td ><?php echo sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->level_discount:'1').'('.sprintf("%.2f",$model->brandUserLevel?$model->brandUserLevel->birthday_discount:'1').')';?></td>
	
									<td class="center">
	<!--									<a href="<?php echo $this->createUrl('member/chargeRecord',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','充值记录');?></a>&nbsp;
										<a href="<?php echo $this->createUrl('member/consumersRecord',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','消费记录');?></a>&nbsp;
										<a href="<?php echo $this->createUrl('member/pointsRecord',array('rfid' => $model->rfid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','积分记录');?></a>&nbsp;-->
										<?php if(Yii::app()->user->role <= User::SHOPKEEPER):?>
										<a href="<?php echo $this->createUrl('member/update',array('lid' => $model->lid , 'companyId' => $model->dpid));?>"><?php echo yii::t('app','编辑');?></a>&nbsp;
	                                    <?php endif;?>
	                                    <!-- <a class="deletememberid" data-id="<?php echo $model->lid;?>" href="javascript:;"><?php echo yii::t('app','删除');?></a> -->
									</td>
								</tr>
							<?php endforeach;?>
							<?php else:?>
							<td colspan="10">没有找到数据</td>
							<?php endif;?>
							</tbody>
						</table>
						</div>
						<?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
							</div>
							<div class="col-md-7 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap">
								<?php $this->widget('CLinkPager', array(
									'pages' => $pages,
									'header'=>'',
									'firstPageLabel' => '<<',
									'lastPageLabel' => '>>',
									'firstPageCssClass' => '',
									'lastPageCssClass' => '',
									'maxButtonCount' => 8,
									'nextPageCssClass' => '',
									'previousPageCssClass' => '',
									'prevPageLabel' => '<',
									'nextPageLabel' => '>',
									'selectedPageCssClass' => 'active',
									'internalPageCssClass' => '',
									'hiddenPageCssClass' => 'disabled',
									'htmlOptions'=>array('class'=>'pagination')
								));
								?>
								</div>
							</div>
						</div>
						<?php endif;?>
					</div>
				</div>
			</div>
			<!-- END EXAMPLE TABLE PORTLET-->
		</div>
	</div>
	<!-- END PAGE CONTENT-->

<div id="main2" name="main2" style="min-width: 500px;min-height:300px;background: white;display:none;">
<div id="content">
	<div class="form-body">
		<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'excel-form',
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
		<div class="form-group ">
			<div class="col-md-9">
				<div class="fileupload fileupload-new" data-provides="fileupload">
					<div class="fileupload-preview fileupload-exists thumbnail" style="max-width: 200px; max-height: 100px; line-height: 20px;"></div>
					<div>
						<span class="btn default btn-file">
						<span class="fileupload-new"><i class="fa fa-paper-clip"></i> 点击选择上传的Excel文件 </span>
						<span class="fileupload-exists"><i class="fa fa-undo"></i> 更改 </span>
						<input type="file" accept="application/vnd.ms-excel" name="file" class="default" />
						</span>
						<a href="#" class="btn red fileupload-exists" data-dismiss="fileupload"><i class="fa fa-trash-o"></i> 移除 </a>
					</div>
				</div>
				<span class="label label-danger">注意:</span>
				<span>大小：建议不超过2M 格式:.xls </span>
			</div>
		</div>
		<div class="form-actions fluid">
			<div class="col-md-offset-3 col-md-9">
				<button type="button" id="su" class="btn blue"><?php echo yii::t('app','确定');?></button>
				<button type="button" class="btn layui-layer-close layui-layer-close2" style="margin-left:3em;"><?php echo yii::t('app','关闭');?></button>
			</div>
		</div>
		<?php $this->endWidget(); ?>

		</div>
	</div>
</div>
	<script>
	jQuery(document).ready(function(){

	  // $('input[name="file"]').change(function(){
		 //  	$('form').ajaxSubmit(function(msg){
		 //  		alert(msg);
			// 	$('#Product_main_picture').val(msg);
			// });
	  //  });

		$("#su").on('click',function(){
			$("#excel-form").submit();
		});
        var $modal = $('.modal');
        $('.add_btn').on('click', function(){

		    	pid = $(this).attr('pid');
		        $modal.find('.modal-content').load('<?php echo $this->createUrl('member/charge' , array('companyId' => $this->companyId));?>', '', function(){
		          $modal.modal();
		        });
	            });
		});
        $(".deletememberid").on("click",function(){
               var id = $(this).attr('data-id');
               msg ='确定要删除该会员吗?';
	       	   bootbox.confirm(msg, function(result) {
                   if(result){
                       location.href="<?php echo $this->createUrl('member/delete',array('companyId' => $this->companyId));?>/id/"+id;
                   }
                });
        });

		$('#inExcel').on('click',function(){
			$('#excel-form').attr({
				action:"<?php echo $this->createUrl('member/indexInput',array('companyId'=>$this->companyId));?>"
			});
			var heightP =($(window).outerHeight()/3)+'px';
			var widthP =($(window).outerWidth()/3)+'px';
			// alert(heightP);
			// alert(widthP);
			layer_zhexiantu=layer.open({
			     type: 1,
			     //shift:5,
			     shade: [0.5,'#fff'],
			     //move:'#main2',
			     moveOut:true,
			     offset:[heightP,widthP],
			     shade: false,
			     title: false, //不显示标题
			     area: ['auto', 'auto'],
			     content: $('#main2'),//$('#productInfo'), //捕获的元素
			     cancel: function(index){
			         layer.close(index);
			         layer_zhexiantu=0;
			     }
			 });
		

			layer.style(layer_zhexiantu, {
				backgroundColor: 'rgba(255,255,255,0.2)',
			});
		function swfupload_callback(name,path,oldname)  {
			$("#Product_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	});
		$('#inExcel2').on('click',function(){
			$('#excel-form').attr({
				action:"<?php echo $this->createUrl('member/indexInput2',array('companyId'=>$this->companyId));?>"
			});
			var heightP =($(window).outerHeight()/3)+'px';
			var widthP =($(window).outerWidth()/3)+'px';
			// alert(heightP);
			// alert(widthP);
			layer_zhexiantu=layer.open({
			     type: 1,
			     //shift:5,
			     shade: [0.5,'#fff'],
			     //move:'#main2',
			     moveOut:true,
			     offset:[heightP,widthP],
			     shade: false,
			     title: false, //不显示标题
			     area: ['auto', 'auto'],
			     content: $('#main2'),//$('#productInfo'), //捕获的元素
			     cancel: function(index){
			         layer.close(index);
			         layer_zhexiantu=0;
			     }
			 });
		

			layer.style(layer_zhexiantu, {
				backgroundColor: 'rgba(255,255,255,0.2)',
			});
		function swfupload_callback(name,path,oldname)  {
			$("#Product_main_picture").val(name);
			$("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
	});
	</script>