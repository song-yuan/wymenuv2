<?php

/**
 * This is the model class for table "nb_goods_carts".
 *
 * The followings are the available columns in table 'nb_goods_carts':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $stock_dpid
 * @property string $goods_name
 * @property string $goods_id
 * @property string $goods_code
 * @property string $material_code
 * @property integer $user_id
 * @property string $user_name
 * @property string $promotion_price
 * @property string $price
 * @property integer $num
 * @property string $end_time
 * @property string $delete_flag
 * @property string $is_sync
 */
class GoodsCarts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_goods_carts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, goods_code, material_code, user_id, user_name, price, num', 'required'),
			array('user_id, num', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, stock_dpid, goods_id, promotion_price, price', 'length', 'max'=>10),
			array('goods_name, user_name, end_time', 'length', 'max'=>30),
			array('goods_code, material_code', 'length', 'max'=>12),
			array('delete_flag', 'length', 'max'=>2),
			array('is_sync', 'length', 'max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, stock_dpid, goods_name, goods_id, goods_code, material_code, user_id, user_name, promotion_price, price, num, end_time, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'stock_dpid' => '仓库id',
			'goods_name' => '商品名称',
			'goods_id' => '商品id',
			'goods_code' => '商品编码，规则：dpid后三位+lid后四位+5位自增数字',
			'material_code' => '原料编码',
			'user_id' => '添加人',
			'user_name' => '添加人名称',
			'promotion_price' => '活动价',
			'price' => '原价',
			'num' => '数量',
			'end_time' => '产品过期时间',
			'delete_flag' => 'Delete Flag',
			'is_sync' => '同步标志',
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
		$criteria->compare('stock_dpid',$this->stock_dpid,true);
		$criteria->compare('goods_name',$this->goods_name,true);
		$criteria->compare('goods_id',$this->goods_id,true);
		$criteria->compare('goods_code',$this->goods_code,true);
		$criteria->compare('material_code',$this->material_code,true);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('user_name',$this->user_name,true);
		$criteria->compare('promotion_price',$this->promotion_price,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('num',$this->num);
		$criteria->compare('end_time',$this->end_time,true);
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
	 * @return GoodsCarts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
