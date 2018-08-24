<?php

/**
 * This is the model class for table "nb_product_material".
 *
 * The followings are the available columns in table 'nb_product_material':
 * @property string $lid
 * @property string $dpid
 * @property string $category_id
 * @property string $create_at
 * @property string $update_at
 * @property string $material_name
 * @property string $material_identifier
 * @property string $material_private_identifier
 * @property string $stock_unit_id
 * @property string $sales_unit_id
 * @property string $stock
 * @property string $stock_cost
 * @property string $source
 * @property integer $delete_flag
 * @property string $is_sync
 */
class ProductMaterial extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_material';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, material_name, material_identifier, category_id, ', 'required'),
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, category_id, stock_unit_id, sales_unit_id', 'length', 'max'=>10),
			array('material_name, material_identifier, material_private_identifier', 'length', 'max'=>255),
			array('is_sync', 'length', 'max'=>50),
			array('create_at, source', 'safe'),
			array('category_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择品项类别')),
			array('stock_unit_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择库存单位')),
			array('sales_unit_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','请选择零售单位')),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, category_id, create_at, update_at, material_name, material_identifier, material_private_identifier, stock_unit_id, sales_unit_id, source, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
		'category' => array(self::BELONGS_TO , 'MaterialCategory' ,'','on'=> 't.category_id=category.lid and category.dpid=t.dpid'),
		'material_price' => array(self::HAS_ONE , 'ProductMaterialPrice' , '', 'on'=>'t.lid=material_price.material_id'),
		'unit' =>array(self::BELONGS_TO , 'MaterialUnit','','on'=>'t.stock_unit_id=unit.lid or t.sales_unit_id =unit.lid'),
		'material_stock' => array(self::HAS_ONE , 'ProductMaterialStock' , '', 'on'=>'t.lid=material_stock.material_id'),
		'bom' => array(self::HAS_MANY , 'ProductBom' , '', 'on'=>'t.dpid  = bom.dpid and bom.material_id=t.lid and bom.delete_flag=0'),
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
			'category_id' => '原料类别',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'material_name' => '原料名称',
			'material_identifier' => '原料编号',
			'material_private_identifier' => '店内码',
			'stock_unit_id' => '库存单位',
			'sales_unit_id' => '零售单位',
			'source' => '来演',
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
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('material_name',$this->material_name,true);
		$criteria->compare('material_identifier',$this->material_identifier,true);
		$criteria->compare('material_private_identifier',$this->material_private_identifier,true);
		$criteria->compare('stock_unit_id',$this->stock_unit_id,true);
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
	 * @return ProductMaterial the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function getJitStock($materialId,$dpid)
	{
		$sql = 'select sum(stock) as stock_all from nb_product_material_stock where dpid='.$dpid.' and material_id='.$materialId.' and delete_flag=0';
		$command = Yii::app()->db->createCommand($sql)->queryRow();
		$stockAll = $command['stock_all'];
		return $stockAll;
	}
	
}
