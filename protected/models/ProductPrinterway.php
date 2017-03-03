<?php

/**
 * This is the model class for table "nb_product_printerway".
 *
 * The followings are the available columns in table 'nb_product_printerway':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $printer_way_id
 * @property string $product_id
 * @property string $delete_flag
 * @property string $is_sync
 */
class ProductPrinterway extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_product_printerway';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('update_at', 'required'),
			array('lid, dpid, printer_way_id, product_id', 'length', 'max'=>10),
			array('delete_flag', 'length', 'max'=>1),
				array('is_sync','length','max'=>50),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, printer_way_id, product_id, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
			'printer_way_id' => 'nb_taste_group的ID',
			'product_id' => 'Product',
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
		$criteria->compare('printer_way_id',$this->printer_way_id,true);
		$criteria->compare('product_id',$this->product_id,true);
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
	 * @return ProductPrinterway the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
        
        public static function getPrinterwayName($printerwayId,$dpid){
		$sql = 'SELECT name from nb_printer_way where lid=:lid and dpid=:dpid';
		$printerway = Yii::app()->db->createCommand($sql)->bindValue(':lid',$printerwayId)->bindValue(':dpid',$dpid)->queryRow();
		return $printerway['name'];
	}
        
        public static function getProductPrinterWay($productId,$companyId)
	{
		$sql = 'select printer_way_id from nb_product_printerway where dpid='.$companyId.' and product_id='.$productId.' and delete_flag=0';
		$result = Yii::app()->db->createCommand($sql)->queryAll();
		return $result;
	}
        
        public static function saveProductPrinterway($dpid,$productId,$printerwayIds=array()){
		$transaction = Yii::app()->db->beginTransaction();
		try {
			$sql = 'update nb_product_printerway set delete_flag="1",update_at ="'.date("Y-m-d H:i:s",time()).'" where dpid=:dpid and product_id=:productId';
			$conn = Yii::app()->db->createCommand($sql);
			$conn->bindValue(':dpid',$dpid);
			$conn->bindValue(':productId',$productId);
			$conn->execute();
			if(!empty($printerwayIds)){                                                                    
				foreach($printerwayIds as $printerwayId){
//					$sql = 'SELECT NEXTVAL("product_printerway") AS id';
//					$maxId = Yii::app()->db->createCommand($sql)->queryRow();
                                        $se=new Sequence("product_printerway");
                                        $lid = $se->nextval();
                                        $data = array(
					 'lid'=>$lid,
					 'dpid'=>$dpid,
					 'create_at'=>date('Y-m-d H:i:s',time()),
                                         'update_at'=>date('Y-m-d H:i:s',time()),
					 'printer_way_id'=>$printerwayId,
					 'product_id'=>$productId,
                                         'delete_flag'=>"0",
					);
                                        //var_dump($data);exit;
					Yii::app()->db->createCommand()->insert('nb_product_printerway',$data);
				}
			}
			$transaction->commit(); //提交事务会真正的执行数据库操作
			return true;
		} catch (Exception $e) {
			$transaction->rollback(); //如果操作失败, 数据回滚
			return false;
		}
	}
}
