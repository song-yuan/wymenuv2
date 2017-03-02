<?php

class WxMemberSource extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_wx_member_source';
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
			
			
			'qrcode' => yii::t('app','二维码'), 
			'channel_name' => yii::t('app','渠道'), 
			'channel_comment' => yii::t('app','渠道说明'), 
			'create_at' => yii::t('app','创建时间'),
                        'update_at' => yii::t('app','更新时间'),
                        
			'delete_flag' => yii::t('app','状态'),
			'is_sync' => yii::t('app','是否同步'),
		);
	}
}


