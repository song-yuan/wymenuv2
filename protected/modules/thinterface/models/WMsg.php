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
class WMsg {
    
    private $dpid;
    private $lid;
    public function __construct($tdpid){
        $this->dpid = intval($tdpid);
    }
    
    //put your code here
    /**
     * 被产生XD 的Wn消息的地方触发调用。
     * 在下单的地方触发该消息
     * data的格式按照接口文档封装
     */
    public function XD($data)
   {
        $this->saveMsg('XD', $data);
   }
   
   /**
     * 被产生JC的Wn消息的地方触发调用。
     * 在加菜的地方触发该消息
     * data的格式按照接口文档封装
     */
   public function JC($data)
   {
           $this->saveMsg('JC', $data);
   }
   
   /**
     * 被产生ZXZF的Wn消息的地方触发调用。
     * 在在线支付的地方触发该消息
     * data的格式按照接口文档封装
     */
   public function ZXZF($data)
   {
           $this->saveMsg('ZXZF', $data);
   }
   
   /**
     * 被产生XXFK的Wn消息的地方触发调用。
     * 在信息反馈的地方触发该消息
     * data的格式按照接口文档封装
     */
   public function XXFK($data)
   {
           $this->saveMsg('XXFK', $data);
   }
    
    /**
     * 被下单、加菜、在线支付、信息反馈等调用
     * 对应的cmd分别是XD、JC、ZXZF、XXFK
     * data的格式按照接口文档封装
     */
    protected function saveMsg($cmd,$data)
   {
        $db = Yii::app()->db;
        $se=new Sequence("data_sync");
        $this->lid = $se->nextval();
        $sql='insert into nb_data_sync(lid,dpid,cmd_code,cmd_data,create_at,is_interface,sync_result) values(:lid,:dpid,:cmd_code,:cmd_data,sysdate(),:is_interface,:sync_result)';
        $command=$db->createCommand($sql);
        $command->bindValue(":lid" , $this->lid);
        $command->bindValue(":dpid" , $this->dpid);
        $command->bindValue(":cmd_code" , $cmd);
        $command->bindValue(":cmd_data" , $data);
        $command->bindValue(":is_interface" , '1');
        $command->bindValue(":sync_result" , '0');
        $command->execute();
        
        /*$ds=new DataSync;
        $se=new Sequence("data_sync");
        $this->lid = $se->nextval();
        $ds->dpid =  $this->dpid;
        $ds->lid = $this->lid;
        $ds->cmd_code = $cmd;
        $ds->cmd_data =$data;
        $ds->create_at = date('y-m-d h:i:s',time());
        $ds->is_interface = '1';
        $ds->sync_result = '0';
        $ds->save();*/
   }
   
   /**
     * 获取最新的Wn消息的指令、指令序列及数据
     * 返回格式参见接口文档
     * 没有消息就返回字符串"no"
     */
   public function getMsg()
   {
           //select top 1 cmd in ('XD','JC','ZXZF','XXFK') and lid >$lid orde by create_time
       $sql="select lid,cmd_code,cmd_data from nb_data_sync where cmd_code in ('XD','JC','ZXZF','XXFK') and dpid=:dpid and sync_result=0 order by create_at";
       $db = Yii::app()->db;
       $command=$db->createCommand($sql);
       //$command->bindValue(":lid" , $lid);
       $command->bindValue(":dpid" , $this->dpid);
       $row=$command->queryRow();
       return $row['cmd_code'].'$#S#$'.$row['lid'].'$#C#$'.$row['cmd_data'];
   }
   
   /**
     * 存储最新的Wn消息的执行结果
     * 成功或失败更新数据库
     */
   public function updateResult($cmd,$lid,$resu)
   {
        $ds=DataSync::model()->findByPk(array('dpid'=>  $this->dpid ,'lid'=>  intval($lid),'cmd_code'=>$cmd));
        $ds->sync_result = $resu;
        $ds->update();
   }
}
