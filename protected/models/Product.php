<?php

/**
 * This is the model class for table "nb_product".
 *
 * The followings are the available columns in table 'nb_product':
 * @property string $product_id
 * @property string $category_id
 * @property string $product_name
 * @property string $main_picture
 * @property string $description
 * @property string $company_id
 * @property string $create_time
 * @property integer $delete_flag
 * @property string $origin_price
 * @property string $price
 * @property integer $recommend
 * @property integer $department_id
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
			array('delete_flag, recommend', 'numerical', 'integerOnly'=>true),
			array('category_id , company_id , product_name , main_picture , origin_price , price ,department_id,description' , 'required'),
			array('category_id, company_id, create_time', 'length', 'max'=>10),
			array('product_name, main_picture', 'length', 'max'=>255),
			array('origin_price, price', 'length', 'max'=>12),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('product_id, category_id, product_name, main_picture, company_id, create_time, delete_flag, origin_price, price, recommend', 'safe', 'on'=>'search'),
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
				'company' => array(self::BELONGS_TO , 'Company' , 'company_id'),
				'category' => array(self::BELONGS_TO , 'ProductCategory' , 'category_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'product_id' => 'Product',
			'category_id' => '分类',
			'product_name' => '产品名称',
			'main_picture' => '主图片',
			'description' => '描述',
			'company_id' => '公司',
			'create_time' => '创建时间',
			'delete_flag' => '状态',
			'origin_price' => '原价',
			'price' => '售价',
			'recommend' => '推荐状态',
			'department_id'=> '操作间'
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

		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('category_id',$this->category_id,true);
		$criteria->compare('product_name',$this->product_name,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('company_id',$this->company_id,true);
		$criteria->compare('create_time',$this->create_time,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('origin_price',$this->origin_price,true);
		$criteria->compare('price',$this->price,true);
		$criteria->compare('recommend',$this->recommend);

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
