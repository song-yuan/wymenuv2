<?php

/**
 * This is the model class for table "nb_goods_material_stock".
 *
 * The followings are the available columns in table 'nb_goods_material_stock':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $goods_id
 * @property string $goods_code
 * @property integer $stock_day
 * @property string $batch_code
 * @property string $batch_stock
 * @property string $stock
 * @property string $free_stock
 * @property string $stock_cost
 * @property integer $delete_flag
 * @property string $is_sync
 */
class GoodsMaterialStock extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_goods_material_stock';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, goods_id, goods_code, stock_day, batch_code, batch_stock, free_stock', 'required'),
			array('goods_id, stock_day, delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, batch_stock, stock, free_stock, stock_cost', 'length', 'max'=>10),
			array('goods_code', 'length', 'max'=>12),
			array('batch_code', 'length', 'max'=>20),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, goods_id, goods_code, stock_day, batch_code, batch_stock, stock, free_stock, stock_cost, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'lid' => 'Lid',
			'dpid' => 'Dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'goods_id' => 'Goods',
			'goods_code' => 'Goods Code',
			'stock_day' => 'Stock Day',
			'batch_code' => 'Batch Code',
			'batch_stock' => 'Batch Stock',
			'stock' => 'Stock',
			'free_stock' => 'Free Stock',
			'stock_cost' => 'Stock Cost',
			'delete_flag' => 'Delete Flag',
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
		$criteria->compare('goods_id',$this->goods_id);
		$criteria->compare('goods_code',$this->goods_code,true);
		$criteria->compare('stock_day',$this->stock_day);
		$criteria->compare('batch_code',$this->batch_code,true);
		$criteria->compare('batch_stock',$this->batch_stock,true);
		$criteria->compare('stock',$this->stock,true);
		$criteria->compare('free_stock',$this->free_stock,true);
		$criteria->compare('stock_cost',$this->stock_cost,true);
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
	 * @return GoodsMaterialStock the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
