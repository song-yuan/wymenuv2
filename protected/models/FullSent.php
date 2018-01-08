<?php

/**
 * This is the model class for table "nb_full_sent".
 *
 * The followings are the available columns in table 'nb_full_sent':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $title
 * @property string $infor
 * @property string $begin_time
 * @property string $end_time
 * @property string $full_cost
 * @property string $extra_cost
 * @property integer $sent_number
 * @property integer $delete_flag
 * @property string $is_sync
 */
class FullSent extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_full_sent';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, full_cost, extra_cost', 'required'),
			array('sent_number, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, full_cost, extra_cost', 'length', 'max'=>10),
			array('title', 'length', 'max'=>64),
			array('infor', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('is_available', 'length', 'max'=>16),
			array('create_at, begin_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, title, infor, begin_time, end_time, full_cost, extra_cost, sent_number, is_available, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'title' => '标题',
			'infor' => '描述',
			'begin_time' => 'Begin Time',
			'end_time' => 'End Time',
			'full_cost' => '满足金额',
			'extra_cost' => '加价金额',
			'sent_number' => '赠送数量限制',
			'delete_flag' => '逻辑删除 0未删除 1删除',
			'is_sync' => 'Is Sync',
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
		$criteria->compare('title',$this->title,true);
		$criteria->compare('infor',$this->infor,true);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('full_cost',$this->full_cost,true);
		$criteria->compare('extra_cost',$this->extra_cost,true);
		$criteria->compare('sent_number',$this->sent_number);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return FullSent the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
