<?php

/**
 * This is the model class for table "nb_site".
 *
 * The followings are the available columns in table 'nb_site':
 * @property string $site_id
 * @property string $serial
 * @property integer $type_id
 * @property string $site_level
 * @property string $company_id
 * @property integer $delete_flag
 * @property integer $has_minimum_consumption
 * @property integer $minimum_consumption_type
 * @property string $minimum_consumption
 * @property string $number
 * @property double $period
 * @property double $overtime
 * @property double $buffer
 * @property string $overtime_fee
 */
class Site extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_site';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('serial , type_id , company_id , site_level' , 'required'),
			array('type_id, delete_flag, has_minimum_consumption, minimum_consumption_type', 'numerical', 'integerOnly'=>true),
			array('period, overtime,buffer', 'numerical'),
			array('serial', 'length', 'max'=>50),
			array('site_level', 'length', 'max'=>20),
			array('company_id, minimum_consumption, number, period, overtime, overtime_fee', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('site_id, serial, type_id, site_level, company_id, delete_flag, has_minimum_consumption, minimum_consumption_type, minimum_consumption, number, period, overtime, buffer, overtime_fee', 'safe', 'on'=>'search'),
		);
	}
	public function validate($attributes = NULL, $clearErrors = true){
		
		$valid = parent::validate();
		if(!$this->company_id){
			return false;
		}
		$site = Site::model()->find('site_id<>:siteId and type_id=:typeId and company_id=:companyId and serial=:serial and delete_flag=0' , array(':serial'=>$this->serial,':siteId'=>$this->site_id?$this->site_id:'',':typeId'=>$this->type_id,':companyId'=>$this->company_id));
		if($site) {
			$this->addError('serial', '座位号已经存在');
			return false;
		}
		return !$this->hasErrors();
	}
	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
				'isfree' => array(self::HAS_ONE , 'SiteNo' , 'site_id' , 'on' => 'isfree.delete_flag=0'),
				'siteType' => array(self::BELONGS_TO , 'SiteType' ,'type_id')
		);
	}
	
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'site_id' => 'Site',
			'serial' => '座位编号',
			'type_id' => '座位类型',
			'site_level' => '座位等级',
			'company_id' => 'Company',
			'delete_flag' => '删除',
			'has_minimum_consumption' => '是否有最低消费',
			'minimum_consumption_type' => '最低消费类型',
			'minimum_consumption' => '最低消费（元/间（人））',
			'number' => '人数',
			'period' => '最低消费时间（分钟）',
			'overtime' => '超时单位（分钟）',
			'buffer' => '超时计算点（分钟）',
			'overtime_fee' => '超时费（元）',
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

		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('serial',$this->serial,true);
		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('site_level',$this->site_level,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('has_minimum_consumption',$this->has_minimum_consumption);
		$criteria->compare('minimum_consumption_type',$this->minimum_consumption_type);
		$criteria->compare('minimum_consumption',$this->minimum_consumption,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('period',$this->period,true);
		$criteria->compare('overtime',$this->overtime,true);
		$criteria->compare('buffer',$this->buffer);
		$criteria->compare('overtime_fee',$this->overtime_fee,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Site the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
