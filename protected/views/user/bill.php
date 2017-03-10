<?php
	$baseUrl = Yii::app()->baseUrl;
	$this->setPageTitle('账单');
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
                <th>账单号</th>
                <th>金额</th>
                <th>时间</th>
            </tr>
             <?php  
                    if($order_pay): 
                    foreach($order_pay as $v):                 
                ?>
            <tr>
                <td><?php echo $v['account_no'];?></td>
                <td><?php echo $v['amount'];?></td>
                <td><?php echo  date('Y.m.d',strtotime($v['create_at']));?>
              
            </tr>
              <?php                                    
                    endforeach;
                    endif;
                ?>
        </table>
       
     </div>   