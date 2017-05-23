                                            
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4><?php echo yii::t('app','消费记录');?></h4>   
                                                    <h5><?php echo $name;?></h5>                                                     
                                                </div>
                                                <div class="modal-body">
                                                        <table class="table table-striped table-bordered table-hover" id="table_retreat">
                                                            <?php if($models):?>
                                                                    <thead>
                                                                            <tr>
                                                                            	<th><?php echo yii::t('app','账单号');?></th>
                                                                                <th><?php echo yii::t('app','时间');?></th>
                                                                                <th><?php echo yii::t('app','性质');?></th>
                                                                                <th><?php echo yii::t('app','订单金额');?></th>
                                                                                <th><?php echo yii::t('app','储值支付');?></th>                                                                                    
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php foreach ($models as $model):?>
                                                                            <tr class="odd gradeX">
                                                                            	<td ><?php echo $model['account_no'];?></td>
	                                                                            <td ><?php echo $model['create_at'];?></td>
	                                                                            <td >
	                                                                            <?php switch($model['order_type']){ 
	                                                                            		case "0": echo '堂食';break;
	                                                                            		case "1": echo "微信堂食";break;
	                                                                            		case "2": echo "微信外送";break;
	                                                                            		case "3": echo "微信预约";break;
	                                                                            		case "4": echo "后台外卖";break;
	                                                                            		case "5": echo "自助点餐";break;
	                                                                            		case "6": echo "微信点单";break;
	                                                                            		default : echo $model['order_type'];break;
	                                                                            }
	                                                                            ?></td>
	                                                                            <td ><?php echo $model['reality_total'];?></td>  
	                                                                            <td ><?php echo $model['pay_amount'];?></td>
	                                                                                                                                                              
                                                                            </tr>
                                                                    <?php endforeach;?>
                                                                    </tbody>                                                                    
                                                                    <?php endif;?>
                                                            </table>
                                                    <?php if($pages->getItemCount()):?>
						<div class="row">
							<div class="col-md-5 col-sm-12">
								<div class="dataTables_info">
									<?php echo yii::t('app','共');?> <?php echo $pages->getPageCount();?> <?php echo yii::t('app','页');?>  , <?php echo $pages->getItemCount();?> <?php echo yii::t('app','条数据');?> , <?php echo yii::t('app','当前是第');?><?php echo $pages->getCurrentPage()+1;?> <?php echo yii::t('app','页');?>
								</div>
							</div>
							<div class="col-md-7 col-sm-12">
								<div class="dataTables_paginate paging_bootstrap">
								<ul class="pagination pull-right" id="yw0">
                                                                    <li class=""><a href="javascript:;" onclick="gotopage(1)">&lt;&lt;</a></li>
                                                                    <li class=""><a href="javascript:;" onclick="gotopage(<?php echo $pages->getCurrentPage();?>)">&lt;</a></li>
                                                                    <li class=" active"><a href="javascript:;"><?php echo $pages->getCurrentPage()+1;?></a></li>
                                                                    <li class=""><a href="javascript:;" onclick="gotopage(<?php echo $pages->getCurrentPage()+2;?>)">&gt;</a></li>
                                                                    <li class=""><a href="javascript:;" onclick="gotopage(<?php echo $pages->getPageCount();?>)">&gt;&gt;</a></li></ul>
								</div>
							</div>
						</div>
						<?php endif;?>
                                                    </div>
                                                <div class="modal-footer">                                                        
                                                        <button type="button" class="btn default" data-dismiss="modal"><?php echo yii::t('app','返 回');?></button>                                                        
                                                </div>
                                                
                                                <script>
                                                        function gotopage(data)
                                                        {
                                                            modalconsumetotal.find('.modal-content').load(totalurl+"/page/"+data);
                                                        }
                                                        
                                                </script>