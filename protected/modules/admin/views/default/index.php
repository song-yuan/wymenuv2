<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/css/default.css'); ?>
<div class="page-content">
    
	<!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config" tabindex="-2" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
        <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->               
	<div class="modal fade" id="portlet-config3" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog modal-wide">
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
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<h3 class="page-title">
				<small></small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	
	<div class="row">
		<div class="col-md-12">
			
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
                                                <?php if($siteTypes):?>
						<?php foreach ($siteTypes as $key=>$siteType):?>
							<li typeId="<?php echo $key ;?>" class="tabtitle <?php if($key == $typeId) echo 'active';?>"><a href="#tab_1_<?php echo $key;?>" data-toggle="tab"><?php echo $siteType ;?></a></li>
						<?php endforeach;?>
                                                <?php endif;?>
                                                        <li typeId="tempsite" class="tabtitle <?php if($typeId == 'tempsite') echo 'active';?>"><a href="#tab_1_tempsite" data-toggle="tab"><?php echo yii::t('app','临时座/排队');?></a></li>
                                                        <li typeId="reserve" class="tabtitle <?php if($typeId == 'reserve') echo 'active';?>"><a href="#tab_1_reserve" data-toggle="tab"><?php echo yii::t('app','预定/外卖');?></a></li>
						</ul>
						<div class="tab-content" id="tabsiteindex">
							
							<!-- END EXAMPLE TABLE PORTLET-->												
						</div>
					</div>
				
			
		</div>	
		<div class="col-md-2 hide messagepart">
			<div class="portlet box purple">
				<div class="portlet-title"><i class="fa fa-volume-up"></i>
					<div class="caption pull-right"> <?php echo yii::t('app','历史消息>>');?></div>					
				</div>
                            
                                <div class="portlet-body message_list" id="messagepartid">
                                    
                                </div>
			</div>    
		</div>
	</div>
        
        <script type="text/javascript">
            var gssid=0;
            var gsistemp=0;
            var gstypeid=0;
            var gop=0;
            var tabcurrenturl="";
            $(document).ready(function() {
                $('body').addClass('page-sidebar-closed');
                tabcurrenturl='<?php echo $this->createUrl('defaultSite/showSite',array('typeId'=>$typeId,'companyId'=>$this->companyId));?>';
                $('#tabsiteindex').load(tabcurrenturl); 
                
            });            
            $('.tabtitle').on('click', function(){
                var typeId=$(this).attr('typeid');
                tabcurrenturl='<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId));?>'+'/typeId/'+typeId+'/sistemp/'+gsistemp+'/stypeId/'+gstypeid+'/ssid/'+gssid+'/op/'+gop;
                $('#tabsiteindex').load(tabcurrenturl); 
            });
            
	</script>
	<!-- END PAGE CONTENT-->
        