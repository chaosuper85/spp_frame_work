<?php
class Page_Login extends SPP_BasePage {
    private $mEmail = '';
    private $mPassword = '';
    private $mErrorMsg = array('error'=>'');
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (isset($_POST['email']) && ! empty($this->mPOST['email'])) {
                $this->mEmail = trim($this->mPOST['email']);
            } else {
                $tErrorMsg['error'] = '请输入邮箱!';
            }
            if (isset($_POST['password']) && ! empty($this->mPOST['password'])) {
                $this->mPassword = trim($this->mPOST['password']);
            } else {
                $tErrorMsg['error'] = '请输入密码!';
            }
        }
    }
    public function run() {
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            if (! empty($this->mErrorMsg['error'])) {
                $this->doDisplayPage();
            } else {
                $this->doLogin();
            }
        } else {
            $this->doDisplayPage();
        }
    }
    public function doDisplayPage() {
        $this->assign('errorMsg', $this->mErrorMsg);
        $this->display('tpl.login.html');
    }
    public function doLogin() {
        $tUser = new DB_User();
        if ($tUser->login($this->mEmail, $this->mPassword)) {
            $_SESSION['currentUser'] = $tUser;
            SPP_Util_Util::directGoToUrl('/index.php');
        } else {
            $this->mErrorMsg['error'] = ':( 用户名或密码错误, 请重试!';
            $this->doDisplayPage();
        }
    }
}