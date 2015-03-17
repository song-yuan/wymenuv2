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
class Pinyin {
    //put your code here
       
    /*
     * 取得一个汉字字符串的首字母拼音
     */
    public function py($val)
    {
        $db = Yii::app()->db;
        $sql = "SELECT pinyin(:val)";
        $command=$db->createCommand($sql);
        $command->bindValue(":val" , $val);
	return $command->queryScalar();
    }
}
