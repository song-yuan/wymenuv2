<?php

/**
 * This is the model class for table "nb_material_unit_ratio".
 *
 * The followings are the available columns in table 'nb_material_unit_ratio':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $stock_unit_id
 * @property string $sales_unit_id
 * @property string $unit_ratio
 * @property integer $delete_flag
 * @property string $is_sync
 */
class MaterialUnitRatio extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_material_unit_ratio';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, stock_unit_id, sales_unit_id', 'required'),
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, stock_unit_id, sales_unit_id, unit_ratio', 'length', 'max'=>10),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, stock_unit_id, sales_unit_id, unit_ratio, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'unit' => array(self::BELONGS_TO,'MaterialUnit','','on'=>'t.stock_unit_id=unit.lid or t.sales_unit_id=unit.lid and unit.dpid=t.dpid and unit.delete_flag=0'),
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
			'stock_unit_id' => '库存单位',
			'sales_unit_id' => '零售单位',
			'unit_ratio' => '对应系数',
			'delete_flag' => '删除 0未删除 1删除',
			'is_sync' => 'Is Sync',
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
		$criteria->compare('stock_unit_id',$this->stock_unit_id,true);
		$criteria->compare('sales_unit_id',$this->sales_unit_id,true);
		$criteria->compare('unit_ratio',$this->unit_ratio,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MaterialUnitRatio the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
