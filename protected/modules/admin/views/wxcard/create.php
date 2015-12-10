		<?php 
			$baseUrl = Yii::app()->baseUrl;
		?>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/bootstrap-fileupload/bootstrap-fileupload.css" />
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/css/wxcard.css" />
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script>
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/bootstrap-datepicker/js/locales/bootstrap-datepicker.zh-CN.js"></script>
		<link rel="stylesheet" type="text/css" href="<?php echo $baseUrl;?>/plugins/jquery-multi-select/css/multi-select.css" />
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/jquery-multi-select/js/jquery.multi-select.js"></script>
		<script type="text/javascript" src="<?php echo $baseUrl;?>/plugins/jquery-multi-select/js/jquery.quicksearch.js"></script>   
		
		<link href="<?php echo $baseUrl;?>/plugins/bootstrap-modal/css/bootstrap-modal.css" rel="stylesheet" type="text/css"/>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modalmanager.js" type="text/javascript" ></script>
		<script src="<?php echo $baseUrl;?>/plugins/bootstrap-modal/js/bootstrap-modal.js" type="text/javascript" ></script>
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
			<!-- BEGIN STYLE CUSTOMIZER -->
			<?php $this->beginContent('//layouts/admin/styleCustomizer');?>
			<?php $this->endContent();?>
			<!-- END BEGIN STYLE CUSTOMIZER -->            
			<!-- BEGIN PAGE HEADER-->   
			<?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('head'=>'营销管理','subhead'=>'添加卡券','breadcrumbs'=>array(array('word'=>'营销管理','url'=>''),array('word'=>'微信卡券','url'=>$this->createUrl('/brand/wxcard',array('cid'=>$this->companyId))),array('word'=>'添加卡券','url'=>'')),'back'=>array('word'=>'返回','url'=>array('/admin/wxcard/index','companyId'=>$this->companyId))));?>
			<!-- END PAGE HEADER-->
			<!-- BEGIN PAGE CONTENT-->
			<div class="row">
				<div class="col-md-12">
					<div class="tabbable tabbable-custom boxless">
						<div class="portlet box purple">
							<div class="portlet-title">
								<div class="caption"><i class="fa fa-gift"></i>添加卡券</div>
							</div>
							<div class="portlet-body form">
								<!-- BEGIN FORM-->
								<?php echo $this->renderPartial('_form', array('model'=>$model,'colors'=>$colors,'type'=>$type)); ?>
								<!-- END FORM--> 
								<input type="hidden" name="type" value="<?php echo $type;?>" />
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- END PAGE CONTENT-->    
		</div>
		<!-- END PAGE -->  
		<div id="responsive" class="modal fade" tabindex="-1" aria-hidden="true">
			<div id="ajax-modal" class="modal fade" tabindex="-1"  style="width:600px;">
			</div>
			<div class="modal-dialog">
				<div class="modal-content">

				</div>
			</div>
		</div>
		<script>
		function formatDate(now) { 
			var year=now.getFullYear(); 
			var month=now.getMonth()+1; 
			var date=now.getDate(); 
			return year+"."+month+"."+date; 
		} 
		function dateStr() { 
			var date = new Date();
			var timeStamp = date.getTime();
			var fixedBeginTerm = $('select[name="fixed_begin_term"]').val();
			var fixedTerm = $('select[name="fixed_term"]').val();
			
			var beginTime = parseInt(timeStamp) + parseInt(fixedBeginTerm)*24*3600*1000;
			var endTime = beginTime + parseInt(fixedTerm)*24*3600*1000;
			
			var beginDate = new Date(parseInt(beginTime));
			var endDate = new Date(parseInt(endTime));
			
			var timeStr = '有效期 : '+ formatDate(beginDate) +' - '+ formatDate(endDate);
			$('#js_validtime_preview').html(timeStr); 
		} 
		jQuery(document).ready(function() {       
		   // initiate layout and plugins
			jQuery(document).ready(function(){
				if( jQuery("#Gift_gift_pic_large").val()){
			           jQuery("#thumbnails_1").html("<img src='"+jQuery("#Gift_gift_pic_large").val()+"?"+(new Date()).getTime()+"' />"); 
				}
			});
	        if (jQuery().datepicker) {
	            $('.date-picker').datepicker({
	            	format: 'yyyy.mm.dd',
	            	language: 'zh-CN',
	                rtl: App.isRTL(),
	                autoclose: true
	            });
	            $('body').removeClass("modal-open"); // fix bug when inline picker is used in modal
	        }
	        window.onload = function(){$('.edit_oper').eq(0).trigger('click');};
	        $('.edit_oper').click(function(){
	        	$('.js_edit_content').css('display','none');
	        	var actionId = $(this).attr('data-actionid');
	        	if(parseInt(actionId)==9){
	        		$('.js_edit_content').eq(0).css('display','block');
	        		$('#js_edit_area').css('margin-top','0px');
	        	}else if(parseInt(actionId)==10){
	        		$('.js_edit_content').eq(1).css('display','block');
	        		$('#js_edit_area').css('margin-top','126px');
	        	}else if(parseInt(actionId)==11){
	        		$('.js_edit_content').eq(2).css('display','block');
	        		$('#js_edit_area').css('margin-top','199px');
	        	}else if(parseInt(actionId)==12){
	        		$('.js_edit_content').eq(3).css('display','block');
	        		$('#js_edit_area').css('margin-top','244px');
	        	}
	        });
			//公司名
			$('input[name="brand_name"]').on('keyup change',function(){
				var brandName = $(this).val();
				$('#js_brand_name_preview').html(brandName);
			});
			//券标题
			$('input[name="title"]').on('keyup change',function(){
				var title = $(this).val();
				$('#js_title_preview').html(title);
			});
			//券副标题
			$('input[name="sub_title"]').on('keyup change',function(){
				var subTitle = $(this).val();
				$('#js_sub_title_preview').html(subTitle);
			});
			//有效期
			$('input[name="begin_timestamp"]').change(function(){
				var beginTime = $('input[name="begin_timestamp"]').val();
				var endTime = $('input[name="end_timestamp"]').val();
				var timeStr = '有效期 : '+ beginTime +' - '+ endTime;
				$('#js_validtime_preview').html(timeStr);
			});
			$('input[name="end_timestamp"]').change(function(){
				var beginTime = $('input[name="begin_timestamp"]').val();
				var endTime = $('input[name="end_timestamp"]').val();
				var timeStr = '有效期 : '+ beginTime +' - '+ endTime;
				$('#js_validtime_preview').html(timeStr);
			});
			
			$('select[name="fixed_begin_term"]').change(function(){
				dateStr();
			});
			
			$('select[name="fixed_term"]').change(function(){
				dateStr();
			});
			
			//选择门店
			$('input[name="js_shop_type"]').change(function(){
				var type = $(this).val();
				if(parseInt(type)!=1){
					$('#js_fix_shop').css('display','none');
				}else{
					$('#js_fix_shop').css('display','block');
				}
			});
			var $modal = $('#ajax-modal');
			$('#js_add_shop').click(function(){
		        $modal.load('<?php echo $this->createUrl('/admin/wxcard/addShop',array('companyId'=>$this->companyId));?>', '', function(){
	              $modal.modal();
		        });
			});
			
			$('#ajax-modal').on('click','#add_shop_btn',function(){
				var checked = 0;
				var set = $(this).parents('#ajax-modal').find('#sample_3 #select_all').attr("data-set");
				var tr = '';
				$(set).each(function () {
					if($(this).is(":checked")){
						checked = 1;
						var td = '';
						$(this).parents('td').siblings().each(function(){
							td += $(this).prop("outerHTML");
						});
						tr +='<tr>'+td+'<input type="hidden" name="shopIds[]" value="'+$(this).val()+'" /></tr>';
					}
				});
				if(!checked){
					alert('请选择门店!');
					return;
				}
				$('#cancel_shop_btn').trigger('click');
				$('#js_shop_table').css('display','block');
				$('#js_fix_shop').find('tbody').html(tr);
			});
			//上传图片
			$('.cover').change(function(){
				var url = '<?php echo $this->createUrl('/admin/wxcard/uploadfile',array('companyId'=>$this->companyId));?>';
				$('#WeixinCard').attr('action',url);
				$('#WeixinCard').ajaxSubmit(function(msg){
				  if(msg==0){
				  	 alert('上传图片格式不正确!');
				  }else{
					var url = '<?php echo $this->createUrl('/admin/wxcard/create',array('companyId'=>$this->companyId,'type'=>$type));?>';
					$('#WeixinCard').attr('action',url);
					var hide = $('.cover').parents('.form-group').find('input[type=hidden]');
					hide.attr('name','logo');
					hide.val(msg);
					$('form').removeAttr('target');
					$('#js_logo_url_preview').attr('src',<?php echo $baseUrl.'/';?>msg);
				  }
				});
			});
			//颜色
			$('select[name="color"]').change(function(){
				var backColor = $('select[name="color"] option:selected').css('background-color');
				$('input[name="color_val"]').val(backColor);
				$(this).css('background-color',backColor);
				$('#js_color_preview').css('background-color',backColor);
			});
			
			$('input[name="date_info_type"]').change(function(){
				if(parseInt($(this).val())==1){
					$('select[name="fixed_begin_term"]').attr('disabled','disabled');
					$('select[name="fixed_term"]').attr('disabled','disabled');
					$('input[name="begin_timestamp"]').removeAttr('disabled');
					$('input[name="end_timestamp"]').removeAttr('disabled');
					var beginTime = $('input[name="begin_timestamp"]').val();
					var endTime = $('input[name="end_timestamp"]').val();
					var timeStr = '有效期 : '+ beginTime +' - '+ endTime;
					$('#js_validtime_preview').html(timeStr);
				}else if(parseInt($(this).val())==2){
					$('input[name="begin_timestamp"]').attr('disabled','disabled');
					$('input[name="end_timestamp"]').attr('disabled','disabled');
					$('select[name="fixed_begin_term"]').removeAttr('disabled');
					$('select[name="fixed_term"]').removeAttr('disabled');
					dateStr();
				}
			});
			$('form').submit(function(){
				
				var type = $('input[name="type"]').val();
				var brandName = $('input[name="brand_name"]').val();
				if(!brandName){
					alert('请填写商家名称!');
					return false;
				}
				var title = $('input[name="title"]').val();
				if(!title){
					alert('请填写标题!');
					return false;
				}
				var title = $('input[name="title"]').val();
				if(!title){
					alert('请填写标题!');
					return false;
				}
				var quantity = $('input[name="quantity"]').val();
				if(!quantity){
					alert('请填写库存!');
					return false;
				}
				var notice = $('input[name="notice"]').val();
				if(!notice){
					alert('请填写操作提示!');
					return false;
				}
				var description = $('textarea[name="description"]').val();
				if(!description){
					alert('请填写使用须知!');
					return false;
				}
				var shopType = $('input[name="js_shop_type"]:checked').val();
				if(parseInt(shopType)==1){
					var length = $('#js_shop_table').find('tr').length;
					if(parseInt(length)==1){
						alert('请添加门店!');
						return false;
					}
				}
				if(parseInt(type)){
					var default_detail = $('textarea[name="default_detail"]').val();
					if(!default_detail){
						alert('请填写优惠详情!');
						return false;
					}
				}else{
					var reduceCost = $('input[name="reduce_cost"]').val();
					if(!reduceCost){
						alert('请填写减免金额!');
						return false;
					}
					if(isNaN(reduceCost)){
						alert('减免金额应该为数字!');
						return false;
					}
				}
				return true;
			});
		});
		function swfupload_callback1(name,path,oldname)  {
			jQuery("#Gift_gift_pic_large").val(name);
			jQuery("#thumbnails_1").html("<img src='"+name+"?"+(new Date()).getTime()+"' />"); 
		}
		</script>