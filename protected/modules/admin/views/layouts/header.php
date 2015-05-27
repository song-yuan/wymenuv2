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
							<p>未读消息(一次最多显示20条),点击消除消息</p>
						</li>
						<li>
							<ul id="header_notification_list" class="dropdown-menu-list scroller" style="height: 420px;">
                                                                <li>  
									<a href="#">
									<span class="badge">4</span> 
									卡座：A123 
									<span class="time">刚刚</span>
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
							<a href="#">查看全部消息 <i class="m-icon-swapright"></i></a>
						</li>
					</ul>
				</li>
				<!-- END NOTIFICATION DROPDOWN -->
				<!-- BEGIN INBOX DROPDOWN -->
				<!-- <li class="dropdown" id="header_inbox_bar">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown"
						data-close-others="true">
					<i class="fa fa-envelope"></i>
					<span class="badge">5</span>
					</a>
					<ul class="dropdown-menu extended inbox">
						<li>
							<p>You have 12 new messages</p>
						</li>
						<li>
							<ul class="dropdown-menu-list scroller" style="height: 250px;">
								<li>  
									<a href="#">
									<span class="photo"><img src="img/avatar3.jpg" alt=""/></span>
									<span class="subject">
									<span class="from">Richard Doe</span>
									<span class="time">46 mins</span>
									</span>
									<span class="message">
									Vivamus sed congue nibh auctor nibh congue nibh. auctor nibh
									auctor nibh...
									</span>  
									</a>
								</li>
							</ul>
						</li>
						<li class="external">   
							<a href="#">See all messages <i class="m-icon-swapright"></i></a>
						</li>
					</ul>
				</li> -->
				<!-- END INBOX DROPDOWN -->
				<!-- BEGIN TODO DROPDOWN -->
				<!-- <li class="dropdown" id="header_task_bar">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<i class="fa fa-tasks"></i>
					<span class="badge">5</span>
					</a>
					<ul class="dropdown-menu extended tasks">
						<li>
							<p>You have 12 pending tasks</p>
						</li>
						<li>
							<ul class="dropdown-menu-list scroller" style="height: 250px;">
								<li>  
									<a href="#">
									<span class="task">
									<span class="desc">New release v1.2</span>
									<span class="percent">30%</span>
									</span>
									<span class="progress">
									<span style="width: 40%;" class="progress-bar progress-bar-success" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
									<span class="sr-only">40% Complete</span>
									</span>
									</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="task">
									<span class="desc">Application deployment</span>
									<span class="percent">65%</span>
									</span>
									<span class="progress progress-striped">
									<span style="width: 65%;" class="progress-bar progress-bar-danger" aria-valuenow="65" aria-valuemin="0" aria-valuemax="100">
									<span class="sr-only">65% Complete</span>
									</span>
									</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="task">
									<span class="desc">Mobile app release</span>
									<span class="percent">98%</span>
									</span>
									<span class="progress">
									<span style="width: 98%;" class="progress-bar progress-bar-success" aria-valuenow="98" aria-valuemin="0" aria-valuemax="100">
									<span class="sr-only">98% Complete</span>
									</span>
									</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="task">
									<span class="desc">Database migration</span>
									<span class="percent">10%</span>
									</span>
									<span class="progress progress-striped">
									<span style="width: 10%;" class="progress-bar progress-bar-warning" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
									<span class="sr-only">10% Complete</span>
									</span>
									</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="task">
									<span class="desc">Web server upgrade</span>
									<span class="percent">58%</span>
									</span>
									<span class="progress progress-striped">
									<span style="width: 58%;" class="progress-bar progress-bar-info" aria-valuenow="58" aria-valuemin="0" aria-valuemax="100">
									<span class="sr-only">58% Complete</span>
									</span>
									</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="task">
									<span class="desc">Mobile development</span>
									<span class="percent">85%</span>
									</span>
									<span class="progress progress-striped">
									<span style="width: 85%;" class="progress-bar progress-bar-success" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
									<span class="sr-only">85% Complete</span>
									</span>
									</span>
									</a>
								</li>
								<li>  
									<a href="#">
									<span class="task">
									<span class="desc">New UI release</span>
									<span class="percent">18%</span>
									</span>
									<span class="progress progress-striped">
									<span style="width: 18%;" class="progress-bar progress-bar-important" aria-valuenow="18" aria-valuemin="0" aria-valuemax="100">
									<span class="sr-only">18% Complete</span>
									</span>
									</span>
									</a>
								</li>
							</ul>
						</li>
						<li class="external">   
							<a href="#">See all tasks <i class="m-icon-swapright"></i></a>
						</li>
					</ul>
				</li> -->
				<!-- END TODO DROPDOWN -->
				<!-- BEGIN USER LOGIN DROPDOWN -->
                                <li class="dropdown user">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
					<img alt="" src="<?php echo Yii::app()->request->baseUrl;?>/img/house_small.jpg"/>
					<span class="username"><?php echo Helper::getCompanyName($this->companyId);?></span>
					<i class="fa fa-angle-down"></i>
					</a>
					<ul class="dropdown-menu">
                                                <li><a href="<?php echo $this->createUrl('company/index').'/companyId/'.$this->companyId;?>" data-method='get'><i class="fa fa-key"></i> 选择其他店铺</a>
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
					<!-- 
						<li><a href="extra_profile.html"><i class="fa fa-user"></i> My Profile</a>
						</li>
						<li><a href="page_calendar.html"><i class="fa fa-calendar"></i> My Calendar</a>
						</li>
						<li><a href="inbox.html"><i class="fa fa-envelope"></i> My Inbox <span class="badge badge-danger">3</span></a>
						</li>
						<li><a href="#"><i class="fa fa-tasks"></i> My Tasks <span class="badge badge-success">7</span></a>
						</li>
						<li class="divider"></li> -->
						<li><a href="javascript:;" id="trigger_fullscreen"><i class="fa fa-move"></i> 全屏显示</a>
						</li>
						</li>
						<li><a href="<?php echo $this->createUrl('login/logout');?>" data-method='post'><i class="fa fa-key"></i> 安全退出</a>
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
                getnotificationnum();
                $('#header_notification_list').load('<?php echo $this->createUrl('default/messageliall',array('companyId'=>$this->companyId));?>'); 
                
                interval = setInterval(getnotificationnum,"15000");
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