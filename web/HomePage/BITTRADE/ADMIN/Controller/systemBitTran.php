<?php
/**
* Description of Transactions Controller
* @description Funhansoft PHP auto templet
* @date 2014-04-02
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
class systemBitTran extends ControllerBase{

    private $dao;
    private $dto;
    private $searchField;
    private $searchValue;
    private $returnarr = array('result'=>0);
    private $poType = 'btc';

    /**
    * @brief
    **/
    function __construct(){
        parent::__construct();
        set_time_limit(3600); //sec 10분
        $this->dto = new WebWalletDepositsDTO();
        Session::startSession();
        $this->checkLoginToken();
    }

    //DB를 연결할 경우만
    private function initDAO(){
        if(!$this->dao){
            $this->dao = new WebWalletDepositsDAO();
            $this->dao->setListLimitRow(30);
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

    
    public function testtks(){
        echo md5('d437e7aa2745cf3d73b0123fd5a31621').'-'.md5('d437e7aa2745cf3d73b0123fd5a31621').'-'.md5(Utils::getUrlParam('param'));
    }


    private function checkLoginToken(){
        
        try{
            $pri = Utils::getUrlParam('pri');
            $mbId = ($pri)?base64_decode($pri):'';
            $token = Utils::getUrlParam('token');
        }catch(Exception $e){
            $pri = '';
            $mbId = '';
            $token = '';
        }
        
        if($pri){
            if(!Session::getSession('mb_id') || !Session::getSession('mb_no')
               || Session::getSession('login_ip') != Utils::getClientIP() )
            {

                $memberdao = new WebMemberDAO();
                $member = $memberdao->getViewByMbId($mbId);

                if($token == Utils::getAppLoginToken($member)){
                    Session::setSession('mb_no',$member->mbNo);
                    Session::setSession('mb_id',$member->mbId);
                    Session::setSession('mb_nick',$member->mbNick);
                    Session::setSession('mb_name',$member->mbName);
                    Session::setSession('mb_level',$member->mbLevel);
                    Session::setSession('mb_point',$member->mbPoint);
                    Session::setSession('login_ip',Utils::getClientIP());
                    $this->checkAdult($member->mbBirth);
                }
            }
        }
    }

    /*
     * @brief 비트코인트렌젝션
     * @return object
     */
    public function insert(){
        //서버 아이피
        
        $this->getViewer()->setResponseType('JSON');

        $txid = Utils::getUrlParam('txid');
        
        $PluginBitcoin = new BitcoinRPCDAO();
        $wsvDAO = new WebConfigWalletServerDAO();
        $sdto = $wsvDAO->getViewByPoType('btc');
        $PluginBitcoin->initServer($sdto->waRpcProto,$sdto->waRpcIp.':'.$sdto->waRpcPort,$sdto->waUser,$sdto->waPass);
        $response = $PluginBitcoin->getRPCTransaction($txid);
        
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
            if(!$mbno) $mbno = 0;
            $this->dto->mbNo = $mbno;
            $this->dto->odTmpMb = $account;
            $this->dto->odCategory =      $category;
            $this->dto->odAmount =        $amount;
            $this->dto->odFromAddr =       '';
            $this->dto->odToAddr =       $address;
            $this->dto->odFee =           $fee;
            $this->dto->odConfirm = $confirmations;
            $this->dto->odEtc1 =     '';
            $this->dto->odEtc2 =    '';
            $this->dto->odEtc3 =     '';
            $this->dto->odTxid =          $response['txid'];
            
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
     * @brief 데이터 수정
     * @return object
     */
    public function update(){
        $this->getViewer()->setResponseType('404');

        //레퍼러 도메인 체크
        if(parent::checkReferer()<0){
            return array();
        }

        //고유값으로 값을 가져온다.
         try{
            $pri = (int)Utils::getPostParam('no');
        }catch(Exception $e){
            $json = json_decode($e);
            $this->returnarr['result'] = (int)$json->code;
            return $this->returnarr;
        }

        $this->initDAO();
        $this->dto = $this->dao->getViewById($pri);
        $this->returnarr = $this->returnDTO($this->dto, array('result','no','fee','confirmations','blockindex'));
        if($this->dto->no){
            if($this->dto->no!=$pri){
                $this->returnarr['result'] =  ResError::paramUnMatchPri;
                return $this->returnarr;
            }

            //수정권한이 있는지 체크

        }else{
            $this->returnarr['result'] = ResError::noResultById;
            return $this->returnarr;
        }

        try{
                $this->initModifyPostParam('no');
                $this->initModifyPostParam('server');
                $this->initModifyPostParam('account');
                $this->initModifyPostParam('category');
                $this->initModifyPostParam('amount');
                $this->initModifyPostParam('fee',ResError::no);
                $this->initModifyPostParam('confirmations',ResError::no);
                $this->initModifyPostParam('blockhash');
                $this->initModifyPostParam('blockindex',ResError::no);
                $this->initModifyPostParam('blocktime');
                $this->initModifyPostParam('txid');
                $this->initModifyPostParam('walletconflicts');
        }catch(Exception $e){
            $json = json_decode($e);
            $this->returnarr['result'] = (int)$json->code;
            return $this->returnarr;
        }

        //param값 유효성 검사
        $this->returnarr['result'] = parent::checkEmptyValue($this->dto,array('result','no','fee','confirmations','blockindex'));
        if($this->returnarr['result']<0){
             return $this->returnarr;
        }

        //토큰 유효성 검사 - 마지막에
        $this->returnarr['result'] = parent::checkToken();
        if($this->returnarr['result']<0){
             return $this->returnarr;
        }

        $this->returnarr['result'] = $this->dao->setUpdate($this->dto);
        return $this->returnarr;
    }

     /*
     * @brief 데이터 삭제
     * @return int
     */
    public function delete(){
        $this->getViewer()->setResponseType('404'); //사용시 JSON으로

        //레퍼러 도메인 체크
        if(parent::checkReferer()<0){
            return array();
        }
        //고유값으로 값을 가져온다.
         try{
            $pri = (int)Utils::getPostParam('id');
        }catch(Exception $e){
            $json = json_decode($e);
            $this->returnarr['result'] = (int)$json->code;
            return $this->returnarr;
        }

        $this->initDAO();
        $this->dto = $this->dao->getViewById($pri);
        //Input Exception Field ,'no','server','account','category','amount','fee','confirmations','blockhash','blockindex','blocktime','txid','walletconflicts'
        $this->returnarr = $this->returnDTO($this->dto, array());
        if($this->dto->no){
            if($this->dto->no!=$pri){
                $this->returnarr['result'] =  ResError::paramUnMatchPri;
                return $this->returnarr;
            }

            //삭제 권한이 있는지

            $this->returnarr['result'] = $this->dao->deleteFromPri($pri);
            return $this->returnarr;
        }else{
            $this->returnarr['result'] =  ResError::noResultById;
            return $this->returnarr;
        }
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