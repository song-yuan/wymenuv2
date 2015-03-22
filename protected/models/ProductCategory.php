<?php

/**
 * This is the model class for table "nb_product_category".
 *
 * The followings are the available columns in table 'nb_product_category':
 * @property string $category_id
 * @property integer $pid
 * @property string $tree
 * @property string $category_name
 * @property string $company_id
 * @property integer $delete_flag
 */
class ProductCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid,dpid , category_name', 'required'),
			array('pid,delete_flag', 'numerical', 'integerOnly'=>true),
			array('category_name', 'length','min'=>2, 'max'=>45),
			array('dpid', 'length', 'max'=>10),
                        array('tree', 'length', 'max'=>50),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, create_at,tree, category_name, dpid, delete_flag', 'safe', 'on'=>'search'),
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
		'product'=>array(self::HAS_MANY,'Product','','on'=>'t.lid=product.category_id and t.dpid=product.dpid'),
		'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'lid' => 'Category',
			'pid'=>'PID',
			'tree'=>'Tree',
			'category_name' => '产品类别',
			'dpid' => '公司',
			'delete_flag' => '状态',
                        'create_at' => '更新时间',
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
		$criteria->compare('category_name',$this->category_name,true);
		$criteria->compare('dpid',$this->dpid,true);
                $criteria->compare('pid',$this->pid,true);
                $criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('delete_flag',$this->delete_flag);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function deleteCategory(){
		$db = Yii::app()->db;
		$categoryIds = $db->createCommand('select lid from '.$this->tableName().' where tree like :categoryTree')->bindValue(':categoryTree',$this->tree.','.'%')->queryColumn();
		$categoryIds[] = $this->lid;
		
		$str = implode(',',$categoryIds);
		
		Yii::app()->db->createCommand('update '.$this->tableName().' set delete_flag=1 where dpid='.$this->dpid.' and lid in ('.$str.')')->execute();
		Yii::app()->db->createCommand('update nb_product set delete_flag=1 where dpid='.$this->dpid.' and category_id in ('.$str.')')->execute();
	}
}
