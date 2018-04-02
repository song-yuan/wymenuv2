<?php

/**
 * This is the model class for table "nb_product_bom".
 *
 * The followings are the available columns in table 'nb_product_bom':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $product_id
 * @property string $material_id
 * @property string $number
 * @property string $sales_unit_id
 * @property integer $delete_flag
 * @property string $is_sync
 */
class ProductBom extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_bom';
	}
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid','required'),
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, product_id, material_id, number, sales_unit_id', 'length', 'max'=>10),
			array('is_sync', 'length', 'max'=>50),
			array('create_at ,update_at ,source', 'safe'),
			array('material_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择品项名称')),
			array('sales_unit_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择零售单位')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, product_id, material_id, number, sales_unit_id, source, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'product' => array(self::BELONGS_TO , 'Product' , '' , 'on' => ' t.product_id=product.lid and t.dpid=product.dpid and product.delete_flag=0'),
			'material' => array(self::BELONGS_TO , 'ProductMaterial' , '' , 'on' => ' t.material_id=material.lid and t.dpid=material.dpid and material.delete_flag=0'),
			'taste' => array(self::BELONGS_TO , 'Taste' , '' , 'on' => ' t.taste_id=taste.lid and t.dpid=taste.dpid and taste.delete_flag=0'),
			//'unit'=>array(self::BELONGS_TO, 'MaterialUnit' , '' ,'on'=>'t.sales_unit_id=unit.lid and t.dpid=unit.dpid and unit.delete_flag=0'),
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
			'product_id' => '产品名称',
			'material_id' => '原料名称',
			'number' => '消耗数量',
			'sales_unit_id' => '零售单位',
			'source' => '来源',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('material_id',$this->material_id,true);
		$criteria->compare('number',$this->number,true);
		$criteria->compare('sales_unit_id',$this->sales_unit_id,true);
		$criteria->compare('source',$this->source,true);
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
	 * @return ProductBom the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function getProductMaterialName($printerwayId,$dpid){
		$sql = 'SELECT material_name from nb_product_material where lid=:lid and dpid=:dpid and delete_flag = 0';
		$printerway = Yii::app()->db->createCommand($sql)->bindValue(':lid',$printerwayId)->bindValue(':dpid',$dpid)->queryRow();
		return $printerway['material_name'];
	}
}
