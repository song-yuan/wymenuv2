<?php
/**
 * 数据同步几大规则
 * 1：app的初始化，重要的事情提示三遍，初始化时，先检查有无数据还没有同步到云端，
 * 如果有就先同步，然后删除本地的所有的表结构及数据，最后从云端下载表结构及数据。
 * 2：下载数据同步，只同步基础数据，表结构不变化，先删除所有基础数据，然后下载
 * 3：日常数据同步，默认是自动打开的，也可以关闭。只要打开，就检查本地的所有的
 * 
 */
class DataAppSyncController extends Controller
{	
    
/**
 * 获取服务器端图片列表
*/
public function actionServerImglist(){
    $company_id = Yii::app()->request->getParam('companyId',0);
    $filesnames1 = scandir("uploads/company_".$company_id);
    $fnj=  json_encode($filesnames1);
    Yii::app()->end($fnj);
}
/**
 * 
 * 获取基础数据表
 * 
 */ 
 public function actionGetSyncBaseTables(){
   $baseTable = DataSyncOperation::getDataSyncBaseTables();
   echo json_encode($baseTable);exit;
}
/**
 * 
 * 获取全部数据表
 * 
 */ 
 public function actionGetSyncAllTables(){
   $allTable = DataSyncOperation::getDataSyncAllTables();
   echo json_encode($allTable);exit;
}
/**
 * 
 * 获取基础数据表 数据
 * 
 */ 
public function actionGetSyncTableData(){
   $dpid = Yii::app()->request->getParam('dpid',null);
   $tableName = Yii::app()->request->getParam('tn',null);
   $tableStruct = DataSyncOperation::getDataSyncData($dpid,$tableName);
   echo json_encode($tableStruct);exit;
}
/**
 * 初始化
 */
public function actionLocalInit(){
    
}

/**
 * 同步基础数据
 */
public function actionSyncBaseData(){
    
}

/**
 * 获取表结构
 */
public function actionGetTableStructure(){
    
}

public function actionOperation()
{
    
}
}