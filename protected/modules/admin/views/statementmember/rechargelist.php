                                            
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4><?php echo yii::t('app','充值记录');?></h4>   
                                                    <h5><?php echo $name;?></h5>                                                     
                                                </div>
                                                <div class="modal-body">
                                                        <table class="table table-striped table-bordered table-hover" id="table_retreat">
                                                            <?php if($models):?>
                                                                    <thead>
                                                                            <tr>
                                                                                <th><?php echo yii::t('app','充值时间');?></th>
                                                                                <th><?php echo yii::t('app','充值金额');?></th>
                                                                                <th><?php echo yii::t('app','赠送金额');?></th>
                                                                                <th><?php echo yii::t('app','赠送积分');?></th>                                                                                    
                                                                            </tr>
                                                                    </thead>
                                                                    <tbody>
                                                                    <?php foreach ($models as $model):?>
                                                                            <tr class="odd gradeX">
	                                                                            <td ><?php echo $model['create_at'];?></td>
	                                                                            <td ><?php echo $model['recharge_money']?></td>
	                                                                            <td ><?php echo $model['cashback_num'];?></td>  
	                                                                            <td ><?php echo $model['point_num'];?></td>
	                                                                                                                                                              
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