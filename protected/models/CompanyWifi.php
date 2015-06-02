<?php

/**
 * This is the model class for table "nb_company_wifi".
 *
 * The followings are the available columns in table 'nb_company_wifi':
 * @property string $id
 * @property string $company_id
 * @property string $macid
 */
class CompanyWifi extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_company_wifi';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('dpid,macid,wifi_name,max_number' , 'required'),
			array('dpid', 'length', 'max'=>10),
			array('macid, wifi_name', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, dpid, wifi_name, macid, max_number', 'safe', 'on'=>'search'),
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
			'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'ID',
			'dpid' => 'Company',
			'macid' => yii::t('app','公司WIFI MAC ID'),
			'wifi_name' => yii::t('app','wifi名称'),
			'max_number' => yii::t('app','最大接入数'),
			'current_num' => yii::t('app','现接入数')
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('company_id',$this->dpid,true);
		$criteria->compare('macid',$this->macid,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return CompanyWifi the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function getPkValue() {
		$sql = 'SELECT NEXTVAL("'.$this->tableName().'") AS id';
		$row = Yii::app()->db->createCommand($sql)->queryRow();
		return $row ? $row['id'] : 1 ;
	}
	
}
