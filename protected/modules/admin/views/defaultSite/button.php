			<?php $form=$this->beginWidget('CActiveForm', array(
				'id'=>'siteNo',
				'enableAjaxValidation'=>true,
				'enableClientValidation'=>true,
				'clientOptions'=>array(
					'validateOnSubmit'=>false,
				),
				'htmlOptions'=>array(
					'class'=>'form-horizontal'
				),
			)); ?>
			
			<div class="modal-body">
				<?php if($typeId=='queue') :?>
                                        第一个排队号：<?php echo $nexpersons; ?><br>
                                        <button  id="queuecall" style="width:7.0em;margin:10px; " type="button" nexpersons="<?php echo $nexpersons; ?>" splid="<?php echo $sid; ?>" stlid="<?php echo $istemp; ?>" class="btn green"><?php echo yii::t('app','叫号');?></button>
                                        <button  id="queuepass" style="width:7.0em;margin:10px; " type="button" nexpersons="<?php echo $nexpersons; ?>" splid="<?php echo $sid; ?>" stlid="<?php echo $istemp; ?>" class="btn green"><?php echo yii::t('app','下一个');?></button>                                        
                                <?php elseif($status=='1') :?>
                                <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn grey orderaction"><?php echo yii::t('app','点 单');?></button>
                                <div class="pull-right">
                                    <button type="button" data-dismiss="modal" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe closesite"><?php echo yii::t('app','撤  台');?></button>
                                    <button type="button" style="margin-left: 25px;margin-right: 25px;" data-dismiss="modal" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe switchsite"><?php echo yii::t('app','转  台');?></button>
                                    <button type="button" data-dismiss="modal" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe unionsite"><?php echo yii::t('app','并  台');?></button>                                    
                                </div>
                                <?php elseif($status=='2') :?>
                                    <!--<button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn blue acountbtn" style="margin-right: 15px;"><?php echo yii::t('app','结单&收银');?></button>-->
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn yellow orderaction"><?php echo yii::t('app','订单详情');?></button>
                                <div class="pull-right">
                                    <!--<button type="button" data-dismiss="modal" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe closesite"><?php echo yii::t('app','撤  台');?></button>-->
                                    <button type="button" style="margin-left: 25px;margin-right: 25px;" data-dismiss="modal" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe switchsite"><?php echo yii::t('app','转  台');?></button>
                                    <button type="button" data-dismiss="modal" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe unionsite"><?php echo yii::t('app','并  台');?></button>
                                </div>
                                <?php elseif($status=='3') :?>
                                    <!--<button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn blue acountbtn" style="margin-right: 15px;"><?php echo yii::t('app','结单&收银');?></button>-->
                                    <button type="button" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn yellow orderaction"><?php echo yii::t('app','订单详情');?></button>
                                <div class="pull-right">
                                    <button type="button" data-dismiss="modal" sid="<?php echo $sid; ?>" istemp="<?php echo $istemp; ?>" class="btn red-stripe switchsite"><?php echo yii::t('app','转  台');?></button>
                                </div>
                                <?php else :?>
                                    <div>
                                        <label class="col-md-3 control-label"><?php echo yii::t('app','人数');?></label>
                                        <div class="col-md-3">
                                            <input class="form-control" placeholder="<?php echo yii::t('app','请输入人数');?>" name="siteNumber" id="site_number" type="text" maxlength="3" style="width:55px;" value="1">
                                        </div>
                                        <!--<label class="col-md-3 control-label"><?php echo yii::t('app','小孩');?></label>
                                        <div style="">
                                            <label id="open_site_minus" style="padding: 4px; margin: 4px; border: 1px;">-1</label>
                                            <label style="padding: 5px;margin: 6px;" name="siteNumber" id="site_number">4</label>
                                            <label id="open_site_plus" style="padding: 4px; margin: 4px; border: 1px;">+1</label>
                                        </div>-->
                                    </div>
                                <div>
                                    <button id="site_open" style="margin-right: 25px;" type="button" istemp="<?php echo $istemp; ?>" sid="<?php echo $sid; ?>" class="btn green"><?php echo yii::t('app','开台并打印');?></button>
                                    <button id="open_order" type="button" istemp="<?php echo $istemp; ?>" sid="<?php echo $sid; ?>" class="btn green-stripe"><?php echo yii::t('app','开台并点单');?></button>
                                </div>
                                <?php endif; ?>
				
			</div>
			
			<?php $this->endWidget(); ?>
                        <script type="text/javascript">
                            $(document).ready(function() {
                                //alert($('#site_number')[0]);
                                var sno=$("#site_number");
                                if(sno.length > 0)
                                {
                                    sno[0].focus();
                                }
                            });
                            
                            function clearolddata(){
                                $("#tab_sitelist").show();
                                $('#pxbox_button').hide();
                                $('#site_row').hide();
                                $('#order_row').show();
                                $("#payDiscountAccount").text("100%");
                                $("#payMinusAccount").text("0.00");
                                $("#cancel_zero").removeClass("edit_span_select_zero");
                                $("#payRealityAccount").text("0.00");
                                $("#payChangeAccount").text("0.00");
                                $("#payCashAccount").text("0.00");
                                $("#payMemberAccount").text("0.00");
                                $("#payUnionAccount").text("0.00");
                                $("#payOthers").text("0.00");
                                $("#payOthers").attr("otherdetail","");
                                $("#card_pay_span_card").text("");
                                $("#card_pay_span_card").attr("actual","");
                                $("#card_pay_span_password").text("");
                                $("#card_pay_span_password").attr("actual","");
                            }
                            
                           $('#site_open').on(event_clicktouchstart,function(){
                               var siteNumber=$('#site_number').val();                               
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               var companyid='<?php echo $this->companyId; ?>';
                               var padid="0000000039";
                               if (typeof Androidwymenuprinter == "undefined") {
                                    alert("<?php echo yii::t('app','无法获取PAD设备信息，请在PAD中运行该程序！');?>");
                                    //return false;
                                }else{
                                    var padinfo=Androidwymenuprinter.getPadInfo();
                                    padid=padinfo.substr(10,10);
                                }
                               //alert(istemp);alert(companyid);
                               if(!isNaN(siteNumber) && siteNumber>0 && siteNumber < 199)
                               {
                                   //alert(!isNaN(siteNumber));
                                    $.ajax({
					'type':'POST',
					'dataType':'json',
					'data':{"sid":sid,"siteNumber":siteNumber,"companyId":companyid,"istemp":istemp,"padId":padid},
					'url':'<?php echo $this->createUrl('defaultSite/opensiteprint',array());?>',
					'success':function(data){
						if(data.status == 0) {
							alert(data.msg+"0");                                                        
						} else {
							alert(data.msg+"1");
                                                        //$('#portlet-button').modal('hide');
							$('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>');
//                                                        $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>');
//                                                        $('#tabsiteindex').load(tabcurrenturl); 
//                                                        $("#tab_sitelist").show();
//                                                        $('#pxbox_button').hide();
						}
					},
                                        'error':function(e){
                                            alert("错误");
                                            return false;
                                        }
                                    });
                                    
                               }else{
                                   alert("<?php echo yii::t('app','输入合法人数');?>");
                                   return false;
                               }                               
                           });
                           
                           $('#open_order').on(event_clicktouchstart,function(){
                               var siteNumber=$('#site_number').val();                               
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               if(!isNaN(siteNumber) && siteNumber>0 && siteNumber < 199)
                               {
                                   //alert(!isNaN(siteNumber));
                                    $.ajax({
					'type':'POST',
					'dataType':'json',
					'data':{"sid":sid,"siteNumber":siteNumber,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
					'url':'<?php echo $this->createUrl('defaultSite/opensite',array());?>',
					'success':function(data){
						if(data.status === 0) {
							alert(data.msg);
						} else {
							alert(data.msg);
                                                        $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>'+'/sid/'+data.siteid+'/istemp/'+istemp);
                                                        clearolddata();
						}
					},
                                        'error':function(e){
                                            return false;
                                        }
                                    });
                                    //return false;
                               }else{
                                   alert("<?php echo yii::t('app','输入合法人数');?>");
                                   return false;
                               }                                                              
                           });
                           
                           $('.closesite').on(event_clicktouchstart,function(){
                                var statu = confirm("<?php echo yii::t('app','确定撤台吗？');?>");
                                if(!statu){
                                    return false;
                                } 
                               var sid = $(this).attr('sid');
                               $.ajax({
                                    'type':'POST',
                                    'dataType':'json',
                                    'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php echo $istemp; ?>'},
                                    'url':'<?php echo $this->createUrl('defaultSite/closesite',array());?>',
                                    'success':function(data){
                                            if(data.status == 0) {
                                                    alert(data.message);
                                                    return false;
                                            } else {
                                                    alert(data.message);
                                                    $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>');
                                                    //$('#portlet-button').modal('hide');
                                                    //$("#tab_sitelist").hide();
                                            }
                                    },
                                        'error':function(e){
                                            return false;
                                        }
                                });
                                //return false;                               
                           });
                           
                           $('.switchsite').on(event_clicktouchstart,function(){
                               //var sid = $(this).attr('sid');
                               var statu = confirm("<?php echo yii::t('app','确定换台吗？');?>");
                                if(!statu){
                                    return false;
                                }  
                                $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'switch','sistemp'=>$istemp,'ssid'=>$sid,'stypeId'=>$typeId));?>');
                                $('#portlet-button').modal('hide');
                           });                           
                           
                           $('.unionsite').on(event_clicktouchstart,function(){
                               //var sid = $(this).attr('sid');
                               var statu = confirm("<?php echo yii::t('app','确定并台吗？');?>");
                                if(!statu){
                                    return false;
                                }  
                                $('#tabsiteindex').load('<?php echo $this->createUrl('defaultSite/showSite',array('companyId'=>$this->companyId,'typeId'=>$typeId,'op'=>'union','sistemp'=>$istemp,'ssid'=>$sid,'stypeId'=>$typeId));?>');
                                $('#portlet-button').modal('hide');
                           });
                           
                           $('.orderaction').on(event_clicktouchstart,function(){
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               $('#orderdetailauto').load('<?php echo $this->createUrl('defaultOrder/orderPartial',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>'+'/sid/'+sid+'/istemp/'+istemp);
                               clearolddata();
                           });
                           
                           $('#btn-print-btn').on(event_clicktouchstart,function(){
                                var sid = $(this).attr('sid');
                                var istemp = $(this).attr('istemp');
                                $.get('<?php echo $this->createUrl('defaultOrder/printList',array('companyId'=>$this->companyId));?>'+'/sid/'+sid+'/istemp/'+istemp,function(data){
                                        if(data.status) {
                                                alert("<?php echo yii::t('app','操作成功');?>");
                                                //alert(data.msg);
                                        } else {
                                                alert(data.msg);
                                        }
                                },'json');
                            });
                            
                            $('#btn-account-btn').on(event_clicktouchstart,function(){
                                var sid = $(this).attr('sid');
                                var istemp = $(this).attr('istemp');
                                var $modalconfig = $('#portlet-button');
                                //$modalconfig.modal('hide');
                                $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('defaultOrder/account',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>'+'/sid/'+sid+'/istemp/'+istemp, '', function(){
                                  
                                            $modalconfig.modal();                                            
                                         }); 
                                          
                            });
                            
                            $('.acountbtn').on(event_clicktouchstart,function(){
                               var sid = $(this).attr('sid');
                               var istemp = $(this).attr('istemp');
                               //alert(istemp);
                               location.href='<?php echo $this->createUrl('defaultOrder/order',array('companyId'=>$this->companyId,'typeId'=>$typeId,'autoaccount'=>'1'));?>'+'/sid/'+sid+'/istemp/'+istemp;
                           });
                            
                           $('#closemodalid').on(event_clicktouchstart,function(){
                               //alert("sdf");
                               return;                                           
                            });
                            
                            $('#queuecall').on(event_clicktouchstart,function(){
                               if (typeof Androidwymenuprinter == "undefined") {
                                    alert("找不到PAD设备");
                                }else{
                                    Androidwymenuprinter.queuecall("AM001");                                    
                                }                                           
                            });                            
                        </script>