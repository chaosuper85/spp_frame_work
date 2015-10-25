<?php
class Cron_Cli extends SPP_Console {
    public function __construct() {
        parent::__construct();
    }
    public function run() {
        // php spp.php Cron_Cli 'a=2&b=3'
        var_dump($this->mArguments);
    }
}