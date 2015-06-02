<?php

/**
 * This is the model class for table "nb_department".
 *
 * The followings are the available columns in table 'nb_department':
 * @property string $department_id
 * @property string $company_id
 * @property string $name
 * @property string $manager
 * @property string $printer_id
 * @property integer $list_no
 * @property string $remark
 */
class Department extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_department';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('company_id,name', 'required'),
			array('list_no', 'numerical', 'integerOnly'=>true),
			array('company_id, printer_id', 'length', 'max'=>10),
			array('name', 'length', 'max'=>45),
			array('manager', 'length', 'max'=>20),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('department_id, company_id, name, manager, printer_id, list_no, remark', 'safe', 'on'=>'search'),
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
			'department_id' =>yii::t('app', '操作间Id'),
			'company_id' => yii::t('app','企业ID'),
			'name' => yii::t('app','名称'),
			'manager' => yii::t('app','负责人'),
			'printer_id' => yii::t('app','打印机ID'),
			'list_no' => yii::t('app','打印份数'),
			'remark' => yii::t('app','备注'),
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

		$criteria->compare('department_id',$this->department_id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('manager',$this->manager,true);
		$criteria->compare('printer_id',$this->printer_id,true);
		$criteria->compare('list_no',$this->list_no);
		$criteria->compare('remark',$this->remark,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Department the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
