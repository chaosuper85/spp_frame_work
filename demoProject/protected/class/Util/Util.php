<?php
class Util_Util extends SPP_Object {
    public static function isUserLogin(&$pSession = null) {
        if (is_null($pSession)) {
            $tSession = &$_SESSION;
        } else {
            $tSession = &$pSession;
        }
        if (isset($tSession['currentUser'])) {
            if ($tSession['currentUser']->mId > 0) {
                return true;
            }
        } else {
            return false;
        }
    }
}