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
 * @property string $is_sync
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
			array('lid, dpid, ', 'required'),
			//array('delete_flag', 'numerical', 'integerOnly'=>true),
			array('category_name', 'length','min'=>2, 'max'=>45,),
			//	array('category_name' , 'unique' , 'message'=>'类别名已经存在'),
			array('pid,dpid', 'length', 'max'=>10),
			array('cate_type', 'length', 'max'=>2),
            array('order_num', 'length', 'max'=>4),
            array('type', 'length', 'max'=>3),
			array('delete_flag', 'length', 'max'=>1),
            array('tree, is_sync', 'length', 'max'=>50),
			array('chs_code', 'length', 'max'=>12),
            array('main_picture', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, order_num, create_at, tree, category_name, chs_code, main_picture, type, cate_type, dpid, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'category_name' => yii::t('app','产品类别'),
			'main_picture' => yii::t('app','类别图片'),
            'type' => yii::t('app','是否参与排名'),
			'cate_type' => yii::t('app','选择分类类型'),
			'dpid' => yii::t('app','公司'),
            'order_num' => yii::t('app','显示顺序'),
			'delete_flag' => yii::t('app','状态'),
            'create_at' => yii::t('app','更新时间'),
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
		$criteria->compare('category_name',$this->category_name,true);
		$criteria->compare('main_picture',$this->main_picture,true);
		$criteria->compare('dpid',$this->dpid,true);
        $criteria->compare('pid',$this->pid,true);
        $criteria->compare('type',$this->type,true);
        $criteria->compare('cate_type',$this->cate_type,true);
        $criteria->compare('order_num',$this->order_num,true);
        $criteria->compare('create_at',$this->create_at,true);
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
	 * @return ProductCategory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public function checkCategory(){
		$db = Yii::app()->db;
		if($this->pid > 0){
			$sql = 'select * from nb_product where dpid='.$this->dpid.' and category_id='.$this->lid.' and delete_flag=0';
			$categoryProducts = $db->createCommand($sql)->queryAll();
		}else{
			$sql = 'select lid from nb_product_category where dpid='.$this->dpid.' and pid='.$this->lid;
			$category = $categoryProducts = $db->createCommand($sql)->queryColumn();
			if(empty($category)){
				$str = 0;
			}else{
				$str = implode(',',$category);
			}
			$sql = 'select * from nb_product where dpid='.$this->dpid.' and category_id in ('.$str.') and delete_flag=0';
			$categoryProducts = $db->createCommand($sql)->queryAll();
		}
		
		if(empty($categoryProducts)){
			return true;
		}else{
			return false;
		}
	}
	public function deleteCategory(){
		$db = Yii::app()->db;
		$categoryIds = $db->createCommand('select lid from '.$this->tableName().' where tree like :categoryTree')->bindValue(':categoryTree',$this->tree.','.'%')->queryColumn();
		$categoryIds[] = $this->lid;
		
		$str = implode(',',$categoryIds);
		
		Yii::app()->db->createCommand('update '.$this->tableName().' set delete_flag=1 where lid in ('.$str.')')->execute();
		Yii::app()->db->createCommand('update nb_product set delete_flag=1 where lid in ('.$str.')')->execute();
	}
	/**
	 * 
	 * 获取 商品分类 一级及多级
	 * 
	 */
	public static function getCategorys($companyId = 0){
		$totalCatgorys = array();
		$command = Yii::app()->db;
		$sql = 'select lid,category_name,main_picture from nb_product_category where dpid=:companyId and pid=0 and delete_flag=0 order by order_num DESC';
		$parentCategorys = $command->createCommand($sql)->bindValue(':companyId',$companyId)->queryAll();
		foreach($parentCategorys as $category){
			$csql = 'select lid, pid, category_name from nb_product_category where dpid=:companyId and pid=:parent_id and delete_flag=0 order by order_num DESC';
			$categorys = $command->createCommand($csql)->bindValue(':companyId',$companyId)->bindValue(':parent_id',$category['lid'])->queryAll();
			$category['children'] = $categorys;
			array_push($totalCatgorys,$category);
		}
		return $totalCatgorys;
	}
	
	
	static public function getChscode($dpid,$lid,$id)
	{
// 		var_dump($dpid,$id);exit;
		$ret = substr("0000000000".$dpid,-3).substr("0000000000".$lid,-4).substr("0000000000".$id,-5);
		//var_dump($ret);exit;
			//$ret=substr(date('Ymd',time()),-6).substr("0000000000".$orderid, -6);
		
		return $ret;
	}
}
