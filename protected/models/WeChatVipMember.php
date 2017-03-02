<?php

class WeChatVipMember extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_wechat_vip_member';
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
			
			'vip_name' => yii::t('app','会员名称'),
			'vip_card_img' => yii::t('app','卡面专属'), 
			'privileg_name' => yii::t('app','特权名称'), 
			'privileg_comment' => yii::t('app','限制与说明'), 
			'create_at' => yii::t('app','创建时间'),
                        'update_at' => yii::t('app','创建时间'),
                        'is_available' => yii::t('app','功能状态'),
			'delete_flag' => yii::t('app','状态'),
			'is_sync' => yii::t('app','是否同步'),
		);
	}
}

