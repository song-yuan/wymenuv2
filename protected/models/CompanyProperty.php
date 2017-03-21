<?php

/**
 * This is the model class for table "nb_company_property".
 *
 * The followings are the available columns in table 'nb_company_property':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $pay_type
 * @property string $pay_channel
 * @property string $appId
 * @property string $qr_code
 * @property string $delete_flag
 * @property string $is_sync
 */
class CompanyProperty extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_company_property';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, update_at', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
			array('pay_type, pay_channel, delete_flag', 'length', 'max'=>2),
			array('appId, is_sync', 'length', 'max'=>50),
			array('qr_code', 'length', 'max'=>250),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, pay_type, pay_channel, appId, qr_code, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'pay_type' => '0表示未开通线上支付，1表示总部统一管理支付，2表示店铺自管

理支付',
			'pay_channel' => '1表示官方支付渠道，2表示收钱吧支付渠道，3表示翼码支付渠

道',
			'appId' => '店铺在支付平台对应的id',
			'qr_code' => '店铺二维码',
			'delete_flag' => '1表示删除',
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
		$criteria->compare('pay_type',$this->pay_type,true);
		$criteria->compare('pay_channel',$this->pay_channel,true);
		$criteria->compare('appId',$this->appId,true);
		$criteria->compare('qr_code',$this->qr_code,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyProperty the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
