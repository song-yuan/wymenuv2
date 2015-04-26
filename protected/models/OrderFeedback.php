<?php

/**
 * This is the model class for table "nb_order_feedback".
 *
 * The followings are the available columns in table 'nb_order_feedback':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $site_id
 * @property string $is_temp
 * @property string $is_deal
 * @property string $feedback_id
 * @property string $order_id
 * @property string $is_order
 * @property string $feedback_memo
 * @property string $delete_flag
 */
class OrderFeedback extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_order_feedback';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('feedback_memo', 'required'),
			array('lid, dpid, site_id, feedback_id, order_id', 'length', 'max'=>10),
			array('is_temp, is_deal, is_order, delete_flag', 'length', 'max'=>1),
			array('feedback_memo', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, site_id, is_temp, is_deal, feedback_id, order_id, is_order, feedback_memo, delete_flag', 'safe', 'on'=>'search'),
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
			'site_id' => 'Site',
			'is_temp' => 'Is Temp',
			'is_deal' => '消息是否处理0未处理，1处理',
			'feedback_id' => 'Feedback',
			'order_id' => 'Order',
			'is_order' => '1是全单反馈，order_id就是订单lid，0不是全单，对应订单明细lid',
			'feedback_memo' => 'Feedback Memo',
			'delete_flag' => 'Delete Flag',
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
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('is_temp',$this->is_temp,true);
		$criteria->compare('is_deal',$this->is_deal,true);
		$criteria->compare('feedback_id',$this->feedback_id,true);
		$criteria->compare('order_id',$this->order_id,true);
		$criteria->compare('is_order',$this->is_order,true);
		$criteria->compare('feedback_memo',$this->feedback_memo,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return OrderFeedback the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
