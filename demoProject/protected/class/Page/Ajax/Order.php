<?php
class Page_Ajax_Order implements SPP_Runnable {
    /**
     * 订单ID
     *
     * @var int
     */
    public $mOrderId = 0;
    
    public $mDebugMode = false;
    /**
     * 订单
     *
     * @var DB_Order
     */
    public $mOrder = null;
    public $mAction = '';
    public $mActionList = array('add','remove' );
    
    /**
     * 构造函数
     */
    public function __construct() {
        if (isset($_GET['orderid'])) {
            $this->mOrderId = intval($_GET['orderid']);
            if ($this->mOrderId > 0 ) {
                $this->mOrder = new DB_Order($this->mOrderId);
            }
        }
        
        if (isset($_GET['action'])) {
            $this->mAction = trim($_GET['action']);
            if (! in_array($this->mAction, $this->mActionList)) {
                $this->mAction = '';
            }
        }
    }
    public function run() {
        if ('remove' == $this->mAction) {
            $this->_removeOrder();
        } elseif ('add' == $this->mAction) {
            $this->_addOrder();
        }
    }
    
    private function _removeOrder() {
        if ($this->mOrder && $this->mOrder->mId > 0) {
            if ($this->mOrder->_delete()) {
                $this->_display(array('error' => null,'msg' => 'success' ));
            } else {
                $this->_display(array('error' => 'error','msg' => '' ));
                
            }
        }
        $this->_display(array('error' => null,'msg' => 'success' ));
    }
    
    private function _addOrder() {
        $tDesc = trim($_POST['orderdesc']);
        $tPrdId = intval($_POST['prd_id']);
        $tPrdCnt = intval($_POST['prd_cnt']);
        
        $this->mOrder = new DB_Order();
        $this->mOrder->mUserId  = $_SESSION['currentUser']->mId;
        $this->mOrder->mOrderDesc = $tDesc;
        $this->mOrder->mProductId = $tPrdId;
        $this->mOrder->mProductCnt = $tPrdCnt;
        $this->mOrder->mOrderTime = date('Y-m-d H:i:s');
        $this->mOrder->mStatus = DB_Order::STATUS_NORMAL;
        $tNewOrderId = $this->mOrder->_insert();
        if($tNewOrderId) {
            $this->_display(array('error' => null,'data' => array('id'=>$tNewOrderId) ));
        } else {
            $this->_display(array('error' => 'error','msg' => '' ));
        }
    }
    private function _display($pData) {
        echo json_encode($pData);
        exit();
    }
}