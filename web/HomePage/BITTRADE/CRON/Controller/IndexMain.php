<?php
/**
* Description of IndexMain
* @description Funhansoft PHP auto templet
* @date 2013-08-31
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.2.1
*/
        class IndexMain extends ControllerBase{

            function __construct(){
                parent::__construct();
            }

            public function main(){
                $returnArray = array();
                

                return $returnArray;
            }

            private function blockIpCheck(){
                $dto = new WebAdminMemberDTO();
                $oLog = new WebAdminLoginHisDAO();
                $isBlockIp = $oLog->isBlockIp(Utils::getClientIP());
                if($isBlockIp){
                    $dto->mbId = 'none';
                    $dto->result = ResError::accessIp;
                    $dto->mbTodayLogin = '블랙리스트 아이피 접속 - 차단됨';
                    $this->loginLogReturn($dto);
                    $this->getViewer()->setResponseType('404');
                }
            }

            private function isAccessIp($ips){
                $is = false;
                $pos = strrpos($ips, "0.0.0.0");
                if ($pos === false) { // note: three equal signs
                    // not found...
                    $arrIps = explode(",", $ips);
                    for($i=0;$i<count($arrIps);$i++){
                        if(trim($arrIps[$i])==Utils::getClientIP()){
                           return $is = true;
                        }
                    }
                }else{
                    $is = true;
                }
                return $is;
            }
            

            public function login(){
                echo "---";
//                echo Utils::getClientIP();
            }

            public function logout(){
                Session::startSession();
                Session::delSession('admin_mb_id');
                Session::delSession('admin_mb_name');
                Session::delSession('admin_mb_auth');
                Session::delSession('admin_mb_no');
                Session::delSession('login_ip');
                Session::destorySession();
                Utils::redirect( get_class());
            }

            public function getMenu(){
               
            }

            public function location(){
                $return = Utils::getUrlParam('return',ResError::no);
                if($return) Utils::redirect($return);
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
