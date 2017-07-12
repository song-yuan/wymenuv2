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
			<?php 
				if ($kind==0) {
					$this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','套餐列表'),'url'=>$this->createUrl('productSet/index' , array('companyId'=>$this->companyId,))),array('word'=>yii::t('app','套餐明细列表'),'url'=>$this->createUrl('productSet/detailindex' , array('companyId' => $this->companyId,'lid' => $psid ,'status'=>$status,'papage'=>$papage))),array('word'=>yii::t('app','添加套餐明细'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('productSet/detailindex' , array('companyId' => $this->companyId,'lid' => $psid ,'status'=>$status,'papage'=>$papage)))));
				}elseif ($kind==1) {
					$this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','套餐列表'),'url'=>$this->createUrl('productSet/index' , array('companyId'=>$this->companyId,))),array('word'=>yii::t('app','套餐明细列表'),'url'=>$this->createUrl('productSet/detailindex' , array('companyId' => $this->companyId,'lid' => $psid ,'status'=>$status,'papage'=>$papage))),array('word'=>yii::t('app','添加产品组合'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('productSet/detailindex' , array('companyId' => $this->companyId,'lid' => $psid ,'status'=>$status,'papage'=>$papage)))));
				}
			?>
			
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption">
								<a href="<?php echo $this->createUrl('productSet/detailcreate',array('companyId'=>$this->companyId,'psid'=>$psid,'type'=>0,'papage'=>$papage,'kind'=>0));?>">
								<span class="tab <?php if($kind==0){ echo 'tab-active';}?>" style="<?php if($kind==0){ echo 'color:white!important;';}else{ echo 'color:orange!important;';}?>" ><?php echo yii::t('app','添加套餐明细');?></span>
								</a>
								<a href="<?php echo $this->createUrl('productSet/detailcreate',array('companyId'=>$this->companyId,'psid'=>$psid,'type'=>0,'papage'=>$papage,'kind'=>1,));?>">
								<span class="tab <?php if($kind==1){ echo 'tab-active';}?>"  style="<?php if($kind==1){ echo 'color:white!important;';}else{ echo 'color:orange!important;';}?>" ><?php echo yii::t('app',' 添加产品组合');?></span>
								</a>
							</div>
							<div class="tools">
								<a href="javascript:;" class="collapse"></a>
							</div>
						</div>

						<div class="portlet-body form">
							<!-- BEGIN FORM-->
							<?php 
								if ($kind==0) {
									echo $this->renderPartial('_detailform', array('model'=>$model, 'categories' => $categories,'categoryId'=>$categoryId,'products'=>$products,'maxgroupno'=>$maxgroupno,'groups'=>$groups,'type'=>$type,'status'=>$status, 'papage'=>$papage)); 
								}elseif($kind==1){
									echo $this->renderPartial('_detailform1', array('model'=>$model, 'categories' => $categories,'categoryId'=>$categoryId,'products'=>$products,'maxgroupno'=>$maxgroupno,'groups'=>$groups,'type'=>$type,'psid'=>$psid,'status'=>$status, 'papage'=>$papage,'pgroups'=>$pgroups)); 
								}

								?>
							<!-- END FORM--> 
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  