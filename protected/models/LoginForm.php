<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel
{
	public $username;
	public $password;
	public $rememberMe;
	
	private $_identity;

	/**
	 * Declares the validation rules.
	 * The rules state that username and password are required,
	 * and password needs to be authenticated.
	 */
	public function rules()
	{
		return array(
			// username and password are required
			array('username', 'required','message'=> '用户名不能为空'),
			array('password', 'required','message'=> '密码不能为空'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'username' => yii::t('app','用户名'),
			'password' => yii::t('app','密码'),
			'rememberMe'=>yii::t('app','记住用户名'),
		);
	}

	/**
	 * Authenticates the password.
	 * This is the 'authenticate' validator as declared in rules().
	 * rules 中  array('password', 'authenticate') 密码验证函数
	 */
	public function authenticate($attribute,$params)
	{
		if(!$this->hasErrors())
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$valid = $this->_identity->authenticate();
			if($valid['status']) {
				$this->addError($valid['field'] , $valid['status'] == 1 ? yii::t('app','用户不存在'): yii::t('app',' 密码错误'));
			}
		}
	}

	/**
	 * Logs in the user using the given username and password in the model.
	 * @return boolean whether login is successful
	 */
	public function login()
	{
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{ 
			$duration=$this->rememberMe ? 3600*24*30 : 3600; // 30 days
			if(Yii::app()->user->login($this->_identity,$duration)) {
				return true;
			} else {
				return false ;
			}
		}
		else
			return false;
	}
}
