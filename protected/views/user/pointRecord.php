<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('积分记录');
?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/js/bootstrap.min.js');?>
<?php Yii::app()->clientScript->registerCssFile(Yii::app()->request->baseUrl.'/plugins/bootstrap/css/bootstrap.min.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/weui.css');?>
<?php Yii::app()->clientScript->registerCssFile( Yii::app()->request->baseUrl.'/css/wechat_css/example.css');?>
<style>
   .page{
        padding:10px 15px 10px 15px;
    }
</style>

    <div class="page">
        <table class="table table-striped">
            <tr>
                <th>时间</th>
                <th>积分</th>
                <th>剩余积分</th>
                <th>过期时间</th>
            </tr>
             <?php  
                    if($points): 
                    foreach($points as $v):                 
                ?>
            <tr>
                <td><?php echo date('Y.m.d',strtotime($v['create_at']));?></td>
                <td><?php echo $v['points'];?></td>
                <td><?php echo $v['remain_points'];?></td> 
                <td><?php echo date('Y.m.d',strtotime($v['end_time']));?></td>
            </tr>
              <?php                                    
                    endforeach;
                    endif;
                ?>
        </table>
    </div>
