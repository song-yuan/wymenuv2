<?php
class WebUser extends CWebUser
{
	public $role2ModuleId = array(
		'1' => array('admin','waiter',''),
		'2' => array('admin' , 'waiter',''),
		'3' => array('waiter'),
		'4' => array(),
	);
	public function login($identity,$duration=0)
	{
		if(!$this->_checkMod($identity->role)){
			return false ;
		}
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
			if ($this->absoluteAuthTimeout)
				$this->setState(self::AUTH_ABSOLUTE_TIMEOUT_VAR, time()+$this->absoluteAuthTimeout);
			$this->afterLogin(false);
		}
		return !$this->getIsGuest();
	}
	private function _checkMod($role){
		$module = Yii::app()->controller->module;
		if(!$module) return true ;
		
		return in_array(Yii::app()->controller->module->getId() , $this->role2ModuleId[$role]);
	}
}