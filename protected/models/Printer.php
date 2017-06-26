<?php

/**
 * This is the model class for table "nb_printer".
 *
 * The followings are the available columns in table 'nb_printer':
 * @property string $printer_id
 * @property string $company_id
 * @property string $width_type
 * @property string $name
 * @property string $address
 * @property string $language
 * @property string $brand
 * @property string $remark
 * @property string $is_sync
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
			array('name, dpid, lid', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
			array('remark, brand', 'length', 'max'=>45),
            array('language, printer_type, width_type', 'length', 'max'=>2),
            array('name, address', 'length', 'max'=>64),
			array('is_sync','length','max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, name, address, create_at, language, printer_type, width_type, brand, remark, is_sync', 'safe', 'on'=>'search'),
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
			'lid' => 'Printer',
			'dpid' => 'Company',
			'name' => yii::t('app','打印机名称'),
            'address'=>yii::t('app','地址(IP/USB/COM)'),
			'language' => yii::t('app','语言'),
			'brand' => yii::t('app','品牌'),
            'printer_type' => yii::t('app','类型'),
			'width_type' => yii::t('app','打印纸宽度'),
			'remark' => yii::t('app','备注'),
			'is_sync' => yii::t('app','是否同步'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('language',$this->language,true);
		$criteria->compare('printer_type',$this->printer_type,true);
		$criteria->compare('width_type',$this->width_type,true);
		$criteria->compare('brand',$this->brand,true);
		$criteria->compare('remark',$this->remark,true);
		$criteria->compare('is_sync',$this->is_sync,true);

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
