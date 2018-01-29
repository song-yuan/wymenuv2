<?php
class CopyPrinterController extends BackendController
{
    public function beforeAction($action) {
            parent::beforeAction($action);
            if(!$this->companyId) {
                    Yii::app()->user->setFlash('error' , yii::t('app','请选择公司'));
                    $this->redirect(array('company/index'));
            }
            return true;
    }
    public function actionIndex(){
        $printers = Printer::model()->findAll('t.dpid='.$this->companyId .' and delete_flag=0');
        $printer_ways = PrinterWay::model()->findAll('t.dpid='.$this->companyId .' and delete_flag=0');
        $db = Yii::app()->db;
        $sql = 'select dpid,company_name from nb_company where delete_flag = 0 and type = 1 and comp_dpid = '.$this->companyId;
        $dpids = $db->createCommand($sql)->queryAll();

        // //打印机设置
        // $criteria = new CDbCriteria;
        // $criteria->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
        // $models = Printer::model()->findAll($criteria);

        $criteriaa = new CDbCriteria;
        $criteriaa->condition =  't.dpid='.$this->companyId .' and delete_flag=0';
        $modelss = PrinterWay::model()->findAll($criteriaa);
        $this->render('index',array(
                // 'models'=>$models,
                'modelss'=>$modelss,
                'printers'=>$printers,
                'printer_ways'=>$printer_ways,
                'dpids'=>$dpids,
        ));
    }

