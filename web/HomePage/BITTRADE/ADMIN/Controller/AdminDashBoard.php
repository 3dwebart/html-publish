<?php
/**
* Description of AdminDashBoard
* @description 상황판
* @date 2013-08-31
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class AdminDashBoard extends ControllerBase{
            
            private $oMCrypt;
            
            function __construct(){
                parent::__construct();
                $this->oMCrypt = new MCrypt();
            }

            public function main(){
                $returnArray = array();
                $mbcntdao = new ViewMbJoinDayDAO();
                $mbcntdao->setListLimitRow(100);
                $mbcnt = $mbcntdao->getList('','');
                $returnArray['result'] = count($mbcnt);
                $returnArray['data'] = $mbcnt;
                return $returnArray;
            }
            
            

            public function delete() {
                $this->getViewer()->setResponseType('404');
            }

            public function insert() {
                $this->getViewer()->setResponseType('404');
            }

            public function lists() {
//                $this->getViewer()->setResponseType('404');
                
            }

            public function update() {
                $this->getViewer()->setResponseType('404');
            }
            
        }