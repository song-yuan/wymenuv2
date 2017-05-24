<?php

/**
 * This is the model class for table "nb_cupon".
 *
 * The followings are the available columns in table 'nb_cupon':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $cupon_title
 * @property string $main_picture
 * @property string $cupon_abstract
 * @property string $cupon_memo
 * @property string $cupon_money
 * @property string $min_consumer
 * @property integer $change_point
 * @property string $time_type
 * @property integer $day
 * @property integer $day_begin
 * @property string $begin_time
 * @property string $end_time
 * @property string $is_available
 * @property string $delete_flag
 * @property string $is_sync
 */
class Cupon extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_cupon';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid', 'required'),
			array('change_point', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, cupon_money, min_consumer', 'length', 'max'=>10),
			array('cupon_title, is_sync', 'length', 'max'=>50),
			array('main_picture, cupon_abstract', 'length', 'max'=>255),
			array('is_available, delete_flag, to_group, time_type, day_begin', 'length', 'max'=>2),
			array('create_at, begin_time, end_time, cupon_memo, day', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, cupon_title, is_sync, main_picture, to_group, cupon_abstract, cupon_memo, cupon_money, min_consumer, change_point, time_type, day, day_begin, begin_time, end_time, is_available, delete_flag', 'safe', 'on'=>'search'),
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
			'cupon_title' => '标题',
			'main_picture' => '主图片',
			'cupon_abstract' => '摘要',
			'to_group'=>'0表示所有人，1表示关注微信，2表示会员等级，3表示会员个人',
			'cupon_memo' => '规则说明',
			'cupon_money' => '金额',
			'min_consumer' => '最低消费',
			'change_point' => '0表示不需要积分，直接领取；>0表示需要兑换的积分',
			'time_type' => '限制形式',
			'begin_time' => '开始时间',
			'end_time' => '结束时间',
			'day' => '限制天数',
			'day_begin' => '开始天数',	
			'is_available' => '0表示有效，1表示无效',
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
		$criteria->compare('cupon_title',$this->cupon_title,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('cupon_abstract',$this->cupon_abstract,true);
		$criteria->compare('cupon_memo',$this->cupon_memo,true);
		$criteria->compare('to_group',$this->to_group,true);
		$criteria->compare('cupon_money',$this->cupon_money,true);
		$criteria->compare('min_consumer',$this->min_consumer,true);
		$criteria->compare('change_point',$this->change_point);
		$criteria->compare('time_type',$this->time_type,true);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('day_begin',$this->day_begin,true);
		$criteria->compare('day',$this->day,true);
		$criteria->compare('is_available',$this->is_available,true);
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
	 * @return Cupon the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
