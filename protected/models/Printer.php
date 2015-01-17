<?php

/**
 * This is the model class for table "nb_printer".
 *
 * The followings are the available columns in table 'nb_printer':
 * @property string $printer_id
 * @property string $company_id
 * @property string $name
 * @property string $ip_address
 * @property string $department_id
 * @property string $brand
 * @property string $remark
 */
class Printer extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_printer';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name,ip_address, brand', 'required'),
			array('company_id, department_id', 'length', 'max'=>10),
			array('name, ip_address, brand', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('printer_id, company_id, name, ip_address, department_id, brand, remark', 'safe', 'on'=>'search'),
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
			'printer_id' => 'Printer',
			'company_id' => 'Company',
			'name' => '打印机名称',
			'ip_address' => 'IP地址',
			'department_id' => 'Department',
			'brand' => '品牌',
			'remark' => '备注',
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

		$criteria->compare('printer_id',$this->printer_id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('ip_address',$this->ip_address,true);
		$criteria->compare('department_id',$this->department_id,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Printer the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
