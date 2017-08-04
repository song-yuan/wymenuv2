<div class="page-content">
 <div id="responsive" class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
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
 <?php $this->widget('application.modules.admin.components.widgets.PageHeader', array('breadcrumbs'=>array(array('word'=>yii::t('app','外卖设置'),'url'=>$this->createUrl('waimai/list' , array('companyId'=>$this->companyId,'type'=>0,))),array('word'=>yii::t('app','饿了么外卖'),'url'=>$this->createUrl('eleme/index' , array('companyId'=>$this->companyId))),array('word'=>yii::t('app','店铺对应'),'url'=>$this->createUrl('eleme/dpdy' , array('companyId'=>$this->companyId,'type'=>0)))),'back'=>array('word'=>yii::t('app','返回'),'url'=>$this->createUrl('eleme/index' , array('companyId' => $this->companyId,'type' =>'0')))));?>
 <div>
 	<?php 
 		$obj = json_decode($result);
        if(!empty($obj->result)){
        	$sql = "select * from nb_eleme_dpdy where dpid=".$this->companyId." and shopId=".$shopid." and delete_flag=0";
        	$dp = Yii::app()->db->createCommand($sql)->queryRow();
        	if(empty($dp)){
        		$se=new Sequence("eleme_dpdy");
				$lid = $se->nextval();
				$creat_at = date("Y-m-d H:i:s");
				$update_at = date("Y-m-d H:i:s");
				$shopid = $obj->result->id;
				$inserData = array(
							'lid'=>	$lid,
							'dpid'=>$this->companyId,
							'create_at'=>$creat_at,
							'update_at'=>$update_at,
							'shopId'=>$shopid
					);
				$res = Yii::app()->db->createCommand()->insert('nb_eleme_dpdy',$inserData);
				echo  yii::t('app','店铺对应成功');
        	}else{
        		echo  yii::t('app','店铺已对应');
        	}
        }
 	?>	
 </div>
 </div>