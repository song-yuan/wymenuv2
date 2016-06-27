<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<div id="responsive" class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','品项分类列表'),'breadcrumbs'=>array(array('word'=>yii::t('app','品项信息'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>1,))),array('word'=>yii::t('app','品项分类管理'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('bom/bom' , array('companyId' => $this->companyId,'type' =>'1',)))));?>

	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
	<?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'siteType-form',
				'action' => $this->createUrl('materialCategory/delete' , array('companyId' => $this->companyId)),
				'errorMessageCssClass' => 'help-block',
				'htmlOptions' => array(
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				),
		)); ?>
						<div class="col-md-12">
					<div class="portlet purple box">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-cogs"></i><?php echo yii::t('app','品项分类管理');?></div>
							<div class="actions">
								<a class="btn blue add_btn" pid="0" data-toggle="modal"><i class="fa fa-plus"></i> <?php echo yii::t('app','添加一级类目');?></a>
								<!-- <a href="<?php echo $this->createUrl('bom/bom' , array('companyId' => $this->companyId));?>" class="btn blue"> <?php echo yii::t('app','返回');?></a> -->
							</div>
						</div>
						<div class="portlet-body">
							<div class="table-responsive">
								<table class="tree table table-striped table-hover table-bordered dataTable">
									<?php foreach($models as $model):?>
									<tr class="treegrid-<?php echo $model->lid?> <?php if($model->pid!='0') echo 'treegrid-parent-'.$model->pid;?>">
										<td width="70%"><?php echo '('.$model->order_num.')'.$model->category_name;?></td>
										<td>
										<?php if($model->pid=='0'):?>
										<a class="btn btn-xs green add_btn" pid="<?php echo $model->lid;?>" data-toggle="modal"><i class="fa fa-plus"></i></a>
										<?php endif;?>
										<a class="btn btn-xs blue edit_btn" id="<?php echo $model->lid;?>" data-toggle="modal"><i class="fa fa-edit"></i></a>
										<a href="javascript:;" cid="<?php echo $model->lid;?>" class="btn btn-xs red btn_delete"><i class="fa fa-times"></i></a>
										</td>
									</tr>
									<?php endforeach;?>
								</table>
								<span style="color: red;"><?php echo yii::t('app','注意');?>：<br>
								<?php echo yii::t('app','*:必须设置二级分类');?><br>
								<?php echo yii::t('app','*:分类之间名称不能重复');?><br></span>
								<?php echo yii::t('app','说明');?>：<br>
								<?php echo yii::t('app','1:（）内数字是各个分类的产品在前台的显示顺序，数值越大的排在最前面。');?><br>
								<?php echo yii::t('app','2:所有数字小于9999');?><br>
								<?php echo yii::t('app','3:一级分类排好顺序后，不同一级分类内的二级分类排序数值不要交叉。');?>
								<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo yii::t('app','如：三个一级分类显示顺序是1、2、3，');?>
								<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo yii::t('app','那么显示顺序是2的一级分类所对应的二级分类的显示顺序数值不能比1内的二级分类显示顺序小，不能比3内的二级分类显示顺序大。');?>
							</div>
						</div>
					</div>
				</div>

		<?php $this->endWidget(); ?>
	<!-- END PAGE CONTENT-->
	<script type="text/javascript">
    $(document).ready(function() {
        $('.tree').treegrid({'initialState':'collapsed'});
        <?php foreach($expandNode as $node):?>
        $('.treegrid-<?php echo substr('0000000000'.$node,-10,10);?>').treegrid('expand');
        <?php endforeach;?>
	});
    var $modal = $('.modal');
    $('.add_btn').on('click', function(){
    	pid = $(this).attr('pid');
        $modal.find('.modal-content').load('<?php echo $this->createUrl('materialCategory/create',array('companyId'=>$this->companyId));?>/pid/'+pid, '', function(){
          $modal.modal();
        });
    });
    $('.edit_btn').on('click', function(){
    	id = $(this).attr('id');
        $modal.find('.modal-content').load('<?php echo $this->createUrl('materialCategory/update',array('companyId'=>$this->companyId));?>/id/'+id, '', function(){
          $modal.modal();
        });
    });
    $('.btn_delete').click(function(){
    	var cid = $(this).attr('cid');
        msg ="<?php echo yii::t('app','你确定要删除该类目吗?');?>";
        if($(this).parent().parent().hasClass('treegrid-collapsed') || $(this).parent().parent().hasClass('treegrid-expanded')){
        	msg += "<?php echo yii::t('app','<br/>该类目的子类目将会一起被删除！');?>";
        }
        bootbox.confirm(msg, function(result) {
           if(result){
               location.href="<?php echo $this->createUrl('materialCategory/delete',array('companyId'=>$this->companyId));?>/id/"+cid;
           }
        });
    });
	</script>