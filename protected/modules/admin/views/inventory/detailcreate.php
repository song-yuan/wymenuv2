	<!-- BEGIN PAGE -->  
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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>yii::t('app','进销存管理'),'subhead'=>yii::t('app','添加盘损单详情'),'breadcrumbs'=>array(array('word'=>yii::t('app','库存管理'),'url'=>$this->createUrl('bom/bom' , array('companyId'=>$this->companyId,'type'=>2,))),array('word'=>yii::t('app','盘损单管理'),'url'=>$this->createUrl('inventory/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','盘损单详情管理'),'url'=>$this->createUrl('inventory/detailindex' , array('companyId'=>$this->companyId,'lid' => $rlid))),array('word'=>yii::t('app','添加盘损单详情'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('inventory/detailindex' , array('companyId' => $this->companyId,'lid' => $rlid)))));?>
	
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><i class="fa fa-reorder"></i><?php echo yii::t('app','添加盘损单详情');?></div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>
						<div class="portlet-body form">
							<div class="form-horizontal form-body">
								<div class="form-group">
									<label class="col-md-3 control-label">盘损类型</label>
									<div class="col-md-2">
										<select class="form-control" id="selcatetype">
											<option value="1" >原料</option>
											<option value="2" >成品</option>
											<option value="0" >全部</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-md-3 control-label">按分类选择</label>
									<div class="col-md-4 mat">
										<?php echo CHtml::dropDownList('selectCategory', 0, $categories , array('class'=>'form-control'));?>
									</div>
									<div class="col-md-4 pro hide">
										<?php echo CHtml::dropDownList('selproCategory', 0, $cateps , array('class'=>'form-control'));?>
									</div>
								</div>
								<?php $form=$this->beginWidget('CActiveForm', array(
										'id' => 'material-form',
										'errorMessageCssClass' => 'help-block',
										'htmlOptions' => array(
											'class' => 'form-horizontal',
											'enctype' => 'multipart/form-data'
										),
								)); ?>
								<div class="form-group mat" id="table-manage">
									<label class="col-md-3 control-label">请勾选品项</label>
									<div class="col-md-9" id="checkm">
									<?php if($materials):?>
									<?php foreach ($materials as $m):?>
									<div id="mc" class="wid-33 flo-l mc" cate="<?php echo $m['category_id'];?>">
								         <div class="wid-10 flo-l">
								         	<input style="height:20px;" type="checkbox" catp="1" class="checkdpids" value="<?php echo $m['lid'];?>" name="mlist[]" id="m<?php echo $m['lid'];?>"/>
								         </div>
								         <div class="wid-70 flo-l"><label for="m<?php echo $m['lid'];?>"><?php echo $m['material_name'];?></label></div>
									</div>
									<?php endforeach;?>
									<div class="wid-10 flo-r checkal">
							         	<input style="height:20px;" type="checkbox" class="group-checkable" data-set="#checkm .checkdpids" id="checkall"/>
							         	<label for="checkall">全选</label>
							         </div>
									<?php endif;?>
									</div>
								</div>
								<div class="form-group pro hide" id="table-manage">
									<label class="col-md-3 control-label">请勾选产品</label>
									<div class="col-md-9" id="checkmp">
									<?php if($products):?>
									<?php foreach ($products as $m):?>
									<div id="mcp" class="wid-33 flo-l mcp" cate="<?php echo $m['category_id'];?>">
								         <div class="wid-10 flo-l">
								         	<input style="height:20px;" type="checkbox" catp="2" class="checkdpids" value="<?php echo $m['lid'];?>" name="mplist[]" id="mp<?php echo $m['lid'];?>"/>
								         </div>
								         <div class="wid-70 flo-l"><label for="mp<?php echo $m['lid'];?>"><?php echo $m['product_name'];?></label></div>
									</div>
									<?php endforeach;?>
									<div class="wid-10 flo-r checkalp">
							         	<input style="height:20px;" type="checkbox" class="group-checkable" data-set="#checkmp .checkdpids" id="checkallp"/>
							         	<label for="checkall">全选</label>
							         </div>
									<?php endif;?>
									</div>
								</div>
								<input type="hidden" id="ms" name="ms" />
								<div class="form-actions fluid">
									<div class="col-md-offset-3 col-md-9">
										<button type='button' class="btn blue add_save"><?php echo yii::t('app','确定');?></button>
									</div>
								</div>
								<?php $this->endWidget(); ?>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
<SCRIPT type="text/javascript">
	$('#selectCategory').change(function(){
		var id = $(this).val();
		if(id == 0){
			$('.mc').removeClass('hide');
			$('.checkal').removeClass('hide');
			}else{
				$('.checkal').addClass('hide');
			$('.mc').each(function(){
				var cate = $(this).attr('cate');
				if(cate == id){
					$(this).removeClass('hide');
				}else{
					$(this).addClass('hide');
				}
			});
		}
	})
		$('#selproCategory').change(function(){
		var id = $(this).val();
		if(id == 0){
			$('.mcp').removeClass('hide');
			$('.checkalp').removeClass('hide');
			}else{
				$('.checkalp').addClass('hide');
			$('.mcp').each(function(){
				var cate = $(this).attr('cate');
				if(cate == id){
					$(this).removeClass('hide');
				}else{
					$(this).addClass('hide');
				}
			});
		}
	})
	$('#selcatetype').change(function(){
		var tp = $(this).val();
		if(tp ==1){
			$('.pro').addClass('hide');
			$('.mat').removeClass('hide');
		}else if(tp ==2){
			$('.mat').addClass('hide');
			$('.pro').removeClass('hide');
		}else{
			$('.mat').removeClass('hide');
			$('.pro').removeClass('hide');
		}
	})
	$('.add_save').on('click',function(){
		var dpids =new Array();
        var dpids="";
        $('.checkdpids:checked').each(function(){
            dpids += $(this).val()+','+ $(this).attr('catp') +';';
        });
        if(dpids!=''){
        	dpids = dpids.substr(0,dpids.length-1);//除去最后一个“，”
        }
        //layer.msg(dpids);
        $('#ms').val(dpids);
        $('#material-form').submit();
        })
</SCRIPT>