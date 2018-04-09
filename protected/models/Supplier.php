<?php

/**
 * This is the model class for table "nb_supplier".
 *
 * The followings are the available columns in table 'nb_supplier':
 * @property string $supid
 * @property string $create_at
 * @property string $update_at
 * @property string $name
 * @property string $logo
 * @property string $contact_name
 * @property string $mobile
 * @property string $tel
 * @property string $address
 * @property string $lng
 * @property string $lat
 * @property string $country
 * @property string $province
 * @property string $city
 * @property string $area
 * @property string $delete_flag
 * @property string $is_sync
 */
class Supplier extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_supplier';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, name, contact_name', 'required'),
			array('supid, lng, lat', 'length', 'max'=>10),
			array('name, logo, address', 'length', 'max'=>255),
			array('contact_name, mobile, tel', 'length', 'max'=>20),
			array('country, province, city, area, is_sync', 'length', 'max'=>25),
			array('delete_flag', 'length', 'max'=>2),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('supid, create_at, update_at, name, logo, contact_name, mobile, tel, address, lng, lat, country, province, city, area, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'supid' => 'Supid',
			'create_at' => 'Create At',
			'update_at' => '最近一次更新时间',
			'name' => '名称',
			'logo' => 'logo',
			'contact_name' => '联系人',
			'mobile' => '手机号',
			'tel' => '电话',
			'address' => '地址',
			'lng' => '经度',
			'lat' => '纬度',
			'country' => '国家',
			'province' => '省',
			'city' => '市',
			'area' => '区',
			'delete_flag' => '短信套餐id',
			'is_sync' => '订单号',
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

		$criteria->compare('supid',$this->supid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('logo',$this->logo,true);
		$criteria->compare('contact_name',$this->contact_name,true);
		$criteria->compare('mobile',$this->mobile,true);
		$criteria->compare('tel',$this->tel,true);
		$criteria->compare('address',$this->address,true);
		$criteria->compare('lng',$this->lng,true);
		$criteria->compare('lat',$this->lat,true);
		$criteria->compare('country',$this->country,true);
		$criteria->compare('province',$this->province,true);
		$criteria->compare('city',$this->city,true);
		$criteria->compare('area',$this->area,true);
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
	 * @return Supplier the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
