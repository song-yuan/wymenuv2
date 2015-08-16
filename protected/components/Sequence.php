<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Sequence
 *
 * @author Administrator
 */
class Sequence {
    //put your code here
    private $table;
    public function __construct($tab){
        $this->table = $tab;
    }    
    
    /*
     * 设定对应表的lid增长的起步值，即初始值
     */
    public function setval($val)
    {
        $db = Yii::app()->db;
        $sql = "SELECT SETVAL(:table, :val)";
        $command=$db->createCommand($sql);
        $command->bindValue(":table" , $this->table);
        $command->bindValue(":val" , $val);
	return $command->execute();
    }
    
    /*
     * 查看当前表的最大lid值，
     * 请注意这个方法只能查看，
     * 不能用该方法取得最大值后+1做为自己的新数据的lid，必须用nextval的
     * 返回值做为自己新插入数据的lid
     * 云端的lid都是双数 0
     * 本地的lid都是单数 1
     */
    public function currval()
    {
        $db = Yii::app()->db;
        $sql = "SELECT CURRVAL(:table)";
        $command=$db->createCommand($sql);
        $command->bindValue(":table" , $this->table);
	$nowval= $command->queryScalar();
        return $nowval;
    }
    
    /*
     * *****************************
     * 新插入数据的lid，必须用该方法获取
     * 云端的lid都是双数
     * 本地的lid都是单数
     * ******************************
     */
    public function nextval()
    {
        $db = Yii::app()->db;
        //var_dump(Yii::app()->params['dbcloud']);exit;
        $sql = "SELECT NEXTVAL(:table)";
        $command=$db->createCommand($sql);
        $command->bindValue(":table" , $this->table);
	$nowval= $command->queryScalar();
        if(Yii::app()->params->cloud_local=="c")
        {
            if($nowval%2==1)
            {
                $nowval++;
                $this->setval($nowval);
            }            
        }else{
            if($nowval%2==0)
            {
                $nowval++;
                $this->setval($nowval);
            }
        }
        return $nowval;
    }
}
