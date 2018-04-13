<?php
class WebUser extends CWebUser
{
	public $role2ModuleId = array(
		'1' => array('admin','waiter','ymall'),
		'2' => array('admin' , 'waiter','ymall'),
		'3' => array('admin' , 'waiter','ymall'),
		'4' => array('admin' ,'waiter','ymall'),
		'5' => array('admin' , 'ymall'),
		'7' => array('admin' , 'ymall'),
        '8' => array('admin' , 'ymall'),
		'9' => array('admin' , 'ymall'),
		'10' => array('admin'),
		'11' => array('admin', 'ymall'),
		'13' => array('admin', 'ymall'),
		'15' => array('admin'),
		'17' => array(''),
		'19' => array(''),
	);
        public $role2ControllerId = array(
		'1' => array('','',''),
		'2' => array('' , '',''),
		'3' => array('','' ,'' ),
		'4' => array('','' , '','',''),
        '5' => array(''),
        '7' =>array(),
        '8'=>array(),
        '9' =>array(),
       	'10' =>array('login','welcome','company','statements','statementstock','statementmember'),
        '11' =>array(),
        '13' =>array(),
        '15' =>array(),
        '17' =>array(),
        '19' =>array(),
	);
        
        //public static $loginrole="0";
	public function login($identity,$duration=0)
	{
		if(!$this->_checkMod($identity->role)){
			return false ;
		}
                
                //::app()->session['loginrole']=$identity->role;
                //var_dump(Yii::app()->session);exit;
		$id=$identity->getId();
                
		$states=$identity->getPersistentStates();
		
		if($this->beforeLogin($id,$states,false)) {
			$this->changeIdentity($id,$identity->getName(),get_object_vars($identity));
			
			if($duration>0) {
				if($this->allowAutoLogin) {
					$this->saveToCookie($duration);
				} else {
					throw new CException(Yii::t('yii','{class}.allowAutoLogin must be set true in order to use cookie-based authentication.',
							array('{class}'=>get_class($this))));
				}
			}
//			if ($this->absoluteAuthTimeout)
//				$this->setState(self::AUTH_ABSOLUTE_TIMEOUT_VAR, time()+$this->absoluteAuthTimeout);
			$this->afterLogin(false);
		}//var_dump($this->getIsGuest());exit;
		return !$this->getIsGuest();
	}
	private function _checkMod($role){
		$module = Yii::app()->controller->module;
		if(!$module) return true ;
                //echo Yii::app()->controller->id;exit;
		if($role>=15)
                {
                    return true;
                    return in_array(Yii::app()->controller->id , $this->role2ControllerId[$role]);
                }else{ 
                	//var_dump(Yii::app()->controller->module->getId() , $this->role2ModuleId[$role]);exit;
                    return in_array(Yii::app()->controller->module->getId() , $this->role2ModuleId[$role]);
                }
	}
}