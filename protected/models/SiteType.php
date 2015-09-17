<?php

/**
 * This is the model class for table "nb_site_type".
 *
 * The followings are the available columns in table 'nb_site_type':
 * @property integer $lid
 * @property integer $dpid
 * @property string $name
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
			array('name , dpid', 'required'),
			array('lid, dpid', 'numerical', 'integerOnly'=>true),
			array('name', 'length', 'max'=>45),
			array('simplecode', 'length', 'max'=>3),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, name, dpid, simplecode', 'safe', 'on'=>'search'),
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
				'site' => array(self::HAS_MANY , 'Site' ,'' ,'on'=>'t.lid=site.type_id and t.dpid=site.dpid','order'=>'site.serial') ,
				'company' => array(self::BELONGS_TO , 'Company' ,'' ,'on'=>'t.dpid=company.dpid') ,
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => yii::t('app','位置类型ID'),
			'name' => yii::t('app','类型名称'),
			'simplecode' => yii::t('app','简称字符'),
			'dpid' => yii::t('app','公司'),
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

		$criteria->compare('lid',$this->lid);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('simplecode',$this->simplecode,true);
		$criteria->compare('dpid',$this->dpid);

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
