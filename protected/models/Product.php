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
 * @property integer $sort
 * @property integer $spicy
 * @property string $is_temp_price
 * @property string $is_member_discount
 * @property string $is_special
 * @property string $is_discount
 * @property string $status
 * @property string $original_price
 * @property string $member_price
 * @property string $dabao_fee
 * @property string $product_unit
 * @property string $weight_unit
 * @property string $is_weight_confirm
 * @property integer $store_number
 * @property integer $order_number
 * @property integer $favourite_number
 * @property string $printer_way_id
 * @property string $is_show
 * @property string $is_show_wx
 * @property string $is_lock
 * @property string $delete_flag
 * @property string $is_sync
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
			array('product_name, simple_code , category_id', 'required'),
			array('rank, spicy, order_number,category_id, favourite_number', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, category_id, original_price, member_price, dabao_fee, product_unit, weight_unit, printer_way_id', 'length', 'max'=>10),
			array('product_name, is_sync', 'length', 'max'=>50),
			array('simple_code', 'length', 'max'=>25),
			array('chs_code, phs_code', 'length', 'max'=>12),
			array('sort', 'length', 'max'=>4),
			array('main_picture', 'length', 'max'=>255),
            array('category_id','compare','compareValue'=>'0','operator'=>'>','message'=>yii::t('app','必须选择二级产品分类')),
			array('is_temp_price, is_member_discount, is_special, is_discount, status, is_weight_confirm, is_show, delete_flag', 'length', 'max'=>1),
			array('is_show_wx, is_lock', 'length', 'max'=>2),
			array('create_at,description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, category_id,chs_code,phs_code, product_name, simple_code, main_picture, description, rank, sort, spicy, is_temp_price, is_member_discount, is_special, is_discount, status, original_price, member_price, dabao_fee, product_unit, weight_unit, is_weight_confirm, store_number, order_number, favourite_number, printer_way_id, is_show, is_show_wx, is_lock, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'category' => array(self::BELONGS_TO , 'ProductCategory' ,'','on'=> 't.category_id=category.lid and category.dpid=t.dpid'),
			'productTaste' => array(self::HAS_MANY , 'ProductTaste' ,'','on'=>'t.lid=productTaste.product_id and t.dpid=productTaste.dpid and productTaste.delete_flag=0'),
			'productPrinterway' => array(self::HAS_MANY , 'ProductPrinterway' ,'','on'=>'t.lid=productPrinterway.product_id and t.dpid=productPrinterway.dpid and productPrinterway.delete_flag=0'),
			'productAddition' => array(self::HAS_MANY , 'ProductAddition' ,'','on'=>'t.lid=productAddition.mproduct_id and t.dpid=productAddition.dpid and productAddition.delete_flag=0'),
			'printerWay' => array(self::HAS_ONE , 'PrinterWay' ,'','on'=>'t.printer_way_id=printerWay.lid and t.dpid=printerWay.dpid and printerWay.delete_flag=0'),
			'productImg' => array(self::HAS_MANY , 'ProductPicture' ,'','on'=>'t.lid=productImg.product_id and t.dpid=productImg.dpid and productImg.delete_flag=0'),
			'PrivatePromotionDetail' => array(self::BELONGS_TO , 'PrivatePromotionDetail' ,'','on'=> 't.lid=PrivatePromotionDetail.product_id and PrivatePromotionDetail.dpid=t.dpid and PrivatePromotionDetail.delete_flag=0'),
			'productbom' => array(self::HAS_MANY , 'ProductBom' ,'','on'=> 't.lid=productbom.product_id and productbom.dpid=t.dpid and productbom.delete_flag=0'),
			
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
			'category_id' => yii::t('app','种类'),
			'chs_code' => yii::t('app','分类编码'),
			'phs_code' => yii::t('app','产品编码'),
			'product_name' => yii::t('app','产品名称'),
			'simple_code' => 'Simple Code',
			'main_picture' =>yii::t('app', '产品主图片'),
			'description' =>yii::t('app', '描述'),
			'rank' =>yii::t('app', '推荐星级'),
			'sort' =>yii::t('app', '默认排序'),
			'spicy' =>yii::t('app', '辣度等级'),
			'is_temp_price' =>yii::t('app', '是否时价菜'),
			'is_member_discount' =>yii::t('app', '是否参与会员折扣'),
			'is_special' =>yii::t('app', '是否特价菜'),
			'is_discount' =>yii::t('app', '可折'),
			'status' =>yii::t('app', '状态'),
			'original_price' =>yii::t('app', '价格'),
			'member_price' =>yii::t('app', '会员价格'),
			'dabao_fee' =>yii::t('app', '打包费'),
			'product_unit' =>yii::t('app', '默认单位'),
			'weight_unit' =>yii::t('app', '重量单位'),
			'is_weight_confirm' =>yii::t('app', '是否需要确认重量'),
			'store_number' =>yii::t('app', '库存数量'),
            'order_number' =>yii::t('app', '总下单次数'),
			'favourite_number' =>yii::t('app', '总点赞次数'),
			'printer_way_id' => '打印方案id',
			'is_show' =>yii::t('app', '可售'),
			'is_show_wx' =>yii::t('app', '是否在微店显示'),
			'is_lock' =>yii::t('app', '是否锁定价格'),
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
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('chs_code',$this->category_id,true);
		$criteria->compare('phs_code',$this->category_id,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('simple_code',$this->simple_code,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('sort',$this->sort);
		$criteria->compare('spicy',$this->spicy,true);
		$criteria->compare('is_temp_price',$this->is_temp_price,true);
		$criteria->compare('is_member_discount',$this->is_member_discount,true);
		$criteria->compare('is_special',$this->is_special,true);
		$criteria->compare('is_discount',$this->is_discount,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('original_price',$this->original_price,true);
		$criteria->compare('member_price',$this->member_price,true);
		$criteria->compare('dabao_fee',$this->dabao_fee,true);
		$criteria->compare('product_unit',$this->product_unit,true);
		$criteria->compare('weight_unit',$this->weight_unit,true);
		$criteria->compare('is_weight_confirm',$this->is_weight_confirm,true);
		$criteria->compare('order_number',$this->order_number);
        $criteria->compare('store_number',$this->store_number);
		$criteria->compare('favourite_number',$this->favourite_number);
		$criteria->compare('printer_way_id',$this->printer_way_id,true);
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
	 * @return Product the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
