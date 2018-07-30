<?php
/**
* Description of Transactions Controller
* @description Funhansoft PHP auto templet
* @date 2014-04-02
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
*/
class systemETHTran extends ControllerBase{
    

    
    private $dao;
    private $crondao;
    private $dto;
    private $returnarr = array('result'=>0);
    private $poType = 'eth';
    private $wRPC;
    private $confirmCnt = 48;
    private $currentBlockHeight = 0;
    
    /**
    * @brief
    **/
    function __construct(){
        parent::__construct();
        set_time_limit(3600); //sec 10분
        $this->dto = new WebWalletDepositsDTO();
    }

    //DB를 연결할 경우만
    private function initDAO(){
        if(!$this->dao){
            $this->dao = new WebWalletDepositsDAO();
            $this->crondao = new CronWalletBlockCheckDAO();
            $this->dao->setListLimitRow(50);
        }
    }
    
    
    private function initCoinServer(){
        $this->wRPC = new EthereumRPCDAO();
        $wsvDAO = new WebConfigWalletServerDAO();
        $sdto = $wsvDAO->getViewByPoType($this->poType);
        $this->wRPC->initServer($sdto->waRpcProto,$sdto->waRpcIp.':'.$sdto->waRpcPort);
    }
    
    private function getBlockByAccount($accounts, $stBlockNum, $curBlockNum){
       
        $resultArray = array();
        $tempArray = array();
  
        for($n = $stBlockNum; $n <= $curBlockNum; $n++ ) {
       
            $hexBlockNum = dechex($n);
            $blockInfo = $this->wRPC->call()->eth_getBlockByNumber("0x".$hexBlockNum, false);   
                  
            for($i = 0; $i < count($blockInfo["transactions"]); $i++)
            {               
                $trinfo = $this->wRPC->call()->eth_getTransactionByHash($blockInfo["transactions"][$i]);
        
                if($trinfo == null)
                    continue;

                if(in_array(strtolower($trinfo["to"]), $accounts)) {
               
//                    $tempArray['blocknum']  = $n;
//                    $tempArray['to']   = $trinfo["to"];
//                    $tempArray['from']   = $trinfo["from"];
//                    $tempArray['txid']      = $trinfo["hash"];
                        $trinfo['account'] = $trinfo["to"];
                     array_push($resultArray, $trinfo);
                }

                if(in_array(strtolower($trinfo["from"]), $accounts)) {
//                    $tempArray['blocknum']  = $n;
//                    $tempArray['account']   = $trinfo["from"];
//                    $tempArray['txid']      = $trinfo["hash"];
//                    array_push($resultArray, $tempArray);   
                    $trinfo['account'] = $trinfo["from"];
                     array_push($resultArray, $trinfo);
                }
                
                
                
            }         
        }                
        return $resultArray;
    }
    
 

        //Wei  to eth
    private function convertDescToEther($dec,$type='ether'){
        if($type == 'ether'){
            $dec =  $dec / 1000000000000000000;
        }
		$dec = number_format($dec, 8);
        $str = str_replace(',', '', $dec);
		return $str;
    }
    
