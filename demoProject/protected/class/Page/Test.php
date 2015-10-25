<?php
class Page_Test extends SPP_BasePage {
    function run() {
        /*
         * $tUser = new DB_User();
         * $tUser->mName = 'Apple';
         * $tUser->_select();
         * var_dump($tUser->mPassword);
         * $tPost = new DB_Post();
         * $tPost->mTitle = 'title';
         * $tList = $tPost->_list(0, 0, '', 1);
         * var_dump($tList);
         * $this->assign('msg', 'This is a msg from Page_Test.');
         * $this->display('login.html');
         */
        $tMsg = new DB_Message();
        $tMsg->mFromUserId = 1;
        $tMsgSource = new DB_Message();
        $tMsgSource->mToUserId = 2;
        
        //$tMsgDes = new DB_Message();
        //$tMsgDes->mFromUserId = 1;
        
        $tMsg->mObjectSource = $tMsgSource;
        //$tMsg->mObjectDestination = $tMsg;
        $ret = $tMsg->_update();
        $this->display('test.html');
    }
}
