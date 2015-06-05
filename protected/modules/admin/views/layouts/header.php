	<!-- BEGIN HEADER -->   
	<div class="header navbar navbar-inverse navbar-fixed-top">
		<!-- BEGIN TOP NAVIGATION BAR -->
		<div class="header-inner">
			<!-- BEGIN LOGO -->  
			<a class="navbar-brand" href="<?php echo $this->createUrl('default/index');?>">
			<span style="margin-left:20px;"><?php echo yii::t('app','我要MENU')?></span>
			</a>
			<!-- END LOGO -->
			<!-- BEGIN RESPONSIVE MENU TOGGLER --> 
			<a href="javascript:;" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
			<img src="<?php echo Yii::app()->request->baseUrl;?>/img/menu-toggler.png" alt="" />
			</a> 
			<!-- END RESPONSIVE MENU TOGGLER -->
			<!-- BEGIN TOP NAVIGATION MENU -->
			<ul class="nav navbar-nav pull-right">
				<!-- BEGIN NOTIFICATION DROPDOWN -->
				<li class="dropdown" id="header_notification_bar">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" id="notification_banner_id"
						data-close-others="true">
					<i class="fa fa-warning"></i>
					<span class="badge" id="allnotificationnum">0</span>
					</a>
                                    <ul class="dropdown-menu extended notification" style="max-width: 800px !important; width:600px !important;">
						<li>
							<p><?php echo yii::t('app','未读消息,点击消除消息');?></p>
						</li>
						<li>
							<ul id="header_notification_list" class="dropdown-menu-list scroller" style="height: 420px;">
                                                                <li>  
									<a href="#">
									<span class="badge">4</span> 
									<?php echo yii::t('app','卡座');?>：A123 
									<span class="time"><?php echo yii::t('app','刚刚');?></span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-success"><i class="fa fa-plus"></i></span>
									New user registered. 
									<span class="time">Just now</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-danger"><i class="fa fa-bolt"></i></span>
									Server #12 overloaded. 
									<span class="time">15 mins</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-warning"><i class="fa fa-bell-o"></i></span>
									Server #2 not responding.
									<span class="time">22 mins</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-info"><i class="fa fa-bullhorn"></i></span>
									Application error.
									<span class="time">40 mins</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-danger"><i class="fa fa-bolt"></i></span>
									Database overloaded 68%. 
									<span class="time">2 hrs</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-danger"><i class="fa fa-bolt"></i></span>
									2 user IP blocked.
									<span class="time">5 hrs</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-warning"><i class="fa fa-bell-o"></i></span>
									Storage Server #4 not responding.
									<span class="time">45 mins</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-info"><i class="fa fa-bullhorn"></i></span>
									System Error.
									<span class="time">55 mins</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="label label-sm label-icon label-danger"><i class="fa fa-bolt"></i></span>
									Database overloaded 68%. 
									<span class="time">2 hrs</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="external">   
							<a href="#"><!-- <i class="m-icon-swapright"></i>--></a>
						</li>
					</ul>
				</li>
				<!-- END NOTIFICATION DROPDOWN -->
				<!-- END TODO DROPDOWN -->
				<!-- BEGIN USER LOGIN DROPDOWN -->
                                <li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="<?php echo Yii::app()->request->baseUrl;?>/img/house_small.jpg"/>
					<span class="username"><?php echo Helper::getCompanyName($this->companyId);?></span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
                                                <li><a href="<?php echo $this->createUrl('company/index').'/companyId/'.$this->companyId;?>" data-method='get'><i class="fa fa-key"></i> <?php echo yii::t('app','选择其他店铺');?></a>
						</li>
					</ul>
				</li>
				<li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="<?php echo Yii::app()->request->baseUrl;?>/img/avatar1_small.jpg"/>
                                        <span class="username"><?php echo Yii::app()->user->name; ?></span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
					
						<li><a href="javascript:;" id="trigger_fullscreen"><i class="fa fa-move"></i> <?php echo yii::t('app','全屏显示');?></a>
						</li>
						</li>
						<li><a href="<?php echo $this->createUrl('login/logout');?>" data-method='post'><i class="fa fa-key"></i> <?php echo yii::t('app','安全退出');?></a>
						</li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
			<!-- END TOP NAVIGATION MENU -->
		</div>
		<!-- END TOP NAVIGATION BAR -->
	</div>
	<!-- END HEADER -->
	<div class="clearfix"></div>
        <script type="text/javascript">
            
            $(document).ready(function() {
                $('<audio id="chatAudio"><source src="/wymenuv2/admin/audio/notify.ogg" type="audio/ogg"><source src="/wymenuv2/admin/audio/notify.mp3" type="audio/mpeg"><source src="/wymenuv2/admin/audio/notify.wav" type="audio/wav"></audio>').appendTo('body');
                //getnotificationnum();
                //$('#header_notification_list').load('<?php echo $this->createUrl('default/messageliall',array('companyId'=>$this->companyId));?>'); 
                
                //interval = setInterval(getnotificationnum,"15000");
            });            
            
            $('#notification_banner_id').on('click', function(){
                getnotificationnum();
                $('#header_notification_list').load('<?php echo $this->createUrl('default/messageliall',array('companyId'=>$this->companyId));?>'); 
            });
            
            function getnotificationnum(){
                $.get('<?php echo $this->createUrl('default/msgnum',array('companyId'=>$this->companyId));?>',function(data){
                if(data.status) {
                      document.getElementById('allnotificationnum').innerHTML = data.num;
                      if(data.num>0)
                      {
                            $('#chatAudio')[0].play();
                      }
                    } 
                },'json');
            }
            //clearTimeout(interval); 
	</script>