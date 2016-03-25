<?php

/**
 * This is the model class for table "nb_private_promotion".
 *
 * The followings are the available columns in table 'nb_private_promotion':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $promotion_title
 * @property string $main_picture
 * @property string $promotion_abstract
 * @property string $promotion_memo
 * @property string $promotion_type
 * @property string $change_point
 * @property string $can_cupon
 * @property string $begin_time
 * @property string $end_time
 * @property string $day_begin
 * @property string $day_end
 * @property string $to_group
 * @property string $group_id
 * @property string $order_num
 * @property string $is_available
 * @property string $delete_flag
 *  @property string $is_sync
 */
class PrivatePromotion extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_private_promotion';
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
			array('lid, dpid, order_num, group_id, change_point,', 'length', 'max'=>10),
			array('promotion_title, is_sync', 'length', 'max'=>50),
			array('main_picture, promotion_abstract', 'length', 'max'=>255),
			array('to_group,promotion_type, can_cupon, is_available, delete_flag', 'length', 'max'=>2),
			//array('order_num', 'length', 'max'=>3),
			array('create_at, begin_time, end_time, day_begin, day_end, weekday', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, promotion_title, main_picture, promotion_abstract, promotion_memo, promotion_type, change_point, can_cupon, begin_time, end_time, weekday, day_begin, end_day, is_available, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
				'PrivateBranduser' => array(self::BELONGS_TO , 'PrivateBranduser' ,'' ,'on'=>'PrivateBranduser.to_group = 2 and t.dpid = PrivateBranduser.dpid and PrivateBranduser.private_promotion_id=t.lid') ,
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
			'create_at' => '创建时间',
			'update_at' => '最近一次更新时间',
			'promotion_title' => '标题',
			'main_picture' => '主图片',
			'promotion_abstract' => '摘要',
			'promotion_memo' => '图文说明，包括规则',
			'promotion_type' => '0排他，1万能',
			'change_point' => '0表示不需要积分，就可以直接领取；>0表示需要的兑换积分数。',
			'can_cupon' => '是否能使用代金券，0表示能用，1表示不能用',
			'begin_time' => '开始时间',
			'end_time' => '结束时间',
			'weekday' => '选择折扣新奇',
			'day_begin' => '开始时间段',
			'day_end' => '结束时间段',
				
			'to_group' => '0表示所有人，1表示关注微信的人群，2表示会员等级，3表示会员个人',
			'group_id' => '表示会员的id',
			'order_num' => '单个订单的数量限制',
			'is_available' => '是否生效，0表示生效，1表示无效。',
			'delete_flag' => '0表示存在，1表示删除。',
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
		$criteria->compare('promotion_title',$this->promotion_title,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('promotion_abstract',$this->promotion_abstract,true);
		$criteria->compare('promotion_memo',$this->promotion_memo,true);
		$criteria->compare('promotion_type',$this->promotion_type,true);
		$criteria->compare('change_point',$this->change_point,true);
		$criteria->compare('can_cupon',$this->can_cupon,true);
		$criteria->compare('begin_time',$this->begin_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('weekday',$this->weekday,true);
		$criteria->compare('day_begin',$this->day_begin,true);
		$criteria->compare('day_end',$this->day_end,true);
		$criteria->compare('to_group',$this->to_group,true);
		$criteria->compare('group_id',$this->group_id,true);
		$criteria->compare('order_num',$this->order_num,true);
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
	 * @return PrivatePromotion the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
