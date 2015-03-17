<?php

/**
 * This is the model class for table "nb_product_set_detail".
 *
 * The followings are the available columns in table 'nb_product_set_detail':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $set_id
 * @property string $product_id
 * @property string $price
 * @property integer $group_no
 * @property integer $number
 * @property string $is_select
 * @property string $delete_flag
 */
class ProductSetDetail extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_set_detail';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid', 'required'),
			array('group_no, number', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, set_id, product_id, price', 'length', 'max'=>10),
			array('is_select, delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, set_id, product_id, price, group_no, number, is_select, delete_flag', 'safe', 'on'=>'search'),
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
                    'product' => array(self::HAS_ONE , 'Product' , '' , 'on' => 't.product_id=product.lid and t.dpid=product.dpid')
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
			'set_id' => 'Set',
			'product_id' => 'Product',
			'price' => '菜品在套餐中的价格，可能是打折过的，总价根据这个价格累加计算而得，但是这个明细价格前台不显示，前台只显示套餐总价，所以可以随便设定，比如把第一个固定菜品价格设定成菜单总价，其他设定成0也可以。当套餐中有可选项且价格不一致时，可选项变化套餐总价也变化',
			'group_no' => '一个套餐中有多组产品，如：主食一组、饮料一组，一般一组就一个，也有一组中有多个可供客户选择的。',
			'number' => '套餐中默认数量',
			'is_select' => '同一组中有多个选择时，那个产品时默认选中的这个字段为1，否则为0',
			'delete_flag' => 'Delete Flag',
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
		$criteria->compare('set_id',$this->set_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('group_no',$this->group_no);
		$criteria->compare('number',$this->number);
		$criteria->compare('is_select',$this->is_select,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductSetDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
