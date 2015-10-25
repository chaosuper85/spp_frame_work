<?php
class Page_Logout extends SPP_BasePage {
    public function __construct() {
        parent::__construct();
    }
    
    public function run() {
        $_SESSION['currentUser'] = null;
        SPP_Util_Util::directGoToUrl('/index.php');
    }
}