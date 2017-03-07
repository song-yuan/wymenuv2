<?php

class WxMemberShop extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_wx_member_shop';
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
			
			
			'goods_img' => yii::t('app','商品图片'), 
			'price' => yii::t('app','价格'), 
			'goods_name' => yii::t('app','商品名称'), 
			'goods_category' => yii::t('app','商品类别'),
                        'stock' => yii::t('app','库存'),
                        'sale' => yii::t('app','总销量'),
                        'state' => yii::t('app','当前状态'),
                         'create_at' => yii::t('app','创建时间'),
                       
			'delete_flag' => yii::t('app','状态'),
			'is_sync' => yii::t('app','是否同步'),
		);
	}
}
