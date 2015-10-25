<?php
class Filter_Session extends SPP_Filter_Filter {
    public function doBeforeRun($pContext) {
        if(empty($_SESSION['currentUser'])) {
            $_SESSION['currentUser'] = new DB_User();
        }
        $pContext->mCurrentUser = &$_SESSION['currentUser'];
    }
    public function doAfterRun($pContext) {
    }
}