    private function checkUnconfirmedTxInsert(){
              
        $this->initCoinServer();
        $this->initDAO();
                
        $dbblock = $this->crondao->getLatestBlock($this->poType);
        
        $startblock = $dbblock->wcStartBlock;
        $endblock =  $dbblock->wcEndBlock;
        
        if(!$startblock){
            $startblock = $endblock;
        }
        

        $this->currentBlockHeight = hexdec($this->wRPC->call()->eth_blockNumber());
        //50개 이하로 찾기
        if($this->currentBlockHeight - $endblock > 50){
            $endblock = $this->currentBlockHeight;
        }
        
        
        $tranarray = [];
        
        
        $accounts = $this->wRPC->rpc('eth_accounts');

        // account값을 모두 소문자로 변환 ( 트랜잭션에서 비교시 대소문자 차이로 비교가 안되는 경우 방지)
        for($i = 0; $i < count($accounts); $i++)
        {
            $accounts[$i] = strtolower($accounts[$i]);
        }
        
        // 항상 시작 블럭넘버($startblock)는 최근 블럭넘버($this->currentBlockHeight)보다 작거나 같아야 한다.
        if($startblock > $this->currentBlockHeight)
            return;
        
//        $tranarray = $this->getBlockByAccount($accounts, $startblock, $this->currentBlockHeight); //부하발생
        $tranarray = $this->getBlockByAccount($accounts, $startblock, $endblock);
        

        
        //다음번에 값을 가져오기 위해 저장
        $bdto = $dbblock;
        $bdto->poType = $this->poType;
        $bdto->wcStartBlock = $endblock;
        $bdto->wcEndBlock = $this->currentBlockHeight;
        $bdto->wcFindCnt = count($tranarray);
        $blockres = $this->crondao->setBlockInsert($bdto);
        //디비저장 END

        
        if(empty($tranarray)){
           return;
        }
        
        $walletdao = new WebMemberDespositWalletDAO();
        for($i=0;$i<count($tranarray);$i++){
            
            if(!$tranarray[$i]) continue;
            

            $obj = $tranarray[$i];
            
//            echo json_encode($obj);
//            echo "<br /><br />";

            $from_addr = $obj['from'];
            
            $address = $obj['account'];
            $mbdto = $walletdao->getViewByAddrerss($address);
            
            //echo json_encode($tranarray);exit;

            /**********************************
            * 데이터 삽입
            *********************************/
            $account= 'MB'.$mbdto->mbNo;
            $fee = hexdec($obj['gas']);

            
            $amount = $this->convertDescToEther(hexdec($obj['value']));
  
            
            $category = '';
            if($obj['to'] == $obj['account']){
                $category = 'receive';
            }else if($obj['from'] == $obj['account']){
                $category = 'send';
            }

            $confirmations = 0;
            
            $this->dto->poType = $this->poType;
            $this->dto->odRegIp = $_SERVER['REMOTE_ADDR'];
            $this->dto->mbNo            = $mbdto->mbNo;
            $this->dto->blockHeight     = hexdec($obj['blockNumber']);
            $this->dto->odTmpMb         = $account;
            $this->dto->odCategory      = $category;
            $this->dto->odAmount        = $amount;
            $this->dto->odFromAddr      = $from_addr;
            $this->dto->odToAddr        = $address;
            $this->dto->odFee           = $fee;
            $this->dto->odConfirm       = $confirmations;
            $this->dto->odEtc1          = hexdec($obj['gas']);
            $this->dto->odEtc2          = '';
            $this->dto->odEtc3          = '';
            $this->dto->odTxid          = $obj['hash'];

            $this->returnarr['result'] = $this->dao->setInsert($this->dto);
            echo 'txid:' . $this->dto->odTxid  . ' - isinsert db :'.$this->returnarr['result']."\n";
                              
 

        }//end for i
    }

    /*
        * 컨펌이 완료되었는지 체크
        */
    public function checkConfirmation(){

       $this->checkUnconfirmedTxInsert();

       $success=0;

       $pointdao = null;
       $dao = null;
       
       $this->initDAO();
       $unconfirmdto = $this->dao->getListByUnConfirm($this->poType);
       $cron_content = '';
       
       if(count($unconfirmdto)) $this->initCoinServer();
       
       for($i=0;$i<count($unconfirmdto);$i++){
           
           $tranview = $unconfirmdto[$i];
           if( strlen($tranview->odTxid) < 32 ) continue; 
           
           $confirmations = $tranview->odConfirm;
 
           $gasUsed = null;
           $rpctran = $trinfo = $this->wRPC->call()->eth_getTransactionReceipt($tranview->odTxid);
           if($rpctran['gasUsed'] && hexdec($rpctran['gasUsed']) >= 2100){

               $gasUsed = hexdec($rpctran['gasUsed']);
               $confirmations = $this->currentBlockHeight - $tranview->blockHeight;
           }
           
           
           //컨펌 수 업데이트
           if($confirmations>0){
               $status = '';
               $gasUsed = 0;
               if($confirmations >= (int)$this->confirmCnt){
                    
                   
                    if($tranview && isset($tranview->poPayYn) && $tranview->poPayYn!='Y' ){

                        if((int)$tranview->mbNo && $tranview->odAmount > 0 ){
                             
                            
                            
                            // 포인트 삽입
                            $podto = new WebPointEtcDTO();
                            $podto->mbNo = $tranview->mbNo;
                            $podto->poPoint = $tranview->odAmount;
                            $podto->poRelId = $tranview->odId;
                            $podto->poRelAction = 'deposit';
                            $result = $this->getInsertPoint($podto);
                            $status = 'OK';

                            // 밸런스 메모리화로 인한 redis 값변경
                            $this->setBalanceUpdate($tranview->mbNo);
                            //  입금완료 메일링
                            $this->sendDepositBtcEmail($tranview);

                        }
                    }
                    
               }

                $success++;
                
                //주문서
                $upresult = $this->dao->setUpdateConfirm($tranview->odTxid,$confirmations,$status,$gasUsed);
                if($upresult>0){
                    $success++;
                }
                $cron_content .= json_encode($unconfirmdto[$i])."<br />";
           }
       }
       if($success>0){
//           $this->setCronComplet( strtoupper($this->poType) . ' 입금 1컨펌이상 ('.$success.'건)',$success."건 업데이트 완료<br />".$cron_content);
       }

       echo $success;
    }
    
