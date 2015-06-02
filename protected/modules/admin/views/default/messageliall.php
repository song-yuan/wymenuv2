                                           <?php foreach ($msgs as $msg):?>
                                            <li class="sitemsg" order_feed_id="<?php echo $msg['lid']; ?>">  
                                                    <a href="#">
                                                    <?php echo FeedBackClass::getFeedbackSite($msg['dpid'], $msg['site_id'], $msg['is_temp'], $msg['order_id'], $msg['is_order'])
                                                            ."-->".FeedBackClass::getFeedbackObject($msg['order_id'],$msg['is_order'],$msg['dpid'])
                                                            ."-->".FeedBackClass::getFeedbackName($msg['feedback_id'],$msg['dpid'])
                                                            ."-->".$msg['feedback_memo']; ?> 
                                                    <span class="time"><?php echo $msg['timediff']; ?><?php echo yii::t('app','秒');?></span>
                                                    </a>
                                                    	
                                            </li>
                                            <?php endforeach;?>  
                                                <!--
                                                <li><span class="badge">4</span> 卡座：A123</li>  
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
				
                        <!--<button type="button" id="notifyButton" class="btn default"><?php echo yii::t('app','取 消');?></button>-->
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
                            //return true;
                            var order_feed_id = $(this).attr('order_feed_id');
                            
                            if(confirm("<?php echo yii::t('app','你确定要消除这条消息吗？');?>"))
                            {
                                $.get('<?php echo $this->createUrl('default/readfeedback',array('companyId'=>$this->companyId));?>/orderfeedbackid/'+order_feed_id,function(data){
                                        if(data.status) {
                                                alert("<?php echo yii::t('app','消除成功');?>");
                                                getnotificationnum();
                                                $('#header_notification_list').load('<?php echo $this->createUrl('default/messageliall',array('companyId'=>$this->companyId));?>'); 
                                        } else {
                                                alert(data.msg);
                                        }
                                },'json');
                            }
                            //var $modalconfig = $('#portlet-config3');
                            //alert("aa");
                            //$modalconfig.modal('hide');
                            //$modalconfig.find('.modal-content').load('<?php echo $this->createUrl('default/msglist',array('companyId'=>$this->companyId));?>'+'/site_id/'+site_id+'/is_temp/'+is_temp, '', function(){
                            //            $modalconfig.modal();                                            
                            //});
                        });
                       
                        </script>
        