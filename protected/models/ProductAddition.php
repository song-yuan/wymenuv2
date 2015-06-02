<?php

/**
 * This is the model class for table "nb_product_addition".
 *
 * The followings are the available columns in table 'nb_product_addition':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $mproduct_id
 * @property string $sproduct_id
 * @property string $price
 * @property integer $number
 * @property string $delete_flag
 */
class ProductAddition extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_addition';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid,dpid', 'required'),
			array('number', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, mproduct_id, sproduct_id, price', 'length', 'max'=>10),
			array('delete_flag', 'length', 'max'=>1),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, mproduct_id, sproduct_id, price, number, delete_flag', 'safe', 'on'=>'search'),
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
                     'mproduct' => array(self::HAS_ONE , 'Product' , '' , 'on' => ' t.mproduct_id=mproduct.lid and t.dpid=mproduct.dpid'),
                     'sproduct' => array(self::HAS_ONE , 'Product' , '' , 'on' => ' t.sproduct_id=sproduct.lid and t.dpid=sproduct.dpid')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
                        'category_id' => '菜品种类',
			'lid' => '自身id，统一dpid下递增',
			'dpid' => '店铺id',
			'create_at' => 'Create At',
			'update_at' => '更新时间',
			'mproduct_id' => yii::t('app','主产品'),
			'sproduct_id' => yii::t('app','附加菜品'),
			'price' => yii::t('app','附加菜价格'),
			'number' =>yii::t('app', '单次下单个数'),
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
		$criteria->compare('mproduct_id',$this->mproduct_id,true);
		$criteria->compare('sproduct_id',$this->sproduct_id,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('number',$this->number);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductAddition the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
