<?php

class WxPoint extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_wx_point';
	}
        
        public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        /**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                        'is_available' => yii::t('app','功能状态'),
			'award_rule' => yii::t('app','奖励规则'),
			'award_scope' => yii::t('app','奖励范围'), 
			'deadline' => yii::t('app','有效期'), 
                        'use_point' => yii::t('app','消耗积分'), 
			'limit_comment' => yii::t('app','限制与说明'), 
			'create_at' => yii::t('app','创建时间'),
                        'update_at' => yii::t('app','创建时间'),             
			'delete_flag' => yii::t('app','状态'),
			'is_sync' => yii::t('app','是否同步'),
		);
	}
}