    private function getInsertPoint($podto){
        
        $result = 0;
        
        $podto->poContent = strtoupper($this->poType).' deposit ';
        $podto->poRelAction = $this->poType.'wallet';
        
        
        $podto->isTracker = 'N';
        $podto->memPoNo = 0;
        $podto->poPointSum = 0;
        $podto->odTotalCost = 0;
        $podto->odFee = 0;

        $podto->poTradeDt = '0000-00-00';
        $podto->poRegIp = Utils::getClientIP();
        
        $pointDAO = new WebPointEtcDAO();
        $pointDAO->setTableName('web_point_'.$this->poType);
        //중복검사
        $prepoint = $pointDAO->getViewByRelId($podto->mbNo, $podto->poRelId,null,null,$podto->poRelAction);

        if(count($prepoint)>0){
            return;
        }
        $result = $pointDAO->setInsert($podto);
        //포인트 합산
        $result2 = $pointDAO->callBalanceSum($podto->mbNo,'wallet');
        return $result;
    }

    function sendDepositBtcEmail($trandto){
 
       if(!isset($trandto->mbNo) || !$trandto->mbNo){
           return false;
       }

       $memberdao = new WebMemberDAO();
       $member = $memberdao->getViewById($trandto->mbNo);

//       // 회원 이메일 알림 수신 여부
//       if($member->mbNotifyWithdrawals=='N'){
//           return false;
//       }

       // 이메일 템플릿 타입 - 언어
       $email_subject = '';
       $email_type_country = '';

       $strpotype = strtoupper($this->poType);
       $coinname = '';
       
       if($member->contryCode=='KR'){
           $coinname  = '이더리움';
           $email_subject = $coinname. ' 입금완료';
           $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_btc_email_kr.html';
       }else{
           $coinname  = 'Ethereum';
           $email_subject = $coinname. ' Deposit Completed';
           $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_btc_email_en.html';
       }

       $logo_url = '/assets/img/common/logo_email.png';

       
       
       $emaildao = new EmailDAO();
       $html = file_get_contents($email_type_country);
       $html = str_replace("{user_name}", $member->mbName,$html);
       $html = str_replace("{site_name}", $this->config['html']['email_title'],$html);
       $html = str_replace("{logo_url}", $this->config['url']['static'].$logo_url,$html);
       $html = str_replace("{point_pay_dt}", date( 'Y-m-d H:i:s', time() ),$html);
       $html = str_replace("{amount}", $trandto->odAmount,$html);
       $html = str_replace("{coinname}", $coinname, $html);
       $html = str_replace("{potype}", $strpotype, $html);
       $html = str_replace("{link_url}", $this->config['url']['site'].'//balance/history',$html);

       $mailresult = $emaildao->mailer($member->mbId, $email_subject, $html, 1, "", "", "");

       return array('result'=>$mailresult);
    }
    
    public function setBalanceUpdate($mb_no)
    {
        $loginstatus = 0;

        $mbsearchkey = '';
        $mbsearchkey = 'MB'.$mb_no;
        $session_expire = ((int)$this->config['session']['cache_expire'] * 60);

        //balance
        $pointdao = new WebPointEtcDAO();
        $balancedata = $pointdao->setRedisBalance($mb_no);

        //balance update
        if($mbsearchkey){
            $this->initRedisServer($this->config['redis']['host'],$this->config['redis']['port'], null,  $this->config['redis']['db_member'], null);
            $balancekey = $mbsearchkey . '-balance';
            $tmpbalancedata = $this->getRedisData($balancekey);

            if(isset($tmpbalancedata)){
                $jsonbalancedata = json_encode($balancedata);
                if($tmpbalancedata != $jsonbalancedata){
                    $this->delRedisData($balancekey);
                    $this->setRedisData($balancekey,$jsonbalancedata,$session_expire);
                }
            }
        }

        return $balancedata;
    }
    
    
    public function main(){
        $this->getViewer()->setResponseType('404');
    }
    public function view(){
        $this->getViewer()->setResponseType('404');
    }
    public function form(){
        $this->getViewer()->setResponseType('404');
    }
    public function lists(){
        $this->getViewer()->setResponseType('404');
    }
    public function insert() {
        
    }

    
    public function update(){
        $this->getViewer()->setResponseType('404');
    }
    public function delete(){
        $this->getViewer()->setResponseType('404'); //사용시 JSON으로
    }

    
    function __destruct(){
        unset($this->dao);
        unset($this->dto);
    }


    



}