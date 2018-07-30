<?php
/**
* Description of Transactions Controller
* @description Funhansoft PHP auto templet
* @date 2014-04-02
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
*/
class systemLTCTran extends ControllerBase{

    private $dao;
    private $dto;
    private $searchField;
    private $searchValue;
    private $returnarr = array('result'=>0);
    private $poType = 'ltc';
    private $coinRootAccount = 'system';
    private $coinServer;
    private $confirmCnt = 6;

    /**
    * @brief
    **/
    function __construct(){
        parent::__construct();
        set_time_limit(3600); //sec 10분
        $this->dto = new WebWalletDepositsDTO();
        Session::startSession();
    }

    //DB를 연결할 경우만
    private function initDAO(){
        if(!$this->dao){
            $this->dao = new WebWalletDepositsDAO();
            $this->dao->setListLimitRow(50);
        }
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
    
    private function initCoinServer(){
        $this->coinServer = new BitcoinRPCDAO();
        $wsvDAO = new WebConfigWalletServerDAO();
        $sdto = $wsvDAO->getViewByPoType($this->poType);
        $this->coinServer->initServer($sdto->waRpcProto,$sdto->waRpcIp.':'.$sdto->waRpcPort,$sdto->waUser,$sdto->waPass);
    }

    /*
     * @brief 비트코인트렌젝션
     * @return object
     */
    public function insert(){
        //서버 아이피
        
        $this->getViewer()->setResponseType('JSON');

        $txid = Utils::getUrlParam('txid');
        
        $this->initCoinServer();
        $response = $this->coinServer->getRPCTransaction($txid);
        
        $details = array();

        try{
            
            $details = $response['details'];
        }catch(Exception $e){
            $json = json_decode($e);
            $this->returnarr['result'] = (int)$json->code;
            return $this->returnarr;
        }
        
        $this->initDAO();
        
        
        for($k=0;$k<count($details);$k++){
//            var_dump($details[$k]);
            
            $account= (isset($details[$k]['account']))?$details[$k]['account']:'';
            $address = (isset($details[$k]['address']))?$details[$k]['address']:'';
            $category = (isset($details[$k]['category']))?$details[$k]['category']:'';
            $amount = (isset($details[$k]['amount']))?$details[$k]['amount']:$response['amount'];
            $fee = (isset($details[$k]['fee']))?$details[$k]['fee']:0;
            $confirmations = (isset($details[$k]['confirmations']))?$details[$k]['confirmations']:0;
            $time = (isset($details[$k]['time']))?$details[$k]['time']:0;
            
            
            $this->dto->poType = $this->poType;
            $this->dto->odRegIp = $_SERVER['REMOTE_ADDR'];
            $mbno = str_replace('MB', '', $account);
            if(!$mbno) $mbno            = 0;
            $this->dto->mbNo            = $mbno;
            $this->dto->odTmpMb         = $account;
            $this->dto->odCategory      = $category;
            $this->dto->odAmount        = $amount;
            $this->dto->odFromAddr      = '';
            $this->dto->odToAddr        = $address;
            $this->dto->odFee           = $fee;
            $this->dto->odConfirm       = $confirmations;
            $this->dto->odEtc1          = '';
            $this->dto->odEtc2          = '';
            $this->dto->odEtc3          = '';
            $this->dto->odTxid          = $response['txid'];
            
            if(isset($response['walletconflicts'])){
                $this->dto->odEtc3 = json_encode($response['walletconflicts']);
            }
//            $this->returnarr = $this->returnDTO($this->dto, array('result','no','fee','confirmations','blockindex','server','blockhash','blockindex','blocktime'));

            $this->returnarr['result'] = $this->dao->setInsert($this->dto);
        }
        
        return $this->returnarr;
    }
    /*
     * 블록이벤트 발생시
     */
    public function blocknotify(){
        //서버 아이피
        
        $this->getViewer()->setResponseType('JSON');

        $bid = Utils::getUrlParam('bid');
        $this->initRedisTradeServer($this->config['redis_trade']['host'],$this->config['redis_trade']['port'], null,  $this->config['redis_trade']['db_ticker'], null);
        $eventkey = 'bitcoin-recv-blockevent';
        $data = $this->getRedisTradeData($eventkey);
        if($data){
            $this->delRedisTradeData($eventkey);
        }
        $this->setRedisTradeData($eventkey,  $bid,60);
        
        return array('result'=>1);
    }
    
    /*
        * 컨펌이 완료되었는지 체크
        */
    public function checkConfirmation(){

       $success=0;

       $pointdao = null;
       $dao = null;
       
       $this->initDAO();
       $keys = $this->dao->getListUnConfirm($this->poType);
       $cron_content = '';
       
       if(count($keys)) $this->initCoinServer();
       
       for($i=0;$i<count($keys);$i++){

           $rpctran = $this->coinServer->rpc('gettransaction',$keys[$i]);
           $confirmations = (int)$rpctran['confirmations'];
           $amount = $rpctran['amount'];

           $details = $rpctran['details'];
           $arrAccount = array();
           for($tmp=0;$tmp<count($details);$tmp++){
               if($details[$tmp]['account']) array_push($arrAccount, $details[$tmp]['account']);
           }

           //컨펌 수 업데이트
           if($confirmations>0){
               $status = '';
               //예외 처리 - 이미 Y값으로 되어 있는 경우 move로 인해 금액이 안맞는 현상 방지
               //1컨펌 이고 회원값이 있고 아직 업데이트 전이라면 MOVE를 시켜놓는다.
               if($confirmations>=$this->confirmCnt){

                   $tranlistdto = $this->dao->getListsByTxId($keys[$i]);
                   
                   for($tidx=0;$tidx<count($tranlistdto);$tidx++){

                       $trandto = $tranlistdto[$tidx];
                       
                       $account = $trandto->odTmpMb;
   
                       if($trandto && isset($trandto->poPayYn) && $trandto->poPayYn!='Y' && strlen($account)>1){
 
                           if((int)$trandto->mbNo && $trandto->odAmount > 0 ){

                                //bitcoin move
                                $this->coinServer->move($account,$this->coinRootAccount,$trandto->odAmount);
                                // 포인트 삽입
                                $podto = new WebPointEtcDTO();
                                $podto->mbNo = $trandto->mbNo;
                                $podto->poPoint = $trandto->odAmount;
                                $podto->poRelId = $trandto->odId;
                                $result = $this->getInsertPoint($podto);

                                $status = 'OK';
                                // 밸런스 메모리화로 인한 redis 값변경
                                $this->setBalanceUpdate($trandto->mbNo);
                                //  입금완료 메일링
                                $this->sendDepositBtcEmail($trandto);
                                
                           }
                       }
                   } // $tranlistdto
               }

               //컨펌 수는 무조건 처리
               $upresult = $this->dao->setUpdateConfirm($keys[$i],$confirmations,$status,'');
               if($upresult>0){
                   $success++;
                   $cron_content .= json_encode($arrAccount)."<br />";
               }

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
        echo 'point:::'.$result;

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
           $coinname  = '라이트코인';
           $email_subject = $coinname. ' 입금완료';
           $email_type_country = '../WebApp/ViewHTML/request_deposit_complet_btc_email_kr.html';
       }else{
           $coinname  = 'Litecoin';
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
    

    /*
     * @brief 데이터 수정
     * @return object
     */
    public function update(){
        $this->getViewer()->setResponseType('404');
    }

     /*
     * @brief 데이터 삭제
     * @return int
     */
    public function delete(){
        $this->getViewer()->setResponseType('404'); //사용시 JSON으로
    }


    /*
     * @brief 검색 파라미터 초기화
     * @return null
     */
    private function setSearchParam(){
        $this->searchField = Utils::getUrlParam('sf',1);
        $this->searchValue = Utils::getUrlParam('sv',1);
        if($this->searchField=='no' || $this->searchField=='server' || $this->searchField=='account' || $this->searchField=='category' || $this->searchField=='amount' || $this->searchField=='fee' || $this->searchField=='confirmations' || $this->searchField=='blockhash' || $this->searchField=='blockindex' || $this->searchField=='txid' || $this->searchField=='walletconflicts'){

        }else{
            $this->searchField = '';
            $this->searchValue = '';
        }
    }

    function __destruct(){
        unset($this->dao);
        unset($this->dto);
    }

}