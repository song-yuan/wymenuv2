<?php

/**
 * This is the model class for table "nb_printer_way".
 *
 * The followings are the available columns in table 'nb_printer_way':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $name
 * @property string $is_onepaper
 * @property integer $list_no
 * @property string $memo
 * @property string $delete_flag
 * @property string $is_sync
 */
class PrinterWay extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_printer_way';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('lid, dpid', 'length', 'max'=>10),
                        array('list_no', 'length', 'max'=>3),
			array('name,is_sync', 'length', 'max'=>50),
			array('memo', 'length', 'max'=>100),
			array('is_onepaper,delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, name, memo, delete_flag,is_sync', 'safe', 'on'=>'search'),
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
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'name' => yii::t('app','名称'),
                        'is_onepaper'=>yii::t('app','是否整单打印'),
                        'list_no'=>yii::t('app','打印份数'),
			'memo' => yii::t('app','说明'),
			'delete_flag' => 'Delete Flag',
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
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('name',$this->name,true);
                $criteria->compare('is_onepaper',$this->name,true);
                $criteria->compare('list_no',$this->name,true);
		$criteria->compare('memo',$this->memo,true);
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
	 * @return PrinterWay the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function getPrinterWay($companyId)
	{
		$sql = 'select lid,name from nb_printer_way where dpid='.$companyId.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}
}
