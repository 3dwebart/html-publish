<?php
/**
* Description of IndexMain
* @description Funhansoft PHP auto templet
* @date 2013-08-31
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class AutoIncrementCheck extends ControllerBase{

            private $dto;
            private $dao;

            function __construct(){
                parent::__construct();
                $this->dto = new AutoIncrementDTO();
            }
            
            private function initDAO(){
                if(!$this->dao){
                    $this->dao = new AutoIncrementDAO();
                }
            }

            public function main(){
                $this->initDAO();
                $returnArray = array();
                
                $this->dto = $this->dao->getList();
                
                $returnArray['result'] = count($this->dto);
                $returnArray['data'] = $this->dto;
                
                return $returnArray;
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
