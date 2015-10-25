<?php
require_once 'Smarty/Smarty.class.php';
class SPP_BasePage extends SPP_Object implements SPP_Runnable {
    /**
     * $_GET
     *
     * @var array
     */
    public $mGET;
    
    /**
     * $_POST
     *
     * @var array
     */
    public $mPOST;
    
    /**
     * Smarty对象
     *
     * @var Smarty
     */
    protected $mSmarty = null;
    public $mCurrentUser = null;
    function __construct() {
        parent::__construct();
        $this->mGET = $_GET;
        $this->mPOST = $_POST;
    }
    /**
     * 初始化Smarty
     */
    private function initSmarty() {
        if (is_null($this->mSmarty)) {
            $class_path = SPP_Config_Config::getSystemPath('class');
            $template_path = SPP_Config_Config::getSystemPath('template');
            $template_c_path = SPP_Config_Config::getSystemPath('temp') . '/template_c';
            $this->mSmarty = new Smarty();
            $this->mSmarty->template_dir = $template_path;
            
            $this->mSmarty->compile_dir = $template_c_path;
            $this->mSmarty->config_dir = $template_c_path;
            $this->mSmarty->cache_dir = $template_c_path;
        }
    }
    
    /**
     * 显示信息
     *
     * @param string $pMessage            
     * @param string $pButtons            
     * @param string $pTemplate            
     */
    public function showMessage($pMessage, $pButtons = array(), $pTemplate = 'tpl.prompt.html') {
        if (is_string($pButtons)) {
            $pButtons = array(array('name' => '确定','url' => $pButtons ) );
        }
        if (isset($this->mCurrentUser) && (! empty($this->mCurrentUser->mUsername) ||
                 ! empty($this->mCurrentUser->mTomUsername))) {
            $this->assign('user', $this->mCurrentUser);
        }
        $this->assign('message', $pMessage);
        if (count($pButtons) > 0) {
            $this->assign('buttons', $pButtons);
        }
        $this->display($pTemplate);
        exit();
    }
    
    /**
     * 显示确认信息
     *
     * @param string $pMessage            
     */
    public function confirm($pMessage) {
        $this->showMessage($pMessage, 
                array(array('name' => '确定','url' => '?confirm=yes' ),
                        array('name' => '取消','url' => 'javascript:history.go(-1);' ) ));
    }
    /**
     * 获取一个模板输出的内容
     *
     * @param string $pTemplate            
     * @return string
     */
    public function fetch($pTemplate) {
        $this->initSmarty();
        return $this->mSmarty->fetch($pTemplate);
    }
    /**
     * 清除所有已赋值到模板中的值
     *
     * @return boolean
     */
    public function clear_all_assign() {
        if (! is_null($this->mSmarty)) {
            return $this->mSmarty->clear_all_assign();
        } else {
            return false;
        }
    }
    /**
     * 显示页面
     *
     * @param string $pTemplate            
     */
    public function display($pTemplate) {
        $this->initSmarty();
        $this->mSmarty->display($pTemplate);
    }
    /**
     * assign变量到模板
     *
     * @param unknown $pKey            
     * @param unknown $pValue            
     */
    public function assign($pKey, $pValue) {
        $this->initSmarty();
        $this->mSmarty->assign($pKey, $pValue);
    }
    /**
     * 引用方式assign变量到模板
     *
     * @param string $pKey            
     * @param mixed $pValue            
     */
    public function assign_by_ref($pKey, &$pValue) {
        $this->initSmarty();
        $this->mSmarty->assign_by_ref($pKey, $pValue);
    }
    /**
     * 页面类入口方法
     *
     * @see SPP_Runnable::run()
     */
    function run() {
    }

}
