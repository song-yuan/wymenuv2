<?php

/**
 * This is the model class for table "nb_product_picture".
 *
 * The followings are the available columns in table 'nb_product_picture':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $product_id
 * @property string $is_set
 * @property string $pic_path
 * @property integer $pic_show_order
 * @property string $delete_flag
 */
class ProductPicture extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_picture';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at, pic_path', 'required'),
			array('pic_show_order', 'numerical', 'integerOnly'=>true),
			array('lid, dpid, product_id', 'length', 'max'=>10),
			array('is_set, delete_flag', 'length', 'max'=>1),
			array('pic_path', 'length', 'max'=>255),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, product_id, is_set, pic_path, pic_show_order, delete_flag', 'safe', 'on'=>'search'),
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
			'product_id' => 'Product',
			'is_set' => '0上面的product_id是单品，1product_id是套餐',
			'pic_path' => 'Pic Path',
			'pic_show_order' => 'Pic Show Order',
			'delete_flag' => '1删除，0未删除',
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
		$criteria->compare('product_id',$this->product_id,true);
		$criteria->compare('is_set',$this->is_set,true);
		$criteria->compare('pic_path',$this->pic_path,true);
		$criteria->compare('pic_show_order',$this->pic_show_order);
		$criteria->compare('delete_flag',$this->delete_flag,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ProductPicture the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
	public static function saveImg($dpid,$productId,$pictures)
	{
		$db = Yii::app()->db;
		$sql = 'delete from nb_product_picture where product_id='.$productId;
		$db->createCommand($sql)->execute();
		if(!empty($pictures)){
			foreach($pictures as $pic){
				$se=new Sequence("product_picture");
                $lid = $se->nextval();
				$data=array(
					'lid'=>$lid,
					'dpid'=>$dpid,
					'create_at'=>date('Y-m-d H:i:s',time()),
                                        'update_at'=>date('Y-m-d H:i:s',time()),
					'is_set'=>0,
					'product_id'=>$productId,
					'pic_path'=>$pic,
				);
				$db->createCommand()->insert('nb_product_picture',$data);
			}
			return true;
		}
		return false;
	}
}
