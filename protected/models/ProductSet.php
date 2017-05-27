<?php

/**
 * This is the model class for table "nb_product_set".
 *
 * The followings are the available columns in table 'nb_product_set':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $category_id
 * @property string $set_name
 * @property string $simple_code
 * @property string $main_picture
 * @property string $description
 * @property integer $rank
 * @property string $is_member_discount
 * @property string $is_special
 * @property string $is_discount
 * @property string $status
 * @property integer $order_number
 * @property integer $store_number
 * @property integer $favourite_number
 * @property integer $is_show
 * @property integer $is_show_wx
 * @property integer $is_lock
 * @property string $delete_flag
 * @property string $is_sync
 */
class ProductSet extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_set';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('set_name, lid', 'required'),
			array('rank, order_number, favourite_number', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, set_price, member_price,category_id', 'length', 'max'=>10),
			array('set_name, is_sync', 'length', 'max'=>50),
			array('simple_code', 'length', 'max'=>25),
			array('main_picture', 'length', 'max'=>255),
			array('type, is_show, is_show_wx, is_lock', 'length', 'max'=>2),
			array('is_member_discount, is_special, is_discount, status, delete_flag', 'length', 'max'=>1),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, category_id, set_name, type, simple_code, main_picture, set_price, member_price, description, rank, is_member_discount, is_special, is_discount, status,store_number, order_number, favourite_number, is_show,is_show_wx,is_lock, delete_flag, is_sync', 'safe', 'on'=>'search'),

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
                     'productsetdetail' => array(self::HAS_MANY , 'ProductSetDetail' ,'' ,'on'=>'t.lid = productsetdetail.set_id and t.dpid = productsetdetail.dpid and productsetdetail.delete_flag=0 '),
				'PrivatePromotionDetail' => array(self::BELONGS_TO , 'PrivatePromotionDetail' ,'','on'=> 't.lid=PrivatePromotionDetail.product_id and PrivatePromotionDetail.dpid=t.dpid and PrivatePromotionDetail.delete_flag=0'),		
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
			'category_id' => '套餐分类',
			'set_name' => yii::t('app','套餐名称'),
			'type' => yii::t('app','套餐类型'),
			'simple_code' => 'Simple Code',
			'main_picture' => yii::t('app','主图片'),
			'set_price' => yii::t('app','套餐基础价格'),
			'member_price' => yii::t('app','套餐基础价格（会员）'),
			'description' => yii::t('app','描述'),
			'rank' => yii::t('app','推荐星级'),
			'is_member_discount' => yii::t('app','是否参与会员折扣'),
			'is_special' => yii::t('app','是否特价菜'),
			'is_discount' => yii::t('app','可折'),
			'status' => yii::t('app','是否沽清'),
            'store_number' =>yii::t('app', '库存数量'),
			'order_number' => yii::t('app','总下单次数'),
			'favourite_number' => yii::t('app','总点赞次数'),
			'is_show' => yii::t('app','是否只在活动中售卖'),
			'is_show_wx' => yii::t('app','是否在微信端显示'),
			'is_lock' => yii::t('app','是否锁定价格'),
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
		$criteria->compare('set_name',$this->set_name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('simple_code',$this->simple_code,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('set_price',$this->set_price,true);
		$criteria->compare('member_price',$this->member_price,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('is_member_discount',$this->is_member_discount,true);
		$criteria->compare('is_special',$this->is_special,true);
		$criteria->compare('is_discount',$this->is_discount,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('order_number',$this->order_number);
        $criteria->compare('store_number',$this->store_number);
		$criteria->compare('favourite_number',$this->favourite_number);
		$criteria->compare('is_show',$this->is_show);
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
	 * @return ProductSet the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
