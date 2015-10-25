<?php
class Filter_Login extends SPP_Filter_Filter {
    
    public function doBeforeRun($pContext) {
        if (empty($pContext->mCurrentUser->mId)) {
            SPP_Util_Util::directGoToUrl('/login.php');
        }
    }
    
    public function  doAfterRun($pContext) {
    }
}