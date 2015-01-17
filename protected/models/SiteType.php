<?php

/**
 * This is the model class for table "nb_site_type".
 *
 * The followings are the available columns in table 'nb_site_type':
 * @property integer $type_id
 * @property string $name
 * @property integer $company_id
 */
class SiteType extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_site_type';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name , company_id', 'required'),
			array('type_id, company_id', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('type_id, name, company_id', 'safe', 'on'=>'search'),
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
				'site' => array(self::HAS_MANY , 'Site' , 'type_id','condition'=>'site.delete_flag=0') ,
				'company' => array(self::BELONGS_TO , 'Company' , 'company_id') ,
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'type_id' => '位置类型ID',
			'name' => '类型名称',
			'company_id' => '公司',
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

		$criteria->compare('type_id',$this->type_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('company_id',$this->company_id);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SiteType the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
