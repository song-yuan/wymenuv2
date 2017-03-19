<?php

/**
 * This is the model class for table "nb_sqb_possetting".
 *
 * The followings are the available columns in table 'nb_sqb_possetting':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $pay_channel
 * @property string $appId
 * @property string $device_id
 * @property string $terminal_sn
 * @property string $terminal_key
 * @property string $key_validtime
 * @property string $com_qrcode
 * @property string $delete_flag
 * @property string $is_sync
 */
class SqbPossetting extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_sqb_possetting';
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
			array('delete_flag', 'length', 'max'=>2),
			array('appId, device_id, terminal_sn, terminal_key, is_sync', 'length', 'max'=>50),
			array('key_validtime', 'length', 'max'=>25),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, appId, device_id, terminal_sn, terminal_key, key_validtime, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'appId' => '店铺在支付平台对应的id',
			'device_id' => '设备唯一身份ID，对应系统内的POS秘钥',
			'terminal_sn' => '店铺在支付平台终端号',
			'terminal_key' => '店铺在支付平台终端秘钥',
			'key_validtime' => '有效时间',
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
		$criteria->compare('appId',$this->appId,true);
		$criteria->compare('device_id',$this->device_id,true);
		$criteria->compare('terminal_sn',$this->terminal_sn,true);
		$criteria->compare('terminal_key',$this->terminal_key,true);
		$criteria->compare('key_validtime',$this->key_validtime,true);
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
	 * @return CompanySetting the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
