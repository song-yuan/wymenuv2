			
				
                                    <ul class="dropdown-menu-list scroller" style="height: 420px;">
                                            
                                            <?php foreach ($msgs as $msg):?>
                                            <li class="sitemsg" site_id="<?php echo $msg['site_id']; ?>" is_temp="<?php echo $msg['is_temp']; ?>">  
                                                    <a href="#">
                                                    <span class="badge"><?php echo $msg['lcount']; ?></span> 
                                                    <?php echo $msg['name']; ?> 
                                                    <span class="time"><?php echo yii::t('app','刚刚');?></span>
                                                    </a>                                                    
                                            </li>
                                            <li class="sitemsg list-group-item" site_id="<?php echo $msg['site_id']; ?>" is_temp="<?php echo $msg['is_temp']; ?>"><span class="badge badge-danger"><?php echo $msg['lcount']; ?></span><?php echo $msg['name']; ?></li>
                                            <?php endforeach;?>  
                                                <!--
                                                <li><span class="badge">4</span> <?php echo yii::t('app','卡座：A123');?></li>  
                                                <li class="bg-yellow">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li class="bg-green">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
                                                <li class="bg-red">[K001-12:01]<br>开发票：上海物易网络科技有限公司</li>
                                                <li class="bg-yellow">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li class="bg-green">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
                                                <li class="bg-red">[K001-12:01]<br>开发票：上海物易网络科技有限公司</li>
                                                <li class="bg-yellow">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li class="bg-green">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
                                                <li class="bg-red">[K001-12:01]<br>开发票：上海物易网络科技有限公司</li>
                                                <li class="bg-yellow">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li class="bg-green">[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
						<li>[牡丹江厅-12:00]<br>催菜：都等了半个小时了</li>
                                                -->
					</ul>
				
                        <!--<button type="button" id="notifyButton" class="btn default">取 消</button>-->
                        <script type="text/javascript">
                        /*document.getElementById('notifyButton').onclick = function(){
                            //判断浏览器是否支持notification
                            if(window.webkitNotifications){
                                //判断当前页面是否被允许发出通知
                                if(webkitNotifications.checkPermission==0){
                                    var icon_url = 'http://www.w3.org/';
                                    var title = 'Hello HTML5';
                                    var body = 'I will be always here waiting for you!';
                                    var WebkitNotification = webkitNotifications.createNotification(icon_url, title, body);
                                    WebkitNotification.show();
                                }else{
                                    document.getElementById('requestbutton').onclick = function () {
                                        webkitNotifications.requestPermission();
                                    };
                                }
                            }else alert("<?php echo yii::t('app','您的浏览器不支持桌面通知特性，请下载谷歌浏览器试用该功能');?>");
                        };*/
                        $('.sitemsg').on('click',function(){
                            var site_id = $(this).attr('site_id');
                            var is_temp = $(this).attr('is_temp');
                            var $modalconfig = $('#portlet-config3');
                            //$modalconfig.modal('hide');
                            $modalconfig.find('.modal-content').load('<?php echo $this->createUrl('default/msglist',array('companyId'=>$this->companyId));?>'+'/site_id/'+site_id+'/is_temp/'+is_temp, '', function(){
                                        $modalconfig.modal();                                            
                            });
                        });
                       
                        </script>
        