<?php
/**
 * 美团外卖 开放平台
 * 接口
 */
class MeituanController extends BackendController
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
	public function actionIndex(){
		$companyId = Yii::app()->request->getParam('companyId');
		$models = MeituanDpinfo::model()->findAll('dpid=:dpid and delete_flag=0',array(':dpid'=>$companyId,':type'=>'1'));
		$this->render('index',array(
			'companyId'=>$companyId,
			'models'=>$models
			));
	}
}
?>