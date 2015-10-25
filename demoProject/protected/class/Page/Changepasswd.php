<?php
class Page_Changepasswd extends SPP_BasePage {
    const FILTERS = 'Filter_Session,Filter_Login';
    public $mOldPwd = '';
    public $mPwd1 = '';
    public $mPwd2 = '';
    public $mMsg = '';
    public function __construct() {
        parent::__construct();
        if (! empty($this->mPOST['oldpwd'])) {
            $this->mOldPwd = $this->mPOST['oldpwd'];
        }
        if (! empty($this->mPOST['pwd1'])) {
            $this->mPwd1 = $this->mPOST['pwd1'];
        }
        if (! empty($this->mPOST['pwd2'])) {
            $this->mPwd2 = $this->mPOST['pwd2'];
        }
    }
    public function run() {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if ($this->doValid()) {
                $this->doChangePwd();
            }
        }
        $this->doDisplayPage();
    }
    public function doValid() {
        if (md5(md5($this->mOldPwd)) !== $this->mCurrentUser->mPassword) {
            $this->mMsg = '原密码不正确!';
            return false;
        } elseif ($this->mPwd1 != $this->mPwd2) {
            $this->mMsg = '两次密码不一致!';
            return false;
        }
        return true;
    }
    public function doDisplayPage() {
        $this->assign('msg', $this->mMsg);
        $this->display('tpl.changepwd.html');
    }
    public function doChangePwd() {
        $this->mCurrentUser->mPassword = md5(md5($this->mPwd1));
        //$tUser = new DB_User();
        //$tUser->_reset();
        //$tUser->mId =  $this->mCurrentUser->mId;
        //$tUser->mPassword = md5(md5($this->mPwd1));;
        //if ($tUser->_update()) {
        if ($this->mCurrentUser->_update()) {
            
            $this->mMsg = '修改成功!';
        }
    }
}