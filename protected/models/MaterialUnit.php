<?php

/**
 * This is the model class for table "nb_material_unit".
 *
 * The followings are the available columns in table 'nb_material_unit':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $unit_type
 * @property string $unit_name
 * @property string $unit_specifications
 * @property integer $delete_flag
 * @property string $is_sync
 */
class MaterialUnit extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_material_unit';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('unit_name,', 'required'),
			array('unit_type, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('unit_name, unit_specifications', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, update_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, unit_type, unit_name, unit_specifications, sort_code, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
		'productMaterial'=>array(self::HAS_MANY,'ProductMaterial','','on'=>'t.lid=ProductMaterial.stock_unit_id or t.lid=ProductMaterial.sales_unit_id and ProductMaterial.dpid=t.dpid and ProductMaterial.delete_flag=0'),
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
			'unit_type' => '单位类型',
			'unit_name' => '单位名称',
			'unit_specifications' => '单位规格',
			'sort_code' => '序号',
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
		$criteria->compare('unit_type',$this->unit_type);
		$criteria->compare('unit_name',$this->unit_name,true);
		$criteria->compare('unit_specifications',$this->unit_specifications,true);
		$criteria->compare('sort_code',$this->sort_code,true);
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
	 * @return MaterialUnit the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	static public function getMaterialUnitLid($dpid,$muhs_code)
	{
		$db = Yii::app()->db;
		$sql = 'select * from nb_material_unit where dpid='.$dpid.' and muhs_code ='.$muhs_code.' and delete_flag=0';
		$materialUnitLid = $db->createCommand($sql)->queryRow();
	
		return $materialUnitLid['lid'];
	}
}
