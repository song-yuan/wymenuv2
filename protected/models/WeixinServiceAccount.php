<?php

/**
 * This is the model class for table "nb_weixin_service_account".
 *
 * The followings are the available columns in table 'nb_weixin_service_account':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $token
 * @property string $original_id
 * @property string $appid
 * @property string $appsecret
 * @property integer $expire
 * @property string $access_token
 * @property string $partner_id
 * @property string $key
 * @property string $operator
 * @property string $certificate
 * @property string $rootca
 * @property string $apiclient_key
 * @property integer $multi_customer_service_status
 * @property string $ticket
 * @property integer $ticket_expire
 * @property string $is_sync
 */
class WeixinServiceAccount extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WeixinServiceAccount the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_weixin_service_account';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, appid, appsecret', 'required'),
			array('expire, multi_customer_service_status, ticket_expire', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('token, original_id', 'length', 'max'=>45),
			array('appid, appsecret, certificate, rootca, apiclient_key, ticket', 'length', 'max'=>255),
			array('access_token', 'length', 'max'=>1000),
			array('partner_id, is_sync', 'length', 'max'=>50),
			array('key', 'length', 'max'=>32),
			array('operator', 'length', 'max'=>30),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, is_sync, token, original_id, appid, appsecret, expire, access_token, partner_id, key, operator, certificate, rootca, apiclient_key, multi_customer_service_status, ticket, ticket_expire', 'safe', 'on'=>'search'),
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
			'lid' => 'Lid',
			'dpid' => 'Dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'token' => 'Token',
			'original_id' => 'Original',
			'appid' => 'Appid',
			'appsecret' => 'Appsecret',
			'expire' => 'Expire',
			'access_token' => 'Access Token',
			'partner_id' => '微信支付商户号',
			'key' => '微信支付API密钥',
			'operator' => 'Operator',
			'certificate' => 'Certificate',
			'rootca' => 'Rootca',
			'apiclient_key' => 'Apiclient Key',
			'multi_customer_service_status' => 'Multi Customer Service Status',
			'ticket' => 'Ticket',
			'ticket_expire' => 'Ticket Expire',
				'is_sync' => yii::t('app','是否同步'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('token',$this->token,true);
		$criteria->compare('original_id',$this->original_id,true);
		$criteria->compare('appid',$this->appid,true);
		$criteria->compare('appsecret',$this->appsecret,true);
		$criteria->compare('expire',$this->expire);
		$criteria->compare('access_token',$this->access_token,true);
		$criteria->compare('partner_id',$this->partner_id,true);
		$criteria->compare('key',$this->key,true);
		$criteria->compare('operator',$this->operator,true);
		$criteria->compare('certificate',$this->certificate,true);
		$criteria->compare('rootca',$this->rootca,true);
		$criteria->compare('apiclient_key',$this->apiclient_key,true);
		$criteria->compare('multi_customer_service_status',$this->multi_customer_service_status);
		$criteria->compare('ticket',$this->ticket,true);
		$criteria->compare('ticket_expire',$this->ticket_expire);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}