    public function actionStorPrinter(){
        //$companyId总部的dpid
        $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
        $is_sync = DataSync::getInitSync();

        $dpid = Yii::app()->request->getParam('dpids');
        $down_type = Yii::app()->request->getParam('down_type');

        $printer_way_ids = Yii::app()->request->getParam('printer_way_ids');

        $dpids = explode(',',$dpid);
        

        $lid_str=implode(",",$printer_way_ids);

        $db = Yii::app()->db;
        if(!empty($dpids)){
            $printer_hqs = Printer::model()->findAll('dpid=:dpid and delete_flag=0 ',array(':dpid'=>$companyId));
            //补充打印机设置的唯一吗
            if(!empty($printer_hqs)){
                foreach($printer_hqs as $printer_hq){
                    if(empty($printer_hq['phs_code'])){
                        $printer_code=new Sequence("phs_code");
                        $printer_phs_code = $printer_code->nextval();
                        $printer_hq['phs_code'] = ProductCategory::getChscode($companyId,$printer_hq['lid'] , $printer_phs_code);
                        $printer_hq->update();
                    }
                }
            }
            $printer_way_hqs = PrinterWay::model()->findAll('dpid=:dpid and delete_flag=0 ',array(':dpid'=>$companyId));
            //补充厨打方案设置的唯一吗
             if(!empty($printer_way_hqs)){
                foreach ($printer_way_hqs as $printer_way_hq){
                    if(empty($printer_way_hq['phs_code'])){
                            $way_code=new Sequence("phs_code");
                            $way_phs_code = $printer_code->nextval();
                            $printer_way_hq['phs_code'] = ProductCategory::getChscode($companyId,$printer_way_hq['lid'] , $way_phs_code);
                            $printer_way_hq->update();
                    }
                }
            }

            //查询厨打方案
            $sql = 'select * from nb_printer_way where dpid='.$companyId.' and delete_flag=0 and lid in ('.$lid_str.')';
            $printer_way_hqs = $db->createCommand($sql)->queryAll();
            $transaction = $db->beginTransaction();
        	try{
                foreach ($dpids as $dpid){

                    //总部打印机方案设置存在就下发
                    //  店铺里存在就删除方案和详情
                    //  之后在插入
                    if(!empty($printer_way_hqs)){
                        foreach ($printer_way_hqs as $printer_way_hq){
                            $way_branch_exist = PrinterWay::model()->find('phs_code =:phs_code and dpid=:dpid and delete_flag=0 ',array(':dpid'=>$dpid,':phs_code'=>$printer_way_hq['phs_code']));

                            if(!empty($way_branch_exist)){
                                if ($down_type) {
                                    $way_branch_exist->delete_flag = 1;
                                    $way_branch_exist->update();
                                    $db->createCommand('update nb_printer_way_detail set delete_flag=1 where print_way_id =:print_way_id and dpid = :dpid')
                                    ->execute(array(':print_way_id'=> $way_branch_exist->lid, ':dpid' => $dpid));
                                    $se = new Sequence("printer_way");
                                    $way_branch_lid = $se->nextval();
                                    $way_branch_data = array(
                                        'lid'=>$way_branch_lid,
                                        'dpid'=>$dpid,
                                        'create_at'=>date('Y-m-d H:i:s',time()),
                                        'update_at'=>date('Y-m-d H:i:s',time()),
                                        'phs_code'=>$printer_way_hq['phs_code'],
                                        'source'=>'1',
                                        'name'=>$printer_way_hq['name'],
                                        'is_onepaper' =>$printer_way_hq['is_onepaper'],
                                        'list_no' => $printer_way_hq['list_no'],
                                        'memo' =>$printer_way_hq['memo'],
                                        'delete_flag' => $printer_way_hq['delete_flag'],
                                        'is_sync' => $printer_way_hq['is_sync'],
                                    );
                                    $way_branch = $db->createCommand()->insert('nb_printer_way',$way_branch_data);

                                    $way_detail_hqs = PrinterWayDetail::model()->findAll('print_way_id =:print_way_id and dpid=:dpid and delete_flag=0 ',array(':print_way_id'=>$printer_way_hq['lid'],':dpid'=>$companyId));

                                    $printer_ids=array();

                                    if(!empty($way_detail_hqs)){

                                        foreach ($way_detail_hqs as $way_detail_hq){
                                            array_push($printer_ids, $way_detail_hq['printer_id']);
                                        }

                                        $printer_ids = array_unique($printer_ids);
                                        $way_lid_str=implode(",",$printer_ids);
                                        //查询打印机设置
                                        $sql = 'select * from nb_printer where dpid='.$companyId.' and delete_flag=0 and lid in ('.$way_lid_str.')';
                                        $printer_hqs = $db->createCommand($sql)->queryAll();
                                        
                                        //总部打印机设置存在就下发
                                        //  店铺里存在就更新
                                        //  不存在就插入
                                        //  判断$down_type
                                        if(!empty($printer_hqs)){
                                            foreach ($printer_hqs as $printer_hq){
                                                $printer_branch_exist = Printer::model()->find('phs_code =:phs_code and dpid=:dpid and delete_flag=0 ',array(':dpid'=>$dpid,':phs_code'=>$printer_hq['phs_code']));
                                                if(!empty($printer_branch_exist)){
                                                    if ($down_type) {
                                                        $printer_branch_exist->update_at = date('Y-m-d H:i:s',time());
                                                        $printer_branch_exist->name = $printer_hq['name'];
                                                        $printer_branch_exist->address = $printer_hq['address'];
                                                        $printer_branch_exist->language = $printer_hq['language'];
                                                        $printer_branch_exist->brand = $printer_hq['brand'];
                                                        $printer_branch_exist->remark = $printer_hq['remark'];
                                                        $printer_branch_exist->width_type = $printer_hq['width_type'];
                                                        $printer_branch_exist->printer_type = $printer_hq['printer_type'];
                                                        $printer_branch_exist->is_sync = $printer_hq['is_sync'];
                                                        $printer_branch_exist->update();
                                                    }
                                                }else{
                                                    $se = new Sequence("printer");
                                                    $branch_lid = $se->nextval();
                                                    $branch_data = array(
                                                        'lid'=>$branch_lid,
                                                        'dpid'=>$dpid,
                                                        'create_at'=>date('Y-m-d H:i:s',time()),
                                                        'update_at'=>date('Y-m-d H:i:s',time()),
                                                        'phs_code'=>$printer_hq['phs_code'],
                                                        'source'=>'1',
                                                        'name'=>$printer_hq['name'],
                                                        'address' =>$printer_hq['address'],
                                                        'language' => $printer_hq['language'],
                                                        'brand' =>$printer_hq['brand'],
                                                        'remark' =>$printer_hq['remark'],
                                                        'printer_type' =>$printer_hq['printer_type'],
                                                        'width_type' =>$printer_hq['width_type'],
                                                        'delete_flag' => $printer_hq['delete_flag'],
                                                        'is_sync' => $printer_hq['is_sync'],
                                                    );
                                                    $branch = $db->createCommand()->insert('nb_printer',$branch_data);
                                                }
                                            }
                                        }
                                        foreach ($way_detail_hqs as $way_detail_hq){
                                            $phs_code_sql = 'select phs_code from nb_printer where delete_flag = 0 and lid ='.$way_detail_hq['printer_id'].' and dpid = '.$companyId;
                                            $phs_code = $db->createCommand($phs_code_sql)->queryRow();
                                            $lid_sql = 'select lid from nb_printer  where delete_flag = 0 and phs_code ='.$phs_code['phs_code'].' and dpid = '.$dpid;
                                            $lid = $db->createCommand($lid_sql)->queryRow();
                                            $se = new Sequence("printer_way_detail");
                                            $way_detail_lid = $se->nextval();
                                            $way_detail_data = array(
                                                'lid'=>$way_detail_lid,
                                                'dpid'=>$dpid,
                                                'create_at'=>date('Y-m-d H:i:s',time()),
                                                'update_at'=>date('Y-m-d H:i:s',time()),
                                                'print_way_id'=>$way_branch_lid,
                                                'floor_id' =>$way_detail_hq['floor_id'],
                                                'printer_id' =>$lid['lid'],
                                                'list_no' => $way_detail_hq['list_no'],
                                                'delete_flag' => $way_detail_hq['delete_flag'],
                                                'is_sync' => $way_detail_hq['is_sync'],
                                            );
                                            $way_detail = $db->createCommand()->insert('nb_printer_way_detail',$way_detail_data);

                                        }
                                    }

                                }
                            }else{
                                $se = new Sequence("printer_way");
                                $way_branch_lid = $se->nextval();
                                $way_branch_data = array(
                                    'lid'=>$way_branch_lid,
                                    'dpid'=>$dpid,
                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                    'update_at'=>date('Y-m-d H:i:s',time()),
                                    'phs_code'=>$printer_way_hq['phs_code'],
                                    'source'=>'1',
                                    'name'=>$printer_way_hq['name'],
                                    'is_onepaper' =>$printer_way_hq['is_onepaper'],
                                    'list_no' => $printer_way_hq['list_no'],
                                    'memo' =>$printer_way_hq['memo'],
                                    'delete_flag' => $printer_way_hq['delete_flag'],
                                    'is_sync' => $printer_way_hq['is_sync'],
                                );
                                $way_branch = $db->createCommand()->insert('nb_printer_way',$way_branch_data);

                                $way_detail_hqs = PrinterWayDetail::model()->findAll('print_way_id =:print_way_id and dpid=:dpid and delete_flag=0 ',array(':print_way_id'=>$printer_way_hq['lid'],':dpid'=>$companyId));

                                $printer_ids=array();

                                if(!empty($way_detail_hqs)){
                                    foreach ($way_detail_hqs as $way_detail_hq){
                                        array_push($printer_ids, $way_detail_hq['printer_id']);
                                    }
                                    $printer_ids = array_unique($printer_ids);
                                    $way_lid_str=implode(",",$printer_ids);
                                    //查询打印机设置
                                    $sql = 'select * from nb_printer where dpid='.$companyId.' and delete_flag=0 and lid in ('.$way_lid_str.')';
                                    $printer_hqs = $db->createCommand($sql)->queryAll();
                                    
                                    //总部打印机设置存在就下发
                                    //  店铺里存在就更新
                                    //  不存在就插入
                                    //  判断$down_type
                                    if(!empty($printer_hqs)){
                                        foreach ($printer_hqs as $printer_hq){
                                            $printer_branch_exist = Printer::model()->find('phs_code =:phs_code and dpid=:dpid and delete_flag=0 ',array(':dpid'=>$dpid,':phs_code'=>$printer_hq['phs_code']));
                                            if(!empty($printer_branch_exist)){
                                                if ($down_type) {
                                                    $printer_branch_exist->update_at = date('Y-m-d H:i:s',time());
                                                    $printer_branch_exist->name = $printer_hq['name'];
                                                    $printer_branch_exist->address = $printer_hq['address'];
                                                    $printer_branch_exist->language = $printer_hq['language'];
                                                    $printer_branch_exist->brand = $printer_hq['brand'];
                                                    $printer_branch_exist->remark = $printer_hq['remark'];
                                                    $printer_branch_exist->width_type = $printer_hq['width_type'];
                                                    $printer_branch_exist->printer_type = $printer_hq['printer_type'];
                                                    $printer_branch_exist->is_sync = $printer_hq['is_sync'];
                                                    $printer_branch_exist->update();
                                                }
                                            }else{
                                                $se = new Sequence("printer");
                                                $branch_lid = $se->nextval();
                                                $branch_data = array(
                                                    'lid'=>$branch_lid,
                                                    'dpid'=>$dpid,
                                                    'create_at'=>date('Y-m-d H:i:s',time()),
                                                    'update_at'=>date('Y-m-d H:i:s',time()),
                                                    'phs_code'=>$printer_hq['phs_code'],
                                                    'source'=>'1',
                                                    'name'=>$printer_hq['name'],
                                                    'address' =>$printer_hq['address'],
                                                    'language' => $printer_hq['language'],
                                                    'brand' =>$printer_hq['brand'],
                                                    'remark' =>$printer_hq['remark'],
                                                    'printer_type' =>$printer_hq['printer_type'],
                                                    'width_type' =>$printer_hq['width_type'],
                                                    'delete_flag' => $printer_hq['delete_flag'],
                                                    'is_sync' => $printer_hq['is_sync'],
                                                );
                                                $branch = $db->createCommand()->insert('nb_printer',$branch_data);
                                            }
                                        }
                                    }
                                    foreach ($way_detail_hqs as $way_detail_hq){
                                        $phs_code_sql = 'select phs_code from nb_printer where delete_flag = 0 and lid ='.$way_detail_hq['printer_id'].' and dpid = '.$companyId;
                                        $phs_code = $db->createCommand($phs_code_sql)->queryRow();

                                        $lid_sql = 'select lid from nb_printer  where delete_flag = 0 and phs_code ='.$phs_code['phs_code'].' and dpid = '.$dpid;
                                        $lid = $db->createCommand($lid_sql)->queryRow();
                                        $se = new Sequence("printer_way_detail");
                                        $way_detail_lid = $se->nextval();
                                        $way_detail_data = array(
                                            'lid'=>$way_detail_lid,
                                            'dpid'=>$dpid,
                                            'create_at'=>date('Y-m-d H:i:s',time()),
                                            'update_at'=>date('Y-m-d H:i:s',time()),
                                            'print_way_id'=>$way_branch_lid,
                                            'floor_id' =>$way_detail_hq['floor_id'],
                                            'printer_id' =>$lid['lid'],
                                            'list_no' => $way_detail_hq['list_no'],
                                            'delete_flag' => $way_detail_hq['delete_flag'],
                                            'is_sync' => $way_detail_hq['is_sync'],
                                        );
                                        $way_detail = $db->createCommand()->insert('nb_printer_way_detail',$way_detail_data);

                                    }
                                }
                            }
                        }
                    }
                }
                $transaction->commit();
                Yii::app()->user->setFlash('success' , yii::t('app','一键下发成功！！！'));
                $this->redirect(array('CopyPrinter/index' , 'companyId' => $companyId)) ;
            }catch (Exception $e){
    		$transaction->rollback();
    		Yii::app()->user->setFlash('error' , yii::t('app','一键下发失败,厨打方案所需的打印机设置或被删除 ,数据异常！！！'));
    		$this->redirect(array('CopyPrinter/index' , 'companyId' => $companyId)) ;
    	}
    }
}
}
