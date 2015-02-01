<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 *
 * @author Administrator
 */
class SMsg {
    
    private $dpid;
    private $lid;
    private function __construct($tdpid){
        $this->dpid = $tdpid;
    }
    
    //put your code here
    public function saveCmdData($cmd,$data)
   {
        $ds=new DataSync;
        $se=new Sequence("data_sync");
        $this->lid = $se->nextval();
        $ds->dpid =  $this->dpid;
        $ds->lid = $this->lid;
        $ds->cmd_code = $cmd;
        $ds->cmd_data =$data;
        $ds->create_at = date('y-m-d h:i:s',time());
        $ds->is_interface = '1';
        $ds->sync_result = '0';
        $ds->save();
   }
   
   public function updateResult($resu,$cmd)
   {
        $ds=DataSync::model()->findByPk(array('dpid'=>  $this->dpid ,'lid'=>  $this->lid,'cmd_code'=>$cmd));
        $ds->sync_result = $resu;
        $ds->update();
   }
    
    /**
     * 开台
     * datalist数据格式参见接口文档
     * 处理，将数据出入数据库
     * 返回1成功，0 失败
     */
    public function KT($datalist)
   {
           return 1;
   }
   
   /**
     * 换台
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function HT($datalist)
   {
           return 1;
   }
   
   /**
     * 并台
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function BT($datalist)
   {
           return 1;
   }
   
   /**
     * 撤台
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function CT($datalist)
   {
           return 1;
   }
   
   /**
     * 退菜
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function TC($datalist)
   {
           return 1;
   }
   
   /**
     * 勾挑
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function GT($datalist)
   {
           return 1;
   }
   
   /**
     * 重量确认
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function ZLQR($datalist)
   {
           return 1;
   }
   
   /**
     * 增菜
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function ZC($datalist)
   {
           return 1;
   }
   
   /**
     * 结单完成
     * datalist数据格式参见接口文档
     * 返回1成功，0 失败
     */
    public function JDWC($datalist)
   {
           return 1;
   }
   
   
}
