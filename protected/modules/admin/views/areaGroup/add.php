<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/PCASClass.js');?>
<?php Yii::app()->clientScript->registerScriptFile( Yii::app()->request->baseUrl.'/js/address.js');?>
<style>
.radio-inline div{padding-top:0!important;}
.selectedclass{
	font-size: 14px;
	color: #333333;
	border-radius: 4px;
    padding: 3px 0px;
    outline: none !important;
    -webkit-box-shadow: none !important;
    -moz-box-shadow: none !important;
    box-shadow: none !important;
}
</style>
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
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','基础设置'),'url'=>$this->createUrl('product/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','地區分组'),'url'=>$this->createUrl('areaGroup/detailindex' , array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid,'type'=>$type))),array('word'=>yii::t('app','添加地區店铺'),'url'=>'')),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('areaGroup/detailindex' , array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid,'type'=>$type)))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="portlet box blue">
						<div class="portlet-title">
							<div class="caption"><?php if($type==1): echo '添加地区店铺';elseif($type==2): echo '添加地区仓库';endif; ?></div>
							<div class="actions">
								<div class="btn-group">
									<select id="province" name="province" class="selectedclass">
									</select>
									<select id="city" name="city" class="selectedclass">
									</select>
									<select id="area" name="area" class="selectedclass">
									</select>
				                </div>
								<div class="btn-group">
									<input type="search" name="cname" id="cname" value="<?php echo $cname; ?>" placeholder="店铺名,手机号,联系人" class="btn width2">
								</div>
								<div class="btn-group">
								    <span id="search" class="btn yellow" ><i class="fa fa-pencial"></i><?php echo yii::t('app','查 询');?></span>
								</div>
							</div>
						</div>
					</div>
						<div class="portlet-body form" style="margin-top:20px;">
							<!-- BEGIN FORM-->
							<?php $form=$this->beginWidget('CActiveForm', array(
							    'id'=>'area-group-add-form',
							    'action'=>$this->createUrl('areaGroup/add',array('companyId'=>$this->companyId,'type'=>$type,'areagroupid'=>$areagroupid)),
								'errorMessageCssClass' => 'help-block',
								'htmlOptions' => array(
									'class' => 'form-horizontal',
									'enctype' => 'multipart/form-data',
								),
							    'enableAjaxValidation'=>false,
							)); ?>
								<?php if($models): ?>
								<?php foreach($models as $model): ?>
							    <div class="col-md-4" style="margin-top:10px;border:1px solid gray;border-radius:3px;background: #abc;">
							        <input  type="checkbox" name="dpid[]" id="<?php echo $model['dpid']; ?>" value="<?php echo $model['dpid']; ?>"><label for="<?php echo $model['dpid']; ?>"><?php echo $model['company_name']; ?></label>
								</div>
								<?php endforeach; ?>
							    <div class="col-md-offset-3 col-md-9" style="margin-top:10px;">
									<?php echo CHtml::submitButton('确定',array('class' => 'btn blue')); ?>
							    	<a href="<?php echo $this->createUrl('priceGroup/index' , array('companyId' => $this->companyId));?>" class="btn default"><?php echo yii::t('app','返回');?></a>
							    </div>
								<?php else: ?>
									<h3 style="color:white;margin:0;border:1px solid gray;border-radius:3px;background: #abc;">请添加 <?php if($type==1): echo '店铺';elseif($type==2): echo '仓库';endif; ?></h3>
								<?php endif; ?>
							<?php $this->endWidget(); ?>
							<!-- END FORM-->
						</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->
		</div>
		<!-- END PAGE -->
	<script type="text/javascript">
		addressInit('province', 'city', 'area', '<?php echo $province;?>', '<?php echo $city;?>', '<?php echo $area;?>');
		$(document).keydown(function(event){
		  switch(event.keyCode){
		     case 13:return false; 
		     }
		});
		$('#province').change(function(){
			changeselect();
		});
		$('#city').change(function(){
			changeselect();
		});
		$('#area').change(function(){
			changeselect();
		});
		function changeselect(){
			var province = $('#province').children('option:selected').val();
			var city = $('#city').children('option:selected').val();
			var area = $('#area').children('option:selected').val();
			var type = "<?php echo $type; ?>";
			location.href="<?php echo $this->createUrl('areaGroup/add' , array('companyId'=>$this->companyId,'areagroupid'=>$areagroupid));?>/province/"+province+"/city/"+city+"/area/"+area+"/type/"+type;
		}
		$('#search').click(function(event) {
			/* Act on the event */
			var cname = $('#cname').val();
			location.href="<?php echo $this->createUrl('areaGroup/add' , array('companyId'=>$this->companyId,'type'=>$type,'areagroupid'=>$areagroupid));?>/cname/"+cname;
		});
		document.onkeydown=function(event){
	        var e = event || window.event || arguments.callee.caller.arguments[0];
	        if(e && e.keyCode==13){
	        // enter键
	        //要做的事情
			var cname = $('#cname').val();
			location.href="<?php echo $this->createUrl('areaGroup/add' , array('companyId'=>$this->companyId,'type'=>$type,'areagroupid'=>$areagroupid));?>/cname/"+cname;
	        }
	    };
	</script>