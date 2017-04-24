<?php
class CopyScreenController extends BackendController
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
    public function actionIndex(){
            $dpid = $this->companyId;
            $models = DoubleScreen::model()->findAll('dpid=:dpid and delete_flag=0 ',array(':dpid'=>$dpid));

            $db = Yii::app()->db;
            $sql = 'select dpid,company_name from nb_company where delete_flag = 0 and type = 1 and comp_dpid = '.$this->companyId;
            $command = $db->createCommand($sql);
            $dpids = $command->queryAll();

            $this->render('index',array(
                            'models'=>$models,
                            'dpids'=>$dpids,)
                        );
    }
        
    public function actionStorProduct(){
            //$companyId总部的dpid
            $companyId = Helper::getCompanyId(Yii::app()->request->getParam('companyId'));
            $is_sync = DataSync::getInitSync();
            //$ids总部选择的双屏lid
            $ids = Yii::app()->request->getPost('ids');
            //$lid总部选择的双屏lid
            $lid = Yii::app()->request->getParam('lid');
            //$dpid要下发的店铺
            $dpid = Yii::app()->request->getParam('dpids');

            $lids = array();
            $lids = explode(',',$lid);
            $dpids = array();
            $dpids = explode(',',$dpid);

            $db = Yii::app()->db;
            if(!empty($dpids)){
                $transaction = $db->beginTransaction();
        	try{ 
                    foreach ($dpids as $dpid){

                        foreach ($lids as $lid){
                            $sreen_hq = DoubleScreen::model()->find('lid =:lid and dpid=:dpid and delete_flag=0 ',array(':dpid'=>$companyId,':lid'=>$lid));
                            if(empty($sreen_hq['phs_code'])){
                                    $sreen_code=new Sequence("phs_code");
                                    $sreen_phs_code = $printer_code->nextval();
                                    $sreen_hq['phs_code'] = ProductCategory::getChscode($companyId,$sreen_hq['lid'] , $sreen_phs_code);   
                                    $sreen_hq->update();

                            } 
                            $branch_exist = DoubleScreen::model()->find('phs_code =:phs_code and dpid=:dpid and delete_flag=0 ',array(':dpid'=>$dpid,':phs_code'=>$sreen_hq['phs_code']));

                            if(!empty($branch_exist)){
                                $branch_exist->delete_flag = 1;
                                $branch_exist->update();
                                Yii::app()->db->createCommand('update nb_double_screen_detail set delete_flag=1 where double_screen_id =:dsid and dpid = :dpid')
                                ->execute(array(':dsid'=> $branch_exist->lid, ':dpid' => $dpid)); 
                            }       

                            if(!empty($sreen_hq)){
                                $se = new Sequence("double_screen");
                                $branch_lid = $se->nextval();
                                $branch_data = array(
                                        'lid'=>$branch_lid,
                                        'dpid'=>$dpid,
                                        'create_at'=>date('Y-m-d H:i:s',time()),
                                        'update_at'=>date('Y-m-d H:i:s',time()),
                                        'phs_code'=>$sreen_hq['phs_code'],
                                        'source'=>'1',
                                        'title'=>$sreen_hq['title'],
                                        'type' =>$sreen_hq['type'], 
                                        'desc' => $sreen_hq['desc'],
                                        'is_able' =>$sreen_hq['is_able'], 
                                        'delete_flag' => $sreen_hq['delete_flag'],
                                        'is_sync' => $sreen_hq['is_sync'],
                                    );                    
        
                                $branch = Yii::app()->db->createCommand()->insert('nb_double_screen',$branch_data);                
                                               		
                                $sreen_detail_hqs = DoubleScreenDetail::model()->findAll('double_screen_id =:double_screen_id and dpid=:dpid and delete_flag=0 ',array(':double_screen_id'=>$sreen_hq['lid'],':dpid'=>$companyId));

                                if(!empty($sreen_detail_hqs)){
                                    foreach ($sreen_detail_hqs as $sreen_detail_hq){
                                        $se = new Sequence("double_screen_detail");
                                        $branch_detail_lid = $se->nextval();
                                        $branch_detail_data = array(
                                                'lid'=>$branch_detail_lid,
                                                'dpid'=>$dpid,
                                                'create_at'=>date('Y-m-d H:i:s',time()),
                                                'update_at'=>date('Y-m-d H:i:s',time()),
                                                'double_screen_id'=>$branch_lid,
                                                'type' =>$sreen_detail_hq['type'], 
                                                'url' => $sreen_detail_hq['url'],      
                                                'delete_flag' => $sreen_detail_hq['delete_flag'],
                                                'is_sync' => $sreen_detail_hq['is_sync'],

                                        );                           
                                        $branch_detail = Yii::app()->db->createCommand()->insert('nb_double_screen_detail',$branch_detail_data);                                            
                                    } 
                                }                                                                
                            }                     
                        }
                    }
                    $transaction->commit();       		
                    Yii::app()->user->setFlash('success' , yii::t('app','双屏下发成功！！！'));
                    $this->redirect(array('copyScreen/index' , 'companyId' => $companyId)) ;       		
                }catch (Exception $e){
        		$transaction->rollback();
        		Yii::app()->user->setFlash('eror' , yii::t('app','双屏下发失败！！！'));
        		$this->redirect(array('copyScreen/index' , 'companyId' => $companyId)) ;
        	} 
            }
    }
}
