                                            
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                                                    <h4><?php echo yii::t('app','微信会员明细');?></h4>                                                       
                                                </div>
                                                <div class="modal-body" style="display:inline-block">
                                                            <?php if(!empty($model)):?>
                                                                <div class="form-group more-condition" style="float:left;width:250px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <img style="width:100%;" src="<?php echo $model['head_icon'];?>">
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:180px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                                <span class="input-group-addon" style="text-align:left;">卡号:<?php echo $model['card_id'];?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">用户名:<?php echo $model['user_name'];?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">昵称:<?php echo $model['nickname'];?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:100px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">性别:
                                                                                <?php switch($model['sex']) { case "0": echo "未知";break; case "1": echo "男";break; case "2": echo "女"; break;};?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">手机号:
                                                                                <?php 
																			if($model['mobile_num']){
										                                    	if(Yii::app()->user->role == 8){
										                                    		$str = substr_replace($model['mobile_num'],'****',3,4);
										                                    	}else{
										                                    		$str = $model['mobile_num'];
										                                    	}
										                                    	echo $str;
										                                    };?></span>                                                                              
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">出生日期:
                                                                                <?php echo substr($model['user_birthday'],0,10);?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">微信分组:
                                                                                <?php echo $wg;?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">会员等级:
                                                                                <?php echo $ul;?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">国家:
                                                                                <?php echo $model['country'];?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">省:
                                                                                <?php echo $model['province'];?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                                <div class="form-group more-condition" style="float:left;width:150px;disabled:true;margin-left:10px;">
                                                                        <div class="input-group" style="width:95%;">
                                                                            <span class="input-group-addon" style="text-align:left;">市:
                                                                                <?php echo $model['city'];?></span>                                                                                
                                                                        </div>
                                                                </div>
                                                            <?php endif;?>                                                         
                                                </div>
                                                <div class="modal-footer">                                                        
                                                        <button type="button" class="btn default" data-dismiss="modal"><?php echo yii::t('app','返 回');?></button>                                                        
                                                </div>
                                                