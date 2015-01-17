<?php

/**
 * This is the model class for table "nb_site_no".
 *
 * The followings are the available columns in table 'nb_site_no':
 * @property string $id
 * @property string $company_id
 * @property string $site_id
 * @property string $waiter_id
 * @property string $code
 * @property integer $delete_flag
 */
class SiteNo extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_site_no';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('site_id, waiter_id', 'required'),
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('company_id, site_id, waiter_id, code', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id, company_id, site_id, waiter_id, code, delete_flag', 'safe', 'on'=>'search'),
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
				'site'=>array(self::BELONGS_TO , 'Site' , 'site_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'company_id' => '公司',
			'site_id' => '座次号',
			'waiter_id' => '服务员',
			'code' => '编码',
			'delete_flag' => '状态',
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
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('site_id',$this->site_id,true);
		$criteria->compare('waiter_id',$this->waiter_id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('delete_flag',$this->delete_flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return SiteNo the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
