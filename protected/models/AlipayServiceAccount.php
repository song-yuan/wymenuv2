<?php

/**
 * This is the model class for table "nb_alipay_service_account".
 *
 * The followings are the available columns in table 'nb_alipay_service_account':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $appid
 * @property string $partner
 * @property string $seller_id
 * @property string $alipay_public_key
 * @property string $merchant_private_key
 * @property string $store_id
 * @property string $alipay_store_id
 * @property string $alipay_public_key_file
 * @property string $merchant_private_key_file
 * @property string $merchant_public_key_file
 * @property string $is_sync
 */
class AlipayServiceAccount extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_alipay_service_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, appid, seller_id, merchant_private_key', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
			array('appid, store_id, alipay_store_id', 'length', 'max'=>32),
			array('partner', 'length', 'max'=>16),
			array('seller_id', 'length', 'max'=>28),
			array('alipay_public_key_file, merchant_private_key_file, merchant_public_key_file', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, alipay_public_key', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, appid, partner, seller_id, alipay_public_key, merchant_private_key, store_id, alipay_store_id, alipay_public_key_file, merchant_private_key_file, merchant_public_key_file, is_sync', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'appid' => '支付宝公众平台应用id',
			'partner' => '合作身份者id',
			'seller_id' => '收款支付宝账号',
			'alipay_public_key' => '支付宝公钥',
			'merchant_private_key' => '商户私钥',
			'store_id' => '商户门店编号',
			'alipay_store_id' => '支付宝的店铺编号',
			'alipay_public_key_file' => '支付宝公钥证书',
			'merchant_private_key_file' => '商户私钥证书',
			'merchant_public_key_file' => '商户公钥证书',
			'is_sync' => '同步标志',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('appid',$this->appid,true);
		$criteria->compare('partner',$this->partner,true);
		$criteria->compare('seller_id',$this->seller_id,true);
		$criteria->compare('alipay_public_key',$this->alipay_public_key,true);
		$criteria->compare('merchant_private_key',$this->merchant_private_key,true);
		$criteria->compare('store_id',$this->store_id,true);
		$criteria->compare('alipay_store_id',$this->alipay_store_id,true);
		$criteria->compare('alipay_public_key_file',$this->alipay_public_key_file,true);
		$criteria->compare('merchant_private_key_file',$this->merchant_private_key_file,true);
		$criteria->compare('merchant_public_key_file',$this->merchant_public_key_file,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return AlipayServiceAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
