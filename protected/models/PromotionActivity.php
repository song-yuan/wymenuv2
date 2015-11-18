<?php

/**
 * This is the model class for table "nb_promotion_activity".
 *
 * The followings are the available columns in table 'nb_promotion_activity':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $activity_title
 * @property string $main_picture
 * @property string $activity_memo
 * @property string $activity_abstract
 * @property string $begin_time
 * @property string $end_time
 * @property string $is_first_push
 * @property string $is_scan_push
 * @property string $delete_flag
 */
class PromotionActivity extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_promotion_activity';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, activity_title, main_picture, activity_memo, activity_abstract', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
			array('activity_title', 'length', 'max'=>50),
			array('main_picture, activity_abstract', 'length', 'max'=>255),
			array('is_first_push, is_scan_push, delete_flag', 'length', 'max'=>2),
			array('create_at, begin_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, activity_title, main_picture, activity_memo, activity_abstract, begin_time, end_time, is_first_push, is_scan_push, delete_flag', 'safe', 'on'=>'search'),
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
			'activity_title' => '标题',
			'main_picture' => '主图片',
			'activity_memo' => '图文说明',
			'activity_abstract' => '摘要',
			'begin_time' => '活动开始日期',
			'end_time' => '活动结束日期',
			'is_first_push' => '0表示关注推送，1表示不推送',
			'is_scan_push' => '0表示扫码推送，1表示不推送',
			'delete_flag' => '0表示存在，1表示删除',
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
		$criteria->compare('activity_title',$this->activity_title,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('activity_memo',$this->activity_memo,true);
		$criteria->compare('activity_abstract',$this->activity_abstract,true);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('is_first_push',$this->is_first_push,true);
		$criteria->compare('is_scan_push',$this->is_scan_push,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return PromotionActivity the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
