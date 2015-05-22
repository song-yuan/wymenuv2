<div class="page-content">
	
	<!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM-->
	<!-- BEGIN PAGE HEADER-->
	<div class="row">
		<div class="col-md-12">
			<!-- BEGIN PAGE TITLE & BREADCRUMB-->			
			<h3 class="page-title">
				<small>订单编号错误！！</small>
			</h3>
			<!-- END PAGE TITLE & BREADCRUMB-->
		</div>
	</div>
	<!-- END PAGE HEADER-->
	<!-- BEGIN PAGE CONTENT-->
	
	<div class="row">
		<div class="col-md-12">			
                    <div class="">
                        <div class="tabbable tabbable-custom">
                            <input type="button" class="btn green" id="errorback" value=" 返 回 ">
                        </div>
                    </div>	
		</div>	
		
	</div>
</div>
        <script type="text/javascript">
            
            $(document).ready(function() {
                $('body').addClass('page-sidebar-closed');
                //set time out
            });            
            $('#errorback').on('click', function(){
                location.href='<?php echo $backurl;?>'; 
            });
            
	</script>
	<!-- END PAGE CONTENT-->
        