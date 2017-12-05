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
		$content = Yii::app()->request->getParam('content',0);
		if (is_numeric($content)) {
			if ($content) {
				$str =' and t.goods_order_accountno = '.$content;
			} else {
				$str = '';
			}
		}else{
			$str = '';
		}
		$db = Yii::app()->db;
		$sql = 'select k.* from (select c.company_name,t.* from nb_goods_delivery t left join nb_company c on(t.dpid = c.dpid) where t.dpid ='.$this->companyId.$str.') k order by status asc';

		$count = $db->createCommand(str_replace('k.*','count(*)',$sql))->queryScalar();
		$pages = new CPagination($count);
		$pdata =$db->createCommand($sql." LIMIT :offset,:limit");
		$pdata->bindValue(':offset', $pages->getCurrentPage()*$pages->getPageSize());
		$pdata->bindValue(':limit', $pages->getPageSize());//$pages->getLimit();
		$models = $pdata->queryAll();
		// p($models);
		$this->render('goodsdelivery',array(
				'models'=>$models,
				'pages'=>$pages,
				'content'=>$content,
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
				'name'=>$name,
				'goid'=>$goid,
		));

	}


	public function actionDetailindexExport(){
		$objPHPExcel = new PHPExcel();

		$goid = Yii::app()->request->getParam('goid');
		// p($goid);
		$db = Yii::app()->db;

		$sqls = 'select t.*,ga.*,ga.mobile as phone from nb_goods_delivery t left join nb_goods_address ga on(t.goods_address_id=ga.lid) where t.lid ='.$goid;


		// $sqls = 'select t.* from nb_goods_delivery t where t.lid ='.$goid;
		$model = $db->createCommand($sqls)->queryRow();

		$sqlstock = 'select t.* from nb_company t where t.type = 2 and t.comp_dpid ='.$this->companyId;
		$stocks = $db->createCommand($sqlstock)->queryAll();

		$sql = 'select k.* from (select c.goods_name,co.company_name as stock_name,t.* from nb_goods_delivery_details t left join nb_goods c on(t.goods_id = c.lid) left join nb_company co on(co.dpid = t.dpid ) where t.goods_delivery_id = '.$goid.' order by t.lid) k';
		$models = $db->createCommand($sql)->queryAll();
		// var_dump($models);exit;
		// p($model);

        //设置第1行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('1')->setRowHeight(30);
        //设置第2行的行高
        $objPHPExcel->getActiveSheet()->getRowDimension('2')->setRowHeight(30);
        $objPHPExcel->getActiveSheet()->getRowDimension('3')->setRowHeight(20);
        //设置字体
        $objPHPExcel->getDefaultStyle()->getFont()->setName('宋体');
        $objPHPExcel->getDefaultStyle()->getFont()->setSize(16);
        $styleArray1 = array(
                        'font' => array(
                                        'bold' => true,
                                        'color'=>array(
                                                        'rgb' => '000000',
                                        ),
                                        'size' => '20',
                        ),
                        'alignment' => array(
                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        ),
        );
        $styleArray2 = array(
                        'font' => array(
                                        'color'=>array(
                                                        'rgb' => 'ff0000',
                                        ),
                                        'size' => '16',
                        ),
                        'alignment' => array(
                                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                        ),
        );
        //大边框样式 边框加粗
        $lineBORDER = array(
                        'borders' => array(
                                        'outline' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THICK,
                                                        'color' => array('argb' => '000000'),
                                        ),
                        ),
        );
        //$objPHPExcel->getActiveSheet()->getStyle('A1:E'.$j)->applyFromArray($lineBORDER);
        //细边框样式
        $linestyle = array(
                        'borders' => array(
                                        'outline' => array(
                                                        'style' => PHPExcel_Style_Border::BORDER_THIN,
                                                        'color' => array('argb' => 'FF000000'),
                                        ),
                        ),
        );

        $objPHPExcel->setActiveSheetIndex(0)
        ->setCellValue('A1',yii::t('app','壹点吃餐饮管理系统仓库配货单'))
        ->setCellValue('A2',yii::t('app','配货单号:'.$model['delivery_accountno'].'--订单号:'.$model['goods_order_accountno']))
        ->setCellValue('A3',yii::t('app','总金额:'.$model['delivery_amount'].'--状态:'.($model['pay_status']?'已支付':'未支付')))
        ->setCellValue('A4',yii::t('app','收货地址:'.$model['pcc'].' '.$model['street']))
        ->setCellValue('A5',yii::t('app','收货人:'.$model['name'].'--联系电话:'.$model['phone']))
        ->setCellValue('A6',yii::t('app','货品名称'))
        ->setCellValue('B6',yii::t('app','价格'))
        ->setCellValue('C6',yii::t('app','数量'))
        ->setCellValue('D6',yii::t('app','批次'))
        ->setCellValue('E6',yii::t('app','发货仓库'));
        $j=7;
        if($models){
            foreach ($models as $key => $v) {
                $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A'.$j,$v['goods_name'])
                ->setCellValue('B'.$j,$v['price'])
                ->setCellValue('C'.$j,$v['num'])
                ->setCellValue('D'.$j,$v['pici'])
                ->setCellValue('E'.$j,$v['stock_name']);

                //细边框引用
                $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':E'.$j)->applyFromArray($linestyle);
                //设置字体靠左
                $objPHPExcel->getActiveSheet()->getStyle('A'.$j.':E'.$j)->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_LEFT);
                $j++;
            }
        }
        //冻结窗格
        $objPHPExcel->getActiveSheet()->freezePane('A7');
        //合并单元格
        $objPHPExcel->getActiveSheet()->mergeCells('A1:E1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:E2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:E3');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:E4');
        $objPHPExcel->getActiveSheet()->mergeCells('A5:E5');
        //单元格加粗，居中：
        // $objPHPExcel->getActiveSheet()->getStyle('A1:J'.$jj)->applyFromArray($lineBORDER);//大边框格式引用
        // 将A1单元格设置为加粗，居中
        $objPHPExcel->getActiveSheet()->getStyle('A1')->applyFromArray($styleArray1);
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A4:E4')->applyFromArray($linestyle);
        $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->applyFromArray($linestyle);
        //加粗字体
        $objPHPExcel->getActiveSheet()->getStyle('A2:E2')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A4:E4')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A5:E5')->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle('A6:E6')->getFont()->setBold(true);
        //设置字体垂直居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
        //设置字体水平居中
        $objPHPExcel->getActiveSheet()->getStyle('A3:E3')->getAlignment()->setVertical(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //设置每列宽度
        $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(15);
        $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(10);
        $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(20);
        $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(25);
        $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(25);
        //输出
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $filename="壹点吃餐饮管理系统仓库配货单---（".date('m-d',time())."）.xls";
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0');
        $objWriter->save('php://output');
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
			$gdmoneys = '0.00';
			
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
					$prices = $gdmeney;
					if(!$prices){
						$prices = 1;
					}
					$moneys = $gimoneys*$gdprice/$prices;
					$moneys = sprintf("%.2f",$moneys);
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