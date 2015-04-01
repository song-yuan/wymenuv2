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
	<style type="text/css">
		.bg_add {
                    background-image: url("/wymenuv2/admin/img/addtempsite.png") !important;
                    filter:"progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod='scale')";
                    -moz-background-size:100% 100%;
                    background-size:100% 100%;                    
                    color: #fff !important;
                }
                .site_list {
			padding-right:10px;
		}
		.site_list {
			display:inline-block;
		}
                .site_list ul {
			padding-left:5px;
                        padding-left:0px;
		}
		.site_list ul li {
			float:left;
			width:80px;
                        height: 80px;			
			border: 1px solid #add;
			margin:5px;
			list-style:none;
			text-align:center;
			vertical-align:middle;
		}
		.messagepart {
			padding-left:0px;
		}
                .message_list {
			border-left:1px solid #000;
			padding-left:2px;
			min-height:500px;
		}
                .message_list ul {
			padding-left:2px;
		}
		.message_list ul li {
			list-style:none;
                        min-height: 35px;
                        line-height: 16px;
		}
	</style>
	<div class="row">
		<div class="col-md-10">
			<?php if($siteTypes):?>
					<div class="tabbable tabbable-custom">
						<ul class="nav nav-tabs">
						<?php foreach ($siteTypes as $key=>$siteType):?>
							<li class="<?php if($key == $typeId) echo 'active';?>"><a href="#tab_1_<?php echo $key;?>" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('default/index' , array('typeId'=>$key , 'companyId'=>$this->companyId)).$geturl;?>'"><?php echo $siteType ;?></a></li>
						<?php endforeach;?>
                                                        <li class="<?php if($typeId == 'tempsite') echo 'active';?>"><a href="#tab_1_tempsite" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('default/index' , array('typeId'=>'tempsite' , 'companyId'=>$this->companyId)).$geturl;?>'">临时座/排队</a></li>
                                                        <li class="<?php if($typeId == 'reserve') echo 'active';?>"><a href="#tab_1_reserve" data-toggle="tab" onclick="location.href='<?php echo $this->createUrl('default/index' , array('typeId'=>'reserve' , 'companyId'=>$this->companyId)).$geturl;?>'">预定</a></li>
						</ul>
						<div class="tab-content">
							<div class="portlet box purple">
								<div class="portlet-title">
									<div class="caption"><i class="fa fa-cogs"></i><?php echo $title; ?></div>
                                                                        <div class="col-md-3 pull-right">
                                                                                <input type="text" class="form-control" placeholder="条码枪扫描">
                                                                        </div>

								</div>
								<div class="portlet-body" id="table-manage">
				
                                                                        <div class="portlet-body site_list">
                                                                                <ul>
                                                                                    <?php if($typeId == 'tempsite'): ?>
                                                                                        <li class="modalaction bg_add" istemp="1" status="0" sid="0"></li>
                                                                                        <?php foreach ($models as $model):?>
                                                                                                <li class="modalaction <?php if($model->status=='1') echo 'bg-yellow'; elseif($model->status=='2') echo 'bg-blue'; elseif($model->status=='3') echo 'bg-green';?>" istemp="1" status=<?php echo $model->status;?> sid=<?php echo $model->site_id;?>><?php echo $model->site_id%1000;?></li>
                                                                                        <?php endforeach;?>
                                                                                    <?php else:?>
                                                                                        <?php foreach ($models as $model):?>
                                                                                                <li class="modalaction <?php if($model->status=='1') echo 'bg-yellow'; elseif($model->status=='2') echo 'bg-blue'; elseif($model->status=='3') echo 'bg-green';?>" istemp="0" status=<?php echo $model->status;?> sid=<?php echo $model->lid;?>><?php echo $model->serial.'<br>'.$model->site_level;?></li>
                                                                                        <?php endforeach;?>
                                                                                    <?php endif;?>
                                                                                        <li class="modalaction bg-yellow" istemp="0" status="1" sid="1"> 001 </li>
                                                                                        <li class="modalaction bg-blue" istemp="0" status="2" sid="2"> 002 </li>
                                                                                        <li class="modalaction bg-green" istemp="0" status="3" sid="3"> 003 </li>
                                                                                        <li class="modalaction" istemp="0" status="0" sid="4"> 004 </li>
                                                                                        <li class="modalaction" istemp="0" status="0" sid="5"> 005 </li>
                                                                                        <li class="modalaction" istemp="0" status="0" sid="21">021<br>普通包厢普通包厢</li>
                                                                                                                                                                               
                                                                                </ul>
                                                                        </div>
                                                                    </div>
							</div>
							<!-- END EXAMPLE TABLE PORTLET-->												
						</div>
					</div>
				<?php endif;?>
			
		</div>	
		<div class="col-md-2 messagepart">
			<div class="portlet box purple">
				<div class="portlet-title">
					<div class="caption"><i class="fa fa-volume-up"></i>2条未读消息</div>					
				</div>
				<div class="portlet-body message_list">
					<ul>
                                                <li class="bg-red">[K001-12:01]<br>开发票：上海物易网络科技有限公司</li>
                                                <li class="bg-blue">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
        <script type="text/javascript">
            $(document).ready(function() {
                $('body').addClass('page-sidebar-closed');
                $('<audio id="chatAudio"><source src="/wymenuv2/admin/audio/notify.ogg" type="audio/ogg"><source src="/wymenuv2/admin/audio/notify.mp3" type="audio/mpeg"><source src="/wymenuv2/admin/audio/notify.wav" type="audio/wav"></audio>').appendTo('body');
            });
            var $modal = $('.modal');
            $('.modalaction').on('click', function(){
                var sid = $(this).attr('sid');
                var status = $(this).attr('status');
                var istemp = $(this).attr('istemp');
                var typeId = '<?php echo $typeId; ?>';
                var geturl = '<?php echo $geturl; ?>';
                if((geturl.indexOf("op/switch") >= 0))
                {
                    if(('123'.indexOf(status) >=0))
                    {
                        alert("正在进行换台操作，请选择没有开台、下单的餐桌");
                        return false;
                    }else if(istemp==1)
                    {
                        alert("正在进行换台操作，请选择没有开台、下单的餐桌");
                        return false;
                    }else{
                        var statu = confirm("确定将该餐桌做为换台目标吗？");
                        if(!statu){
                            return false;
                        }
                        $.ajax({
                            'type':'POST',
                            'dataType':'json',
                            'data':{"sid":sid,"companyId":'<?php echo $this->companyId; ?>',"istemp":'<?php if($typeId=='tempsite') echo '1'; else echo '0'; ?>',"ssid":'<?php echo $ssid; ?>',"sistemp":'<?php echo $sistemp; ?>'},
                            'url':'<?php echo $this->createUrl('default/switchsite',array());?>',
                            'success':function(data){
                                    if(data.status == 0) {
                                            alert(data.message);
                                    } else {
                                            alert(data.message);
                                            location.href='<?php echo $this->createUrl('default/index',array('companyId'=>$this->companyId,'typeId'=>$typeId));?>';
                                    }
                            }
                        });
                        return false;
                        
                    }
                }
                if((geturl.indexOf("op/union") >= 0) && ('04567'.indexOf(status) >=0))
                {
                    alert("正在进行并台操作，请选择已经开台、下单的餐桌");
                    return false;
                }
                $('#chatAudio')[0].play();
                $modal.find('.modal-content').load('<?php echo $this->createUrl('default/button',array('companyId'=>$this->companyId));?>/sid/'+sid+'/status/'+status+'/istemp/'+istemp+'/typeId/'+typeId+'<?php echo $geturl; ?>', '', function(){
                  $modal.modal();
                });
            });
	</script>
	<!-- END PAGE CONTENT-->
        