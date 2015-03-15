<?php

/**
 * This is the model class for table "nb_product".
 *
 * The followings are the available columns in table 'nb_product':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $category_id
 * @property string $product_name
 * @property string $simple_code
 * @property string $main_picture
 * @property string $description
 * @property integer $rank
 * @property string $is_temp_price
 * @property string $is_member_discount
 * @property string $is_special
 * @property string $is_discount
 * @property string $status
 * @property string $original_price
 * @property string $product_unit
 * @property string $weight_unit
 * @property string $is_weight_confirm
 * @property integer $order_number
 * @property integer $favourite_number
 * @property string $printer_way_id
 * @property string $is_show
 * @property string $delete_flag
 */
class Product extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_name, simple_code, main_picture', 'required'),
			array('rank, order_number, favourite_number', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, category_id, original_price, product_unit, weight_unit, printer_way_id', 'length', 'max'=>10),
			array('product_name', 'length', 'max'=>50),
			array('simple_code', 'length', 'max'=>25),
			array('main_picture', 'length', 'max'=>255),
			array('is_temp_price, is_member_discount, is_special, is_discount, status, is_weight_confirm, is_show, delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, category_id, product_name, simple_code, main_picture, description, rank, is_temp_price, is_member_discount, is_special, is_discount, status, original_price, product_unit, weight_unit, is_weight_confirm, order_number, favourite_number, printer_way_id, is_show, delete_flag', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO , 'ProductCategory' , 'category_id'),
			'productTaste' => array(self::HAS_MANY , 'ProductTaste' ,'','on'=>'t.lid=productTaste.product_id and productTaste.delete_flag=0'),
			'printerWay' => array(self::HAS_ONE , 'PrinterWay' ,'','on'=>'t.printer_way_id=printerWay.lid and printerWay.delete_flag=0'),
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
			'category_id' => '种类id',
			'product_name' => '产品名称',
			'simple_code' => 'Simple Code',
			'main_picture' => 'Main Picture',
			'description' => 'Description',
			'rank' => '产品星级，商家自己从1-5评星',
			'is_temp_price' => '是否时价',
			'is_member_discount' => '是否参与会员折扣',
			'is_special' => '是否特价菜',
			'is_discount' => '是否参与优惠活动',
			'status' => '0正常，1沽清',
			'original_price' => 'Original Price',
			'product_unit' => '默认单位',
			'weight_unit' => '重量单位',
			'is_weight_confirm' => '是否需要确认重量',
			'order_number' => '总下单次数',
			'favourite_number' => '总点赞次数',
			'printer_way_id' => '打印方案id',
			'is_show' => '是否在正常分类显示，单在活动、套餐等中总显示，1显示，0不显示',
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
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('simple_code',$this->simple_code,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('is_temp_price',$this->is_temp_price,true);
		$criteria->compare('is_member_discount',$this->is_member_discount,true);
		$criteria->compare('is_special',$this->is_special,true);
		$criteria->compare('is_discount',$this->is_discount,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('original_price',$this->original_price,true);
		$criteria->compare('product_unit',$this->product_unit,true);
		$criteria->compare('weight_unit',$this->weight_unit,true);
		$criteria->compare('is_weight_confirm',$this->is_weight_confirm,true);
		$criteria->compare('order_number',$this->order_number);
		$criteria->compare('favourite_number',$this->favourite_number);
		$criteria->compare('printer_way_id',$this->printer_way_id,true);
		$criteria->compare('is_show',$this->is_show,true);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
