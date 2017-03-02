<?php

/**
 * This is the model class for table "nb_brand_user_level".
 *
 * The followings are the available columns in table 'nb_brand_user_level':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $level_name
 * @property string $level_type
 * @property string $level_discount
 * @property string $min_charge_money
 * @property string $card_cost
 * @property integer $min_total_points
 * @property integer $max_total_points
 * @property integer $enable_date
 * @property string $delete_flag
 * @property string $is_sync
 */
class BrandUserLevel extends CActiveRecord
{
	public $bgimg;
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_brand_user_level';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('level_type,level_name', 'required'),
			array('min_total_points, max_total_points', 'numerical', 'integerOnly'=>true),
			array('lid, dpid,min_charge_money,card_cost', 'length', 'max'=>10),
			array('level_name, is_sync', 'length', 'max'=>50),
			array('level_type,delete_flag', 'length', 'max'=>2),
			array('level_discount,birthday_discount', 'length', 'max'=>8),
			array('create_at,update_at,enable_date,style_id', 'safe'),
			array('level_discount','compare','compareValue'=>'1','operator'=>'<=','message'=>yii::t('app','折扣数值大于等于0小于等于1')),
			array('level_discount','compare','compareValue'=>'0','operator'=>'>=','message'=>yii::t('app','折扣数值大于等于0小于等于1')),
           // array('min_total_points','compare','compareAttribute'=>'max_total_points','operator'=>'<','message'=>yii::t('app','最低积分大于最高积分')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, level_name, level_type, level_discount, birthday_discount, min_charge_money, card_cost, min_total_points, is_sync, max_total_points,enable_date, style_id, delete_flag', 'safe', 'on'=>'search'),
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
				'MemberWxCardStyle' => array(self::BELONGS_TO , 'MemberWxcardStyle' ,'' ,'on'=>'t.dpid = MemberWxCardStyle.dpid and t.style_id = MemberWxCardStyle.lid '),
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
			'level_name' => '等级名称',
            'level_type' => '等级类型',
			'level_discount' => '会员折扣',
			'birthday_discount' => '会员生日折扣',
			'min_charge_money' => '最低充值金额',
			'card_cost' => '制卡工本费',
			'min_total_points' => '当前等级的最低积分',
			'max_total_points' => '当前等级的最高积分',
			'enable_date' => '有效期',
			'style_id' => '背景样式',
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
		$criteria->compare('level_name',$this->level_name,true);
        $criteria->compare('level_type',$this->level_type,true);
        $criteria->compare('level_discount',$this->level_discount,true);
        $criteria->compare('birthday_discount',$this->birthday_discount,true);
		$criteria->compare('min_total_points',$this->min_total_points);
		$criteria->compare('max_total_points',$this->max_total_points);
		$criteria->compare('enable_date',$this->enable_date,true);
		$criteria->compare('style_id',$this->style_id,true);
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
	 * @return BrandUserLevel the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
