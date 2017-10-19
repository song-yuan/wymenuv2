<style>
	.modal-dialog{
		width: 1024px;
		height: 80%;
	}
</style>
<div class="page-content">
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
					<h4 class="modal-title">未找到连接</h4>
				</div>
				<div class="modal-body">
					Widget settings form goes here
				</div>
				<div class="modal-footer">
					<button type="button" class="btn default" data-dismiss="modal">关闭</button>
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
	<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','原料信息'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>4,))),array('word'=>yii::t('app','配方列表'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('product/list' , array('companyId' => $this->companyId,'type' =>'4',)))));?>
	
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	<div class="row">
            <?php $form=$this->beginWidget('CActiveForm', array(
				'id' => 'product-form',
				'action' => $this->createUrl('productSet/delete' , array('companyId' => $this->companyId)),
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
					<div class="caption"><i class="fa fa-globe"></i><?php echo yii::t('app','配方列表');?></div>
					<div class="actions">
						<div class="btn-group">
							<?php echo CHtml::dropDownList('selectCategory', $categoryId, $categories , array('class'=>'form-control'));?>
						</div>
					</div>
				</div>
				<div class="portlet-body" id="table-manage">
				<div class="dataTables_wrapper form-inline">
					<div class="table-responsive">
					<table class="table table-striped table-bordered table-hover" id="sample_1">
						<thead>
							<tr>
								<th><?php echo yii::t('app','产品名称');?></th>
								<th><?php echo yii::t('app','配方');?></th>
  								<th>&nbsp;</th>
  								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($models as $model):?>
							<tr class="odd gradeX">
								<td ><?php echo $model->product_name ;?></td>
								<td >
								<?php if($model->productbom):?>
									<?php foreach ($model->productbom as $pbs):?>
									<?php echo ProductBom::getProductMaterialName($pbs->material_id,$model->dpid);?>
									<?php endforeach;?>
								<?php endif;?>
								</td>
								<td style="width:10%" class="add_btn" pid="<?php echo $model->lid;?>" compid="<?php echo $model->dpid;?>" prodname="<?php echo $model->product_name;?>" phscode="<?php echo $model->phs_code;?>" data-toggle="modal"> <a href="" ><?php echo yii::t('app','批量添加配方');?></a></td>
                                <td style="width:10%" class="center">
								<a href="<?php echo $this->createUrl('productBom/detailindex',array('pblid' => $model->lid , 'companyId' => $model->dpid , 'prodname'=>$model->product_name,'papage'=>$pages->getCurrentPage()+1));?>"><?php echo yii::t('app','编辑修改配方');?></a>
								</td>
							</tr>
						<?php endforeach;?>
						</tbody>
					</table>
					</div>
					<?php if($pages->getItemCount()):?>
					<div class="row">
						<div class="col-md-5 col-sm-12">
							<div class="dataTables_info">
								<?php echo yii::t('app','共 ');?><?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?> <?php echo $pages->getCurrentPage()+1;?><?php echo yii::t('app','页');?>
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
								'htmlOptions'=>array('class'=>'pagination pull-right')
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
            <?php $this->endWidget(); ?>
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
		$('#selectCategory').change(function(){
			var cid = $(this).val();
			location.href="<?php echo $this->createUrl('productBom/index' , array('companyId'=>$this->companyId));?>/cid/"+cid;
		});
	});
	var $modal = $('.modal');
    $('.add_btn').on('click', function(){
    	pid = $(this).attr('pid');
    	compid = $(this).attr('compid');
    	prodname = $(this).attr('prodname');
    	phscode = $(this).attr('phscode');
        $modal.find('.modal-content').load('<?php echo $this->createUrl('productBom/create',array('companyId'=>$this->companyId));?>/pid/'+pid+'/prodname/'+prodname+'/phscode/'+phscode, '', function(){
          $modal.modal();
        });
    });
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
        
		if(window.confirm("确认进行此项操作?配方下发之前请先确认是否下发相应菜品及原料，否则，配方下发会出现不可预知的错误！！！")){
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
	            	$("#chscode").val(codec);
	            	$("#phscode").val(codep);
	    	        //$("#copyproduct-form").submit();
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