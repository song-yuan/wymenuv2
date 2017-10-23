<?php
class GoodstockController extends BackendController
{
	public function actions() {
		return array(
				'upload'=>array(
						'class'=>'application.extensions.swfupload.SWFUploadAction',
						//注意这里是绝对路径,.EXT是文件后缀名替代符号
						'filepath'=>Helper::genFileName().'.EXT',
						//'onAfterUpload'=>array($this,'saveFile'),
				)
		);
	}
	public function beforeAction($action) {
		parent::beforeAction($action);
		if(!$this->companyId && $this->getAction()->getId() != 'upload') {
			Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
			$this->redirect(array('company/index'));
		}
		return true;
	}
	public function actionGoodsdelivery(){
		$db = Yii::app()->db;
		$sql = 'select k.* from (select c.company_name,t.* from nb_goods_delivery t left join nb_company c on(t.dpid = c.dpid) where t.dpid ='.$this->companyId.') k';
		//$models = $db->createCommand($sql)->queryAll();
		
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
	
		$this->render('goodsdelivery',array(
				'models'=>$models,
				'pages'=>$pages,
		));
		
	}
	public function actionDetailindex(){
		$goid = Yii::app()->request->getParam('lid');
		$name = Yii::app()->request->getParam('name');
		$papage = Yii::app()->request->getParam('papage');
		
		$db = Yii::app()->db;
		
		$sqls = 'select t.* from nb_goods_delivery t where t.lid ='.$goid;
		$model = $db->createCommand($sqls)->queryRow();
		
		$sqlstock = 'select t.* from nb_company t where t.type = 2 and t.comp_dpid ='.$this->companyId;
		$stocks = $db->createCommand($sqlstock)->queryAll();
		
		$sql = 'select k.* from (select c.goods_name,co.company_name as stock_name,t.* from nb_goods_delivery_details t left join nb_goods c on(t.goods_id = c.lid) left join nb_company co on(co.dpid = t.dpid ) where t.goods_delivery_id = '.$goid.' order by t.lid) k';
		//;
	
		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		//var_dump($count);exit;
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		//var_dump($models);exit;
	
		$this->render('detailindex',array(
				'models'=>$models,
				'model'=>$model,
				'stocks'=>$stocks,
				'pages'=>$pages,
				'papage'=>$papage,
				'name'=>$name
		));
	
	}

