<?php

/**
 * This is the model class for table "nb_consumer_cash_proportion".
 *
 * The followings are the available columns in table 'nb_consumer_cash_proportion':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $ccp_name
 * @property string $point_type
 * @property string $min_available_point
 * @property string $max_available_point
 * @property string $proportion_points
 * @property string $date_info_type
 * @property string $begin_timestamp
 * @property string $end_timestamp
 * @property string $fixed_term
 * @property string $fixed_begin_term
 * @property string $is_available
 * @property string $delete_flag
 *  @property string $is_sync
 */
class ConsumerCashProportion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_consumer_cash_proportion';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, ccp_name, point_type, min_available_point, max_available_point', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
			array('ccp_name, is_sync', 'length', 'max'=>50),
			array('date_info_type, point_type, is_available, delete_flag', 'length', 'max'=>2),
			array('fixed_term,fixed_begin_term,min_available_point, max_available_point', 'length', 'max'=>10),
            array('proportion_points','compare','compareValue'=>'9999','operator'=>'<','message'=>yii::t('app','比例数值太大')),
			array('min_available_point','compare','compareAttribute'=>'max_available_point','operator'=>'<','message'=>yii::t('app','最小积分大于最大积分')),			
			array('proportion_points', 'length', 'max'=>6),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, is_sync, date_info_type, ccp_name, point_type, begin_timestamp,end_timestamp,fixed_term,fixed_begin_term,min_available_point, max_available_point, proportion_points, is_available, delete_flag', 'safe', 'on'=>'search'),
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
			'ccp_name' => '名称',
			'point_type' => '类型',//0历史积分，1有效积分
			'min_available_point' => '最低积分',//只要达到最低积分需求，就按照这个比例来计算
			'max_available_point' => '最高积分',//，区间必须批次覆盖
			'proportion_points' => '消费返现比例',//，计算结果四舍五入
				'date_info_type' => '期限类型',
				'begin_timestamp' => '有效开始时间',
				'end_timestamp' => '结束时间',
				'fixed_term' => '有效天数',
				'fixed_begin_term' => '多少天之后有效',
			'is_available' => '是否有效',//0表示有效，1表示无效
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
		$criteria->compare('ccp_name',$this->ccp_name,true);
		$criteria->compare('point_type',$this->point_type,true);
		$criteria->compare('min_available_point',$this->min_available_point,true);
		$criteria->compare('max_available_point',$this->max_available_point,true);
		$criteria->compare('proportion_points',$this->proportion_points,true);
		$criteria->compare('date_info_type',$this->date_info_type,true);
		$criteria->compare('begin_timestamp',$this->begin_timestamp,true);
		$criteria->compare('end_timestamp',$this->end_timestamp,true);
		$criteria->compare('fixed_term',$this->fixed_term,true);
		$criteria->compare('fixed_begin_term',$this->fixed_begin_term,true);
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
	 * @return ConsumerCashProportion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
