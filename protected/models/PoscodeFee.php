<?php

/**
 * This is the model class for table "nb_poscode_fee".
 *
 * The followings are the available columns in table 'nb_poscode_fee':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $poscode
 * @property string $exp_time
 * @property integer $num
 * @property string $status
 */
class PoscodeFee extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_poscode_fee';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dpid, poscode', 'required'),
			array('num', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('poscode', 'length', 'max'=>50),
			array('exp_time', 'length', 'max'=>255),
			array('status', 'length', 'max'=>25),
			array('create_at, update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, poscode, exp_time, num, status', 'safe', 'on'=>'search'),
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
			'update_at' => '最近一次更新时间',
			'poscode' => '序列号',
			'exp_time' => '到期时间',
			'num' => '第几台机器',
			'status' => '0表示状态正常，1表示POS端不可用，2表示线上不可用，3表示都不可用',
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
		$criteria->compare('poscode',$this->poscode,true);
		$criteria->compare('exp_time',$this->exp_time,true);
		$criteria->compare('num',$this->num);
		$criteria->compare('status',$this->status,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PoscodeFee the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
