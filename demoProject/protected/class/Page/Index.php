<?php
class Page_Index extends SPP_BasePage {
    
    /**
     * 构造函数
     */
    public function __construct() {
        parent::__construct();
    }
    /**
     * 入口方法
     */
    public function run() {
        if (Util_Util::isUserLogin()) {
            $this->mCurrentUser = &$_SESSION['currentUser'];
            $this->doDisplayPage();
        } else {
            SPP_Util_Util::directGoToUrl('/login.php');
        }
    }
    /**
     * 显示页面
     */
    public function doDisplayPage() {
        $this->displayMessage();
        $this->displayOrders();
        $this->assign('currentUser', $this->mCurrentUser);
        $this->display('tpl.index.html');
    }
    /**
     * 显示订单列表
     */
    public function displayOrders() {
        $tOrder = new DB_Order();
        $tList = $tOrder->_list();
        /*
         * 填充用户到信息到订单数组
         * 调用SPP_Util_Utils::fill方法实现
         */
        SPP_Util_Util::fills($tList, new DB_User(), 'name', 'user_id', 'id');
        $this->assign('orderList', $tList);
    }
    /**
     * 显示站内消息
     */
    public function displayMessage() {
        $tMsg = new DB_Message();
        $tMsgList = $tMsg->getMsgByToUserId($this->mCurrentUser->mId, 1);
        
        /*
         * 填充消息发送人姓名到消息数组中
         * 自己写代码实现
         */
        $tFromUserId = SPP_Util_Util::Array2String($tMsgList, 'from_user_id');
        $tUser = new DB_User();
        $tUser->mAdditionalCondition = "id in ({$tFromUserId})";
        $tUserList = $tUser->_list();
        $tUserList = SPP_Util_Util::Array2Hash($tUserList, 'id');
        for ($i = 0; $i < count($tMsgList); $i ++) {
            $tMsgList[$i]['from_user_name'] = $tUserList[$tMsgList[$i]['from_user_id']]['name'];
        }
        
        $this->assign('msgList', $tMsgList);
        $this->assign('msgPage', $tMsg->mPagination);
        $tUnReadMsgCnt = $tMsg->getUserUnReadMsgCount($this->mCurrentUser->mId);
        $this->assign('unReadMsgCnt', $tUnReadMsgCnt);
    }
}
