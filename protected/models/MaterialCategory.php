<?php

/**
 * This is the model class for table "nb_material_category".
 *
 * The followings are the available columns in table 'nb_material_category':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $pid
 * @property string $tree
 * @property string $category_name
 * @property string $main_picture
 * @property string $order_num
 * @property integer $delete_flag
 * @property string $is_sync
 */
class MaterialCategory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_material_category';
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
			array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, pid', 'length', 'max'=>10),
			array('tree, category_name, is_sync', 'length', 'max'=>50),
			array('main_picture', 'length', 'max'=>255),
			array('order_num', 'length', 'max'=>4),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('create_at, pid, order_num, delete_flag, is_sync, dpid, main_picture, category_name, lid, update_at', 'safe', 'on'=>'search'),
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
			'productMaterial'=>array(self::HAS_MANY,'ProductMaterial','','on'=>'t.lid=productMaterial.category_id and t.dpid=productMaterial.dpid'),
			'company' => array(self::BELONGS_TO , 'Company' , 'dpid'),
                //'siteNo' => array(self::HAS_ONE , 'SiteNo' , '','on'=>' t.dpid=siteNo.dpid and t.site'),
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
			'pid' => '打印机id',
			'tree' => 'Tree',
			'category_name' => '品项类别',
			'main_picture' => '图片',
			'order_num' => '显示顺序',
			'delete_flag' => '删除 0未删除 1删除',
			'is_sync' => 'Is Sync',
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
		$criteria->compare('pid',$this->pid,true);
		$criteria->compare('tree',$this->tree,true);
		$criteria->compare('category_name',$this->category_name,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('order_num',$this->order_num,true);
		$criteria->compare('delete_flag',$this->delete_flag);
		$criteria->compare('is_sync',$this->is_sync,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return MaterialCategory the static model class
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
		
		Yii::app()->db->createCommand('update '.$this->tableName().' set delete_flag=1 where lid in ('.$str.')')->execute();
		Yii::app()->db->createCommand('update nb_material_category set delete_flag=1 where lid in ('.$str.')')->execute();
	}
	/**
	 * 
	 * 获取 商品分类 一级及多级
	 * 
	 */
	public static function getCategorys($companyId = 0){
		$totalCatgorys = array();
		$command = Yii::app()->db;
		$sql = 'select lid,category_name,main_picture from nb_material_category where dpid=:companyId and pid=0 and delete_flag=0 order by order_num DESC';
		$parentCategorys = $command->createCommand($sql)->bindValue(':companyId',$companyId)->queryAll();
		foreach($parentCategorys as $category){
			$csql = 'select lid, pid, category_name from nb_material_category where dpid=:companyId and pid=:parent_id and delete_flag=0 order by order_num DESC';
			$categorys = $command->createCommand($csql)->bindValue(':companyId',$companyId)->bindValue(':parent_id',$category['lid'])->queryAll();
			$category['children'] = $categorys;
			array_push($totalCatgorys,$category);
		}
		return $totalCatgorys;
	}
}
