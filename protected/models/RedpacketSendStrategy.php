<?php

/**
 * This is the model class for table "nb_redpacket_send_strategy".
 *
 * The followings are the available columns in table 'nb_redpacket_send_strategy':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $redpacket_lid
 * @property string $min_money
 * @property string $max_money
 * @property string $send_type
 * @property string $is_available
 * @property string $delete_flag
 */
class RedpacketSendStrategy extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_redpacket_send_strategy';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at', 'required'),
			array('lid, dpid, redpacket_lid, min_money, max_money', 'length', 'max'=>10),
			array('send_type, is_available, delete_flag', 'length', 'max'=>2),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, redpacket_lid, min_money, max_money, send_type, is_available, delete_flag', 'safe', 'on'=>'search'),
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
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '最近一次更新的时间',
			'redpacket_lid' => '红包的id',
			'min_money' => '使用该红包的结单最低消费',
			'max_money' => '使用该红包的结单最高消费',
			'send_type' => '0为结单时发送，1为其他发送（后续补充）',
			'is_available' => '是否生效；0为生效，1Wie无效',
			'delete_flag' => '0为存在，1为删除',
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
		$criteria->compare('redpacket_lid',$this->redpacket_lid,true);
		$criteria->compare('min_money',$this->min_money,true);
		$criteria->compare('max_money',$this->max_money,true);
		$criteria->compare('send_type',$this->send_type,true);
		$criteria->compare('is_available',$this->is_available,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RedpacketSendStrategy the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
