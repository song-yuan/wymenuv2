<?php

/**
 * This is the model class for table "nb_weixin_card".
 *
 * The followings are the available columns in table 'nb_weixin_card':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $logo
 * @property string $color
 * @property string $brand_name
 * @property string $title
 * @property string $sub_title
 * @property string $notice
 * @property string $description
 * @property integer $date_info_type
 * @property integer $begin_timestamp
 * @property integer $end_timestamp
 * @property integer $fixed_term
 * @property integer $fixed_begin_term
 * @property integer $sku_quantity
 * @property integer $can_share
 * @property integer $can_give_friend
 * @property integer $get_limit
 * @property string $service_phone
 * @property integer $card_type
 * @property integer $least_cost
 * @property integer $reduce_cost
 * @property string $gift
 * @property string $card_id
 * @property string $qrcode
 * @property integer $status
 * @property integer $delete_flag
 * @property string $is_sync
 */
class WeixinCard extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return WeixinCard the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_weixin_card';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, brand_name, title, notice, description, date_info_type', 'required'),
			array('date_info_type, begin_timestamp, end_timestamp, fixed_term, fixed_begin_term, sku_quantity, can_share, can_give_friend, get_limit, card_type, least_cost, reduce_cost, status, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('logo, qrcode', 'length', 'max'=>255),
			array('color, brand_name, title, sub_title, notice, service_phone, card_id', 'length', 'max'=>64),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, gift', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, logo, color, brand_name, title, sub_title, notice, description, date_info_type, begin_timestamp, end_timestamp, fixed_term, fixed_begin_term, sku_quantity, can_share, can_give_friend, get_limit, service_phone, card_type, least_cost, reduce_cost, gift, card_id, qrcode, status, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'dpid' => 'Dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'logo' => 'Logo',
			'color' => 'Color',
			'brand_name' => 'Brand Name',
			'title' => 'Title',
			'sub_title' => 'Sub Title',
			'notice' => 'Notice',
			'description' => 'Description',
			'date_info_type' => 'Date Info Type',
			'begin_timestamp' => 'Begin Timestamp',
			'end_timestamp' => 'End Timestamp',
			'fixed_term' => 'Fixed Term',
			'fixed_begin_term' => 'Fixed Begin Term',
			'sku_quantity' => 'Sku Quantity',
			'can_share' => 'Can Share',
			'can_give_friend' => 'Can Give Friend',
			'get_limit' => 'Get Limit',
			'service_phone' => 'Service Phone',
			'card_type' => 'Card Type',
			'least_cost' => 'Least Cost',
			'reduce_cost' => 'Reduce Cost',
			'gift' => 'Gift',
			'card_id' => 'Card',
			'qrcode' => 'Qrcode',
			'status' => 'Status',
			'delete_flag' => 'Delete Flag',
			'is_sync' => 'Is Sync',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('color',$this->color,true);
		$criteria->compare('brand_name',$this->brand_name,true);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('sub_title',$this->sub_title,true);
		$criteria->compare('notice',$this->notice,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('date_info_type',$this->date_info_type);
		$criteria->compare('begin_timestamp',$this->begin_timestamp);
		$criteria->compare('end_timestamp',$this->end_timestamp);
		$criteria->compare('fixed_term',$this->fixed_term);
		$criteria->compare('fixed_begin_term',$this->fixed_begin_term);
		$criteria->compare('sku_quantity',$this->sku_quantity);
		$criteria->compare('can_share',$this->can_share);
		$criteria->compare('can_give_friend',$this->can_give_friend);
		$criteria->compare('get_limit',$this->get_limit);
		$criteria->compare('service_phone',$this->service_phone,true);
		$criteria->compare('card_type',$this->card_type);
		$criteria->compare('least_cost',$this->least_cost);
		$criteria->compare('reduce_cost',$this->reduce_cost);
		$criteria->compare('gift',$this->gift,true);
		$criteria->compare('card_id',$this->card_id,true);
		$criteria->compare('qrcode',$this->qrcode,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}