<?php

/**
 * This is the model class for table "nb_goods".
 *
 * The followings are the available columns in table 'nb_goods':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $category_id
 * @property string $cate_code
 * @property string $goods_code
 * @property string $goods_name
 * @property string $simple_code
 * @property string $main_picture
 * @property string $description
 * @property integer $sort
 * @property string $is_member_discount
 * @property string $is_discount
 * @property string $original_price
 * @property string $member_price
 * @property string $goods_unit
 * @property integer $store_number
 * @property integer $order_number
 * @property integer $favourite_number
 * @property string $is_show
 * @property string $is_show_wx
 * @property string $is_lock
 * @property string $delete_flag
 * @property string $is_sync
 */
class Goods extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_goods';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('goods_name, original_price, simple_code, goods_unit', 'required'),
			array('sort, store_number, order_number, favourite_number', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, category_id, member_price, original_price, goods_unit', 'length', 'max'=>10),
			array('cate_code, goods_code', 'length', 'max'=>12),
			array('goods_name, is_sync', 'length', 'max'=>50),
			array('simple_code', 'length', 'max'=>25),
			array('main_picture', 'length', 'max'=>255),
			array('is_member_discount, is_discount, is_show, is_show_wx, is_lock, delete_flag', 'length', 'max'=>2),
			array('member_price, description, main_picture, goods_code, cate_code, update_at, create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, category_id, cate_code, goods_code, goods_name, simple_code, main_picture, description, sort, is_member_discount, is_discount, original_price, member_price, goods_unit, store_number, order_number, favourite_number, is_show, is_show_wx, is_lock, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'dpid' => '仓库id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'category_id' => '商品种类',
			'cate_code' => '商品分类编码',
			'goods_code' => '商品编码',
			'goods_name' => '商品名称',
			'simple_code' => '商品简码',
			'main_picture' => '主图',
			'description' => '描述',
			'sort' => '排序',
			'is_member_discount' => '是否参与会员折扣',
			'is_discount' => '可折',
			'original_price' => '原价',
			'member_price' => '会员价格',
			'goods_unit' => '默认单位',
			'store_number' => '<0无限库存0沽清>0有限库存',
			'order_number' => '总下单次数',
			'favourite_number' => '总点赞次数',
			'is_show' => '是否显示',
			'is_show_wx' => '是否显示在微信端',
			'is_lock' => '控制价格是否锁定，0不锁定，价格可以修改；1锁定，价格不能修改。',
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
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('cate_code',$this->cate_code,true);
		$criteria->compare('goods_code',$this->goods_code,true);
		$criteria->compare('goods_name',$this->goods_name,true);
		$criteria->compare('simple_code',$this->simple_code,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('is_member_discount',$this->is_member_discount,true);
		$criteria->compare('is_discount',$this->is_discount,true);
		$criteria->compare('original_price',$this->original_price,true);
		$criteria->compare('member_price',$this->member_price,true);
		$criteria->compare('goods_unit',$this->goods_unit,true);
		$criteria->compare('store_number',$this->store_number);
		$criteria->compare('order_number',$this->order_number);
		$criteria->compare('favourite_number',$this->favourite_number);
		$criteria->compare('is_show',$this->is_show,true);
		$criteria->compare('is_show_wx',$this->is_show_wx,true);
		$criteria->compare('is_lock',$this->is_lock,true);
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
	 * @return Goods the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
