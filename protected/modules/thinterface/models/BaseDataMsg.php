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
class BaseDataMsg {
    //put your code here
    private $dpid;
    private $lid;
    public function __construct($tdpid){
        $this->dpid = intval($tdpid);
        $this->lid=0;
    }
    
    public function saveCmd($cmd)
   {
        //$ds=new DataSync();
        $db = Yii::app()->db;
        $se=new Sequence("data_sync");
        $this->lid = $se->nextval();
        $sql='insert into nb_data_sync(lid,dpid,cmd_code,cmd_data,create_at,is_interface,sync_result) values(:lid,:dpid,:cmd_code,:cmd_data,sysdate(),:is_interface,:sync_result)';
        $command=$db->createCommand($sql);
        $command->bindValue(":lid" , $this->lid);
        $command->bindValue(":dpid" , $this->dpid);
        $command->bindValue(":cmd_code" , $cmd);
        $command->bindValue(":cmd_data" , '');
        $command->bindValue(":is_interface" , '1');
        $command->bindValue(":sync_result" , '0');
        $command->execute();
        /*
        $ds->dpid =  $this->dpid;
        $ds->lid = $this->lid;
        $ds->cmd_code = $cmd;
        $ds->cmd_data = '';
        $ds->create_at = date('y-m-d h:i:s',time());
        $ds->is_interface = '1';
        $ds->sync_result = '0';
        //var_dump($ds);
        if(!$ds->save())
        {
            var_dump($ds->getErrors());
            echo 'insert error!!!!';
        }*/
   }
   
   public function updateResult($cmd,$resu)
   {
        $ds=DataSync::model()->findByPk(array('dpid'=>  $this->dpid ,'lid'=>  $this->lid,'cmd_code'=>$cmd));
        $ds->sync_result = $resu;
        $ds->update();
   }
    
    /**
     * 下载菜品类别
     * datalist格式参见接口文档中相应命令的数据部分
     * 操作：将数据解析后插入数据库或更新数据库内内容，成功返回1，失败返回0
     * return 1 success 0 fail
     */
   public function CPLB($datalist)
   {
        return 1;
   }
   
   public function XZCP($datalist)
   {
           return 22;
   }
   
   public function XZTC($datalist)
   {
           return 23;
   }
   
   public function TCNR($datalist)
   {
           return 23;
   }
   
   public function XZQY($datalist)
   {
           return 23;
   }
   
   public function KWZF($datalist)
   {
           return 23;
   }
   
   public function CZBX($datalist)
   {
           return 23;
   }
   
   public function FKXX($datalist)
   {
           return 23;
   }
   
   public function DPKW($datalist)
   {
           return 23;
   }
   
   public function GQLB($datalist)
   {
           return 23;
   }
   
   public function SJLB($datalist)
   {
           return 23;
   }
   
   public function YHHD($datalist)
   {
           return 23;
   }
   
   public function TJLB($datalist)
   {
           return 23;
   }
}
