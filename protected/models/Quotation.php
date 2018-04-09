<?php

/**
 * This is the model class for table "nb_quotation".
 *
 * The followings are the available columns in table 'nb_quotation':
 * @property string $lid
 * @property string $dpid
 * @property string $supid
 * @property string $create_at
 * @property string $update_at
 * @property string $sup_status
 * @property string $com_status
 * @property string $status
 * @property string $delete_flag
 */
class Quotation extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_quotation';
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
			array('lid, dpid, supid', 'length', 'max'=>10),
			array('sup_status, com_status, status, delete_flag', 'length', 'max'=>2),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, supid, create_at, update_at, sup_status, com_status, status, delete_flag', 'safe', 'on'=>'search'),
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
			'dpid' => '品牌id',
			'supid' => '供应商id',
			'create_at' => 'Create At',
			'update_at' => '最近一次更新时间',
			'sup_status' => '供应商0允许修改，1不允许修改',
			'com_status' => '品牌0允许修改，1不允许修改',
			'status' => '当前两字段都为0时，即为0，表示可修改，否则不可修改',
			'delete_flag' => '1表示已删除。',
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
		$criteria->compare('supid',$this->supid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('sup_status',$this->sup_status,true);
		$criteria->compare('com_status',$this->com_status,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Quotation the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
