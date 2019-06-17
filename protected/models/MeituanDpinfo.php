<?php

/**
 * This is the model class for table "nb_meituan_dpinfo".
 *
 * The followings are the available columns in table 'nb_meituan_dpinfo':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $app_poi_code
 * @property string $name
 * @property string $address
 * @property string $latitude
 * @property string $longitude
 * @property string $pic_url
 * @property string $pic_url_large
 * @property string $phone
 * @property string $shipping_fee
 * @property string $shipping_time
 * @property string $promotion_info
 * @property integer $invoice_support
 * @property string $invoice_description
 * @property integer $open_level
 * @property integer $is_online
 * @property integer $ctime
 * @property integer $utime
 * @property string $third_tag_name
 * @property integer $pre_book
 * @property integer $time_select
 * @property string $delete_flag
 * @property string $is_aync
 */
class MeituanDpinfo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_meituan_dpinfo';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, update_at, address, latitude, longitude, phone, third_tag_name', 'required'),
			array('invoice_support, open_level, is_online, ctime, utime, pre_book, time_select', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, shipping_fee', 'length', 'max'=>10),
			array('app_poi_code, name', 'length', 'max'=>32),
			array('address, pic_url, pic_url_large, shipping_time, promotion_info, invoice_description', 'length', 'max'=>255),
			array('latitude, longitude, phone, third_tag_name', 'length', 'max'=>20),
			array('delete_flag', 'length', 'max'=>2),
			array('is_aync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, app_poi_code, name, address, latitude, longitude, pic_url, pic_url_large, phone, shipping_fee, shipping_time, promotion_info, invoice_support, invoice_description, open_level, is_online, ctime, utime, third_tag_name, pre_book, time_select, delete_flag, is_aync', 'safe', 'on'=>'search'),
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
			'app_poi_code' => 'App Poi Code',
			'name' => 'Name',
			'address' => 'Address',
			'latitude' => 'Latitude',
			'longitude' => 'Longitude',
			'pic_url' => 'Pic Url',
			'pic_url_large' => 'Pic Url Large',
			'phone' => 'Phone',
			'shipping_fee' => 'Shipping Fee',
			'shipping_time' => 'Shipping Time',
			'promotion_info' => 'Promotion Info',
			'invoice_support' => 'Invoice Support',
			'invoice_description' => 'Invoice Description',
			'open_level' => 'Open Level',
			'is_online' => 'Is Online',
			'ctime' => 'Ctime',
			'utime' => 'Utime',
			'third_tag_name' => 'Third Tag Name',
			'pre_book' => 'Pre Book',
			'time_select' => 'Time Select',
			'delete_flag' => 'Delete Flag',
			'is_aync' => 'Is Aync',
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
		$criteria->compare('app_poi_code',$this->app_poi_code,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('latitude',$this->latitude,true);
		$criteria->compare('longitude',$this->longitude,true);
		$criteria->compare('pic_url',$this->pic_url,true);
		$criteria->compare('pic_url_large',$this->pic_url_large,true);
		$criteria->compare('phone',$this->phone,true);
		$criteria->compare('shipping_fee',$this->shipping_fee,true);
		$criteria->compare('shipping_time',$this->shipping_time,true);
		$criteria->compare('promotion_info',$this->promotion_info,true);
		$criteria->compare('invoice_support',$this->invoice_support);
		$criteria->compare('invoice_description',$this->invoice_description,true);
		$criteria->compare('open_level',$this->open_level);
		$criteria->compare('is_online',$this->is_online);
		$criteria->compare('ctime',$this->ctime);
		$criteria->compare('utime',$this->utime);
		$criteria->compare('third_tag_name',$this->third_tag_name,true);
		$criteria->compare('pre_book',$this->pre_book);
		$criteria->compare('time_select',$this->time_select);
		$criteria->compare('delete_flag',$this->delete_flag,true);
		$criteria->compare('is_aync',$this->is_aync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MeituanDpinfo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
