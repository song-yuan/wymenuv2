<?php

/**
 * This is the model class for table "nb_cart".
 *
 * The followings are the available columns in table 'nb_cart':
 * @property string $cart_id
 * @property string $product_id
 * @property string $company_id
 * @property string $code
 * @property string $create_time
 */
class Cart extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_cart';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('product_id, product_num, code', 'required'),
			array('product_id, company_id, code, product_num, create_time', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('cart_id, product_id, company_id, code, product_num, create_time', 'safe', 'on'=>'search'),
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
		'product'=>array(self::HAS_ONE,'Product','','on'=>'t.product_id=product.product_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'cart_id' => 'Cart',
			'product_id' => 'Product',
			'company_id' => 'Company',
			'code' => 'Code',
			'product_num' => 'Product Num',
			'create_time' => 'Create Time',
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

		$criteria->compare('cart_id',$this->cart_id,true);
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('code',$this->code,true);
		$criteria->compare('product_num',$this->prodcut_num,true);
		$criteria->compare('create_time',$this->create_time,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Cart the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	static public function getCartProducts($companyId,$code){
		$db = Yii::app()->db;
		$sql = "select t.*,t1.*,t2.category_name from nb_cart t
				left join nb_product t1 on t.product_id = t1.product_id
				left join nb_product_category t2 on t1.category_id = t2.category_id
				where t.company_id=:companyId and code=:code";
		return $db->createCommand($sql)->bindValue(':companyId' , $companyId)->bindValue(':code' , $code)->queryAll();
	}
}
