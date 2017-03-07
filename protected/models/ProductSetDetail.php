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
 * @property string $is_sync
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
				array('is_sync','length','max'=>50),
			array('product_id','compare','compareValue'=>'0','operator'=>'>','message'=>'必须选择产品'),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, set_id, product_id, price, group_no, number, is_select, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
                    'product' => array(self::HAS_ONE , 'Product' , '' , 'on' => ' t.product_id=product.lid and t.dpid=product.dpid')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                        'category_id' => '产品种类',
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'set_id' => yii::t('app','套餐名称'),
			'product_id' => yii::t('app','产品名称'),
			'price' =>yii::t('app', '菜品差额'),
			'group_no' =>yii::t('app', '分组号'),
			'number' =>yii::t('app', '数量'),
			'is_select' =>yii::t('app', '组中默认项'),
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
		$criteria->compare('set_id',$this->set_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('group_no',$this->group_no);
		$criteria->compare('number',$this->number);
		$criteria->compare('is_select',$this->is_select,true);
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
	 * @return ProductSetDetail the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