	public function actionStore(){
		$pid = Yii::app()->request->getParam('pid');
		//var_dump($pid);//exit;
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			$is_sync = DataSync::getInitSync();
			$stocks = array();
			$stocks = explode(';',$pid);
			foreach ($stocks as $stock){
				$sto = array();
				$sto = explode(',',$stock);
				$lid = $sto[0];
				$stockid = $sto[1];
				//var_dump($lid);var_dump($stockid);
				$db->createCommand('update nb_goods_delivery_details set pici = '.$stockid.',update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$lid)
				->execute();
				
			}
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
			//return true;
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			//return false;
		}
	}
	public function actionStockstore(){
		$pid = Yii::app()->request->getParam('pid');//订单lid编号
		$dpid = $this->companyId;
		$db = Yii::app()->db;
		$transaction = $db->beginTransaction();
		try
		{
			$is_sync = DataSync::getInitSync();
			
			$sql ='select gd.compid,gd.dpid as dpids,gd.goods_order_id,gd.goods_address_id,gd.goods_order_accountno,gd.pay_status,t.* from nb_goods_delivery_details t left join nb_goods_delivery gd on(gd.dpid=t.dpid and gd.lid = t.goods_delivery_id) where t.goods_delivery_id ='.$pid.' and t.delete_flag =0 group by t.pici';
			$modelstocks = $db->createCommand($sql)->queryAll();
			
			$sql ='select t.delivery_amount,gddp.reality_money from nb_goods_delivery t left join (select sum(gdd.price*gdd.num) as reality_money,gdd.goods_delivery_id from nb_goods_delivery_details gdd where gdd.goods_delivery_id = '.$pid.') gddp on(gddp.goods_delivery_id = t.lid) where t.lid ='.$pid.' and t.delete_flag =0 ';
			$gdprices = $db->createCommand($sql)->queryRow();
			//var_dump($modelstocks);exit;
			if((!empty($modelstocks))&&(!empty($gdprices))){
				$gdprice = $gdprices['delivery_amount'];
				$gdmeney = $gdprices['reality_money'];
				
				foreach ($modelstocks as $ms){
					$moneys = '';
					$gimoneys = '0.00';
					
					$gdoid = $ms['goods_order_id'];
					//按照仓库id生成发货单
					$se = new Sequence("goods_invoice");
					$gdlid = $se->nextval();
					$gc = new Sequence("goods_codes");
					$gdcode = $gc->nextval();
					$datagd = array(
							'lid'=>$gdlid,
							'dpid'=>$ms['dpids'],
							'compid'=>$ms['compid'],
							'create_at'=>date('Y-m-d H:i:s',time()),
							'update_at'=>date('Y-m-d H:i:s',time()),
							'goods_delivery_id'=>$pid,
							'goods_order_id'=>$ms['goods_order_id'],
							'goods_address_id'=>$ms['goods_address_id'],
							'goods_order_accountno'=>$ms['goods_order_accountno'],
							'invoice_accountno'=>Common::getCodes($dpid, $gdlid, $gdcode),
							'auditor'=>Yii::app()->user->username,
							'operators'=>'',
							'sent_personnel'=>'',
							'mobile'=>'',
							'status'=>'0',
							'invoice_amount'=>'',
							'pay_status'=>$ms['pay_status'],
							'remark'=>'',
							'delete_flag'=>'0',
							'is_sync'=>$is_sync,
					);
					//var_dump($datagd);//exit;
					$command = $db->createCommand()->insert('nb_goods_invoice',$datagd);
					if($command){
						$sql ='select t.* from nb_goods_delivery_details t where t.goods_delivery_id ='.$pid.' and t.delete_flag =0 and t.pici ='.$ms['pici'];
						$models = $db->createCommand($sql)->queryAll();
						foreach ($models as $m){
							
							$gimoneys = $gimoneys + $m['price']*$m['num'];
							//生成仓库发货单详情
							$se = new Sequence("goods_invoice_details");
							$gddlid = $se->nextval();
							
							$datagdd = array(
									'lid'=>$gddlid,
									'dpid'=>$ms['dpid'],
									'create_at'=>date('Y-m-d H:i:s',time()),
									'update_at'=>date('Y-m-d H:i:s',time()),
									'goods_invoice_id'=>$gdlid,
									'goods_id'=>$m['goods_id'],
									'goods_code'=>$m['goods_code'],
									'material_code'=>$m['material_code'],
									'price'=>$m['price'],
									'num'=>$m['num'],
									'remark'=>'',
									'delete_flag'=>'0',
									'is_sync'=>$is_sync,
							);
							$commands = $db->createCommand()->insert('nb_goods_invoice_details',$datagdd);
							//var_dump($datagdd);
						}
						
					}
					$moneys = $gimoneys/$gdmeney*$gdprice;
					$moneys = number_format($moneys,2);
					$db->createCommand('update nb_goods_invoice set invoice_amount = '.$moneys.',update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$gdlid)
					->execute();
					
				}
			}
			//exit;
			$db->createCommand('update nb_goods_order set order_status = "5",update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$gdoid)
			->execute();
			$db->createCommand('update nb_goods_delivery set status = "1",update_at ="'.date('Y-m-d H:i:s',time()).'" where lid ='.$pid)
			->execute();
			$transaction->commit();
			Yii::app()->end(json_encode(array("status"=>"success",'msg'=>'成功')));
		}catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			Yii::app()->end(json_encode(array("status"=>"fail")));
			return false;
		}
	}
	
}