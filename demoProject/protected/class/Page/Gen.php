<?php
class Page_Gen extends SPP_BasePage {
    function run() {
       $generator = new SPP_Tools_GenerateBaseTable(0,'/tmp','SPP','DB');
        $generator->generateCode(array('order'));
    }
}
