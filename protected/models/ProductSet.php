<?php

/**
 * This is the model class for table "nb_product_set".
 *
 * The followings are the available columns in table 'nb_product_set':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
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
 * @property string $delete_flag
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
			array('lid, dpid', 'length', 'max'=>10),
			array('set_name', 'length', 'max'=>50),
			array('simple_code', 'length', 'max'=>25),
			array('main_picture', 'length', 'max'=>255),
			array('is_member_discount, is_special, is_discount, status, delete_flag', 'length', 'max'=>1),
			array('description', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, set_name, simple_code, main_picture, description, rank, is_member_discount, is_special, is_discount, status,store_number, order_number, favourite_number, delete_flag', 'safe', 'on'=>'search'),

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
			'set_name' => yii::t('app','套餐名称'),
			'simple_code' => 'Simple Code',
			'main_picture' => yii::t('app','主图片'),
			'description' => yii::t('app','描述'),
			'rank' => yii::t('app','推荐星级'),
			'is_member_discount' => yii::t('app','是否参与会员折扣'),
			'is_special' => yii::t('app','是否特价菜'),
			'is_discount' => yii::t('app','是否参与优惠活动'),
			'status' => yii::t('app','是否沽清'),
                        'store_number' =>yii::t('app', '库存数量'),
			'order_number' => yii::t('app','总下单次数'),
			'favourite_number' => yii::t('app','总点赞次数'),
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
		$criteria->compare('set_name',$this->set_name,true);
		$criteria->compare('simple_code',$this->simple_code,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('description',$this->description,true);
		$criteria->compare('rank',$this->rank);
		$criteria->compare('is_member_discount',$this->is_member_discount,true);
		$criteria->compare('is_special',$this->is_special,true);
		$criteria->compare('is_discount',$this->is_discount,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('order_number',$this->order_number);
                $criteria->compare('store_number',$this->store_number);
		$criteria->compare('favourite_number',$this->favourite_number);
		$criteria->compare('delete_flag',$this->delete_flag,true);

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
