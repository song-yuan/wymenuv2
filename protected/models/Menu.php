<?php

/**
 * This is the model class for table "nb_menu".
 *
 * The followings are the available columns in table 'nb_menu':
 * @property string $lid
 * @property string $dpid
 * @property string $create_at
 * @property string $update_at
 * @property integer $horizontal
 * @property integer $vertical
 * @property string $name
 * @property integer $type
 * @property string $value
 */
class Menu extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Menu the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'nb_menu';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('lid, dpid, create_at, update_at, horizontal, vertical, name, type', 'required'),
			array('horizontal, vertical, type', 'numerical', 'integerOnly'=>true),
			array('lid, dpid', 'length', 'max'=>10),
			array('name', 'length', 'max'=>40),
			array('value', 'length', 'max'=>255),
			array('create_at', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('lid, dpid, create_at, update_at, horizontal, vertical, name, type, value', 'safe', 'on'=>'search'),
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
			'lid' => 'Lid',
			'dpid' => 'Dpid',
			'create_at' => 'Create At',
			'update_at' => 'Update At',
			'horizontal' => 'Horizontal',
			'vertical' => 'Vertical',
			'name' => 'Name',
			'type' => 'Type',
			'value' => 'Value',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('lid',$this->lid,true);
		$criteria->compare('dpid',$this->dpid,true);
		$criteria->compare('create_at',$this->create_at,true);
		$criteria->compare('update_at',$this->update_at,true);
		$criteria->compare('horizontal',$this->horizontal);
		$criteria->compare('vertical',$this->vertical);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public static function getMenuList($brandId){
		$mainMenu = self::getMainMenu($brandId);
		if($mainMenu){
			foreach($mainMenu as $key=>$val){
				$mainMenu[$key]['children'] = self::getChildren($brandId,$val['horizontal']);
			}
		}
		return $mainMenu;
	}
	public static function getMainMenu($brandId){
		$sql = "select * from nb_menu where dpid=:brandId and vertical=0";
		return Yii::app()->db->createCommand($sql)->bindValue(':brandId',$brandId)->queryAll();
	}
	public static function getChildren($brandId,$horizontal){
		$sql = "select * from nb_menu where dpid=:brandId and horizontal=:horizontal and vertical >0 order by vertical asc";
		return Yii::app()->db->createCommand($sql)->bindValues(array(':brandId'=>$brandId,':horizontal'=>$horizontal))->queryAll();
	}
	
	//得到菜单 json格式
	public static function getMenuJson($brandId){
		
		$arrMenu['button']=array();
		$menuList = self::getMenuList($brandId);
		$i=0;
		foreach($menuList as $value){
			if(empty($value['children'])){ 
				if($value['type']==1){
					$arrMenu['button'][$i]['type'] = "view";
					$arrMenu['button'][$i]['name'] = $value['name'];
					$arrMenu['button'][$i]['url'] = $value['value'];
				}elseif($value['type']==2){
					$arrMenu['button'][$i]['type'] =" click";
					$arrMenu['button'][$i]['name'] = $value['name'];
					$arrMenu['button'][$i]['key'] = $value['value'] ? $value['value']:"WEIQUAN";
				}
			}else{
				$arrMenu['button'][$i]['name'] = $value['name'];
				$j=0;
				$sort_children = self::array_sort($value['children'],'vertical','desc');
				foreach($sort_children as $svalue){
					if($svalue['type']==2){
						$arrMenu['button'][$i]['sub_button'][$j]['type'] = "click";
						$arrMenu['button'][$i]['sub_button'][$j]['name'] = $svalue['name'];
						$arrMenu['button'][$i]['sub_button'][$j]['key'] = $svalue['value'] ? $svalue['value']:"WEIQUAN";
					}elseif($svalue['type']==1){
						$arrMenu['button'][$i]['sub_button'][$j]['type'] = "view";
						$arrMenu['button'][$i]['sub_button'][$j]['name'] = $svalue['name'];
						$arrMenu['button'][$i]['sub_button'][$j]['url'] = $value['value'];
					}
					$j++;
				}
			}
			$i++;
		}
		return json_encode($arrMenu,JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
	}
	public static function array_sort($array,$keys,$type='asc'){
	    if(!isset($array) || !is_array($array) || empty($array)){
	        return '';
	    }
	    if(!isset($keys) || trim($keys)==''){
	        return '';
	    }
	    if(!isset($type) || $type=='' || !in_array(strtolower($type),array('asc','desc'))){
	        return '';
	    }
	    $keysvalue=array();
	    foreach($array as $key=>$val){
	        $val[$keys] = str_replace('-','',$val[$keys]);
	        $val[$keys] = str_replace(' ','',$val[$keys]);
	        $val[$keys] = str_replace(':','',$val[$keys]);
	        $keysvalue[] =$val[$keys];
	    }
	    asort($keysvalue); //key值排序
	    reset($keysvalue); //指针重新指向数组第一个
	    foreach($keysvalue as $key=>$vals) {
	        $keysort[] = $key;
	    }
	    $keysvalue = array();
	    $count=count($keysort);
	    if(strtolower($type) != 'asc'){
	        for($i=$count-1; $i>=0; $i--) {
	           $keysvalue[] = $array[$keysort[$i]];
	        }
	    }else{
	        for($i=0; $i<$count; $i++){
	            $keysvalue[] = $array[$keysort[$i]];
	        }
	    }
	    return $keysvalue;
   }
}