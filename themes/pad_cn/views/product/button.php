
			
			<div id="client_open_site">				
                                <label class="col-md-3 control-label"><?php echo yii::t('app','请输入人数：');?></label>
                                <div class="col-md-4">
                                    <input class="form-control" placeholder="<?php echo yii::t('app','请输入人数');?>" name="siteNumber" id="site_number" type="text" maxlength="2" value="1">
                                </div>
                                <div class="pull-right">
                                    <button id="site_open" type="button" istemp="<?php echo $istemp; ?>" sid="<?php echo $sid; ?>" class="btn green"><?php echo yii::t('app','开 台');?></button>
                                    
                                </div>
			</div>
			
                        <script type="text/javascript">
                            $(document).ready(function() {
                                var sno=$("#site_number");
                                if(sno.length > 0)
                                {
                                    sno[0].focus();
                                }
                            });
                            
                            
                           $('#site_open').on(event_clicktouchstart,function(){
                               var siteNumber=$('#site_number').val();                               
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               if(!isNaN(siteNumber) && siteNumber>0 && siteNumber < 99)
                               {
                                    $.ajax({
										'type':'POST',
										'dataType':'json',
										'data':{"sid":sid,"siteNumber":siteNumber,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
										'url':'<?php echo $this->createUrl('defaultSite/opensite',array());?>',
										'success':function(data){
											if(data.status == 0) {
												alert(data.message);
											} else {
												alert(data.message);
					                            $('#portlet-button').modal('hide');
												$('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>');
											}
										},
                                        'error':function(e){
                                            return false;
                                        }
                                    });
                                    
                               }else{
                                   alert("<?php echo yii::t('app','输入合法人数');?>");
                                   return false;
                               }                               
                           });
                           
                           
                        </script>