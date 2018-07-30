<?php
/**
* Description of License
* @description Funhansoft PHP auto templet
* @date 2013-08-31
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class License extends ControllerBase{

            function __construct(){
                parent::__construct();
            }

            public function main(){
                $returnArray = array();
                $keyvalue = Utils::getUrlParam('key');
                $ipaddress = Utils::getClientIP();

                $log_txt = date("Y-m-d H:i:s", time()).'[ip:'.$ipaddress.'] key : '.$keyvalue.'\r\n';
                $log_file = fopen('../WebApp/Debug/'."license_log.txt", "a");
//                $log_file = fopen('D:/03.workspace/funhansoft/bittradeV2/WebApp/Debug/'."license_log.txt", "a");
                fwrite($log_file, $log_txt."\r\n");
                fclose($log_file);
                return array();
            }

            public function delete() {
                $this->getViewer()->setResponseType('404');
            }

            public function insert() {
                $this->getViewer()->setResponseType('404');
            }

            public function lists() {
                $this->getViewer()->setResponseType('404');
            }

            public function update() {
                $this->getViewer()->setResponseType('404');
            }

        }