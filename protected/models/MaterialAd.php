<?php

/**
 * This is the model class for table "nb_material_ad".
 *
 * The followings are the available columns in table 'nb_material_ad':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property string $name
 * @property string $main_picture
 * @property string $description
 * @property integer $sort
 * @property string $is_show
 * @property string $delete_flag
 * @property string $is_sync
 */
class MaterialAd extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'nb_material_ad';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('update_at, name, main_picture, description', 'required'),
            array('sort', 'numerical', 'integerOnly'=>true),
            array('lid, dpid', 'length', 'max'=>10),
            array('name, is_sync', 'length', 'max'=>50),
            array('main_picture', 'length', 'max'=>255),
            array('is_show, delete_flag', 'length', 'max'=>1),
            array('create_at', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('lid, dpid, create_at, update_at, name, main_picture, description, sort, is_show, delete_flag, is_sync', 'safe', 'on'=>'search'),
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
            'name' => '标题',
            'main_picture' => '主图片',
            'description' => '描述',
            'sort' => '手机端排序，越小越靠前',
            'is_show' => '是否在手机原料商城显示，1显示，0不显示',
            'delete_flag' => 'Delete Flag',
            'is_sync' => '同步标志',
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
        $criteria->compare('name',$this->name,true);
        $criteria->compare('main_picture',$this->main_picture,true);
        $criteria->compare('description',$this->description,true);
        $criteria->compare('sort',$this->sort);
        $criteria->compare('is_show',$this->is_show,true);
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
     * @return MaterialAd the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}