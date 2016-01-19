<?php

/**
 * This is the model class for table "nb_cashback_record".
 *
 * The followings are the available columns in table 'nb_cashback_record':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $point_type
 * @property string $type_lid
 * @property integer $cashback_num
 * @property string $brand_user_lid
 * @property string $begin_timestamp
 * @property string $end_timestamp
 * @property string $delete_flag
 * @property string $is_sync
 */
class CashbackRecord extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_cashback_record';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, point_type', 'required'),
			array('cashback_num', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, type_lid, brand_user_lid', 'length', 'max'=>10),
			array('point_type, delete_flag', 'length', 'max'=>2),
			array('create_at', 'safe'),
				array('is_sync','length','max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid,begin_timestamp,end_timestamp, create_at, update_at, point_type, type_lid, is_sync, cashback_num, brand_user_lid, delete_flag', 'safe', 'on'=>'search'),
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
			'update_at' => '最近一次的更新时间',
			'point_type' => '0消费，1充值',
			'type_lid' => '消费就是order的lid，充值就是recharge_record的lid',
			'cashback_num' => '返现金额',
			'brand_user_lid' => '会员id',
				'begin_timestamp' => '起始时间',
				'end_timestamp' => '结束时间',
			'delete_flag' => '0表示存在，1表示删除',
				'is_sync' => yii::t('app','是否同步'),
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
		$criteria->compare('point_type',$this->point_type,true);
		$criteria->compare('type_lid',$this->type_lid,true);
		$criteria->compare('cashback_num',$this->cashback_num);
		$criteria->compare('brand_user_lid',$this->brand_user_lid,true);
		$criteria->compare('begin_timestamp',$this->begin_timestamp,true);
		$criteria->compare('end_timestamp',$this->end_timestamp,true);
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
	 * @return CashbackRecord the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
