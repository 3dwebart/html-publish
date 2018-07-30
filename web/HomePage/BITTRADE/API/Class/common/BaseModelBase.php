<?php

/**
* Description of BaseModelBase
* @author bugnote@funhansoft.com
* @date 2017-04-20
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
class BaseModelBase {

    protected $res; //환경 리소스
	protected $db;
	protected $dbSlave;
	protected $remotedb; //다른 DB연결시
    protected $remotedbSlave; //다른 DB연결시
    protected $redis; //redis서버
    protected $redistrade; //redis서버
    protected $ssMember; //세션맴버(redis)

    private $list_limit_start=0;
	private $list_limit_row=20;
    private $list_order_by = array();
    private $list_order_by_rand = false;
    private $dbconfname;
    private $dbconf;

	public function __construct($dbconfname=null) {
        $this->dbconfname = (!$dbconfname)?'mysql':'mysql_'.$dbconfname;
        $this->stringChecker = new StringChecker();
	}

    public function setConfigJson($res){
        $this->res = $res;
        $this->dbconf = $this->res->config[$this->dbconfname];
    }

    public function getConfigJson($key){
        return (isset($this->res->ctrl->$key))?$this->res->ctrl->$key:'';
    }

    protected function initRedisServer($host = '127.0.0.1', $port = 6379, $timeout = null,  $db = 0, $password = null){
        $this->redis = new Credis_Client($host,$port,$timeout, '', $db, $password);
    }

    protected function setRedisData($key,$value,$sec=600){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port']);
        if(!$this->redis->exists($key)){
            $this->redis->set($key,$value);
            $this->redis->expire($key, $sec);
        }
    }

    protected function getRedisData($key){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port']);
        if($this->redis->exists($key)){
            return $this->redis->get($key);
        }
        return NULL;
    }

    protected function delRedisData($key){
        if(!$this->redis) $this->redis = new Credis_Client($this->res->config['redis']['host'],$this->res->config['redis']['port']);
        if($this->redis->exists($key)){
            $this->redis->del($key);
        }
    }
    
    protected function initRedisTradeServer($host = '127.0.0.1', $port = 6379, $timeout = null,  $db = 0, $password = null){
        $this->redistrade = new Credis_Client($host,$port,$timeout, '', $db, $password);
    }
    
    protected function setRedisTradeData($key,$value,$sec=600){
        if(!$this->redistrade) $this->redistrade = new Credis_Client($this->res->config['redis_trade']['host'],$this->res->config['redis_trade']['port']);
        if(!$this->redistrade->exists($key)){
            $this->redistrade->set($key,$value);
            $this->redistrade->expire($key, $sec);
        }
    }

    protected function getRedisTradeData($key){
        if(!$this->redistrade) $this->redistrade = new Credis_Client($this->res->config['redis_trade']['host'],$this->res->config['redis_trade']['port']);
        if($this->redistrade->exists($key)){
            return $this->redistrade->get($key);
        }
        return NULL;
    }

    protected function delRedisTradeData($key){
        if(!$this->redistrade) $this->redistrade = new Credis_Client($this->res->config['redis_trade']['host'],$this->res->config['redis_trade']['port']);
        if($this->redistrade->exists($key)){
            $this->redistrade->del($key);
        }
    }

    //회원 정보를 쿠키로 부터 가져온다.
    protected function getMemberDataFromRedis(){
        $sessionkey = Utils::getCookie($this->res->config['session']['sskey']);
        if(!$sessionkey){
            return null;
        }
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);
        
        return $this->getRedisData($sessionkey);
    }
    
    protected function getBalanceDataFromRedis(){
        $sessionkey = Utils::getCookie($this->res->config['session']['sskey']);
        if(!$sessionkey){
            return null;
        }
        //balance
        $tmp = @explode("-", $sessionkey);
        $balancekey = $tmp[0] . '-balance';
        
        $this->initRedisServer($this->res->config['redis']['host'],
                $this->res->config['redis']['port'],null,
                $this->res->config['redis']['db_member']);

        return $this->getRedisData($balancekey);
    }

    /*
     * @brief 파라미터로부터 넘어온 데이터를 검증 후 정렬
     */
    protected function getBindValueFromParamData($mapkeys,$paramData){

        $data = array();
        $ruleserror = array();

        //정의된 값이 없으면 통과
        if(!isset($mapkeys)) return null;

        $index=0;
        $ruleindex = 0;

        foreach($mapkeys as $key => $value){
            $data[$index] = '';
            if(isset($paramData[$key])){
                $data[$index] = trim($paramData[$key]);
            }

            //데이터값이 올바른지 검사
            $arr = 0;
            if(!$mapkeys->$key) continue;
            foreach($mapkeys->$key as $rulekey => $ruleval){
                //default value
                if(trim($rulekey)=='default'){
                    $data[$index] = $this->getBindDefaultValue($rulekey, $ruleval);
                    continue;
                }
                
                 if(trim($rulekey)=='html'){
                    //html허용
                    if($ruleval){
                        $data[$index] = Utils::getHTMLParam($data[$index]);
                    }else{
                        $data[$index] = strip_tags($data[$index]);
                    }
                }else{
                    $data[$index] = strip_tags($data[$index]);
                }

                $ck = $this->stringChecker->isValidParamRules($data[$index], $rulekey, $ruleval);
                if(!$ck){
                    //init error data
                    if(!isset($ruleserror[$ruleindex]) || !isset($ruleserror[$ruleindex][$key]) ){
                        $ruleserror[$ruleindex] = array('key'=>$key,'message'=>array());
                    }

                    if(isset($this->res->lang->validator->$rulekey)){
                        $explainlang = $this->res->lang->validator->$rulekey;
                        $explainlang = str_replace("{0}", $mapkeys->$key->$rulekey, $explainlang);
                    }else{
                        $explainlang = 'language error';
                    }
//                    $ruleserror[$ruleindex]['message'][$arr++] = $explainlang;
                    $ruleserror[$ruleindex]['message'] = $explainlang;
                }
            }

            if (isset($ruleserror[$ruleindex]))
                $ruleindex++;

            $index++;
        }

        return array('data'=>$data,
            'ruleserror'=>$ruleserror);
    }

    //기본값셋팅
    private function getBindDefaultValue($rulekey, $ruleval){

        if (!$this->ssMember) {
            $this->ssMember = json_decode($this->getMemberDataFromRedis(),true);
        }

        $returnvalue = $ruleval;

        if($rulekey == 'default'){
            switch($ruleval){
                case 'SS_MB_NO':
                    if(isset($this->ssMember['mb_no'])){
                        $returnvalue = $this->ssMember['mb_no'];
                    }else{
                        $returnvalue = '';
                    }

                break;
                case 'SS_MB_ID':
                    if(isset($this->ssMember['mb_id'])){
                        $returnvalue = $this->ssMember['mb_id'];
                    }else{
                        $returnvalue = '';
                    }
                break;
                case 'SS_MB_NICK':
                    if(isset($this->ssMember['mb_nick'])){
                        $returnvalue = $this->ssMember['mb_nick'];
                    }else{
                        $returnvalue = '';
                    }
                break;
                case 'SS_MB_NANME':
                    if(isset($this->ssMember['mb_name'])){
                        $returnvalue = $this->ssMember['mb_name'];
                    }else{
                        $returnvalue = '';
                    }
                break;
                case 'SS_MB_LEVEL':
                    if(isset($this->ssMember['mb_level'])){
                        $returnvalue = $this->ssMember['mb_level'];
                    }else{
                        $returnvalue = '';
                    }
                break;
                case 'CLIENT_IP':
                    $returnvalue = Utils::getClientIP();
                break;
                case 'CLIENT_AGENT':
                    $returnvalue = $_SERVER['HTTP_USER_AGENT'];
                break;

            }
        }
        return $returnvalue;
    }


    protected function getDatabase(){
            if(!$this->db){
                try {
                    $this->db = new PDO("mysql:host=".$this->dbconf['host'].";port=".$this->dbconf['port'].";dbname=".$this->dbconf['db']."", $this->dbconf['user'], $this->dbconf['pass'],
                            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

                } catch (PDOException $e) {
                    echo "{\"result\":-1,\"msg\":\"PDO Master Error.\"}";
                    throw new DBException("PDO Master Error: " . $e->getMessage());
                    die();
                }

            }
//            $this->dbSlave = $this->db;
            return $this->db;
	}

	protected function getDatabaseSlave(){
		if(!$this->dbSlave){
            if($this->dbconf['slave_host'] == $this->dbconf['host'] && $this->dbconf['slave_port'] == $this->dbconf['port']){
                return $this->dbSlave = $this->getDatabase();
            }else{
                try {
                    $this->dbSlave = new PDO("mysql:host=".$this->dbconf['slave_host'].";port=".$this->dbconf['slave_port'].";dbname=".$this->dbconf['db'], $this->dbconf['user'], $this->dbconf['pass'],
                            array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
                } catch (PDOException $e) {
                    echo "{\"result\":-1,\"msg\":\"PDO Slave Error.\"}";
                    throw new DBException("PDO Slave Error: " . $e->getMessage());
                    die();
                }
            }
		}
		return $this->dbSlave;
        
	}
    
	
	protected function getRemoteDatabase($dbconfname){
		$confkey = (!$dbconfname)?'mysql':'mysql_'.$dbconfname;
        $dbconf = $this->res->config[$confkey];
		
//		if(!$this->remotedb){
			try {
				$this->remotedb = new PDO("mysql:host=".$dbconf['host'].";port=".$this->dbconf['port'].";dbname=".$dbconf['db']."", $dbconf['user'], $dbconf['pass'],
						array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));

			} catch (PDOException $e) {
				echo "{\"result\":-1,\"msg\":\"PDO Remote Error.\"}";
				throw new DBException("PDO Master Error: " . $e->getMessage());
				die();
			}

//		}
		return $this->remotedb;
	}
    
    protected function getRemoteDatabaseSlave($dbconfname){
//		$confkey = (!$dbconfname)?'mysql':'mysql_'.$dbconfname;
//        $dbconf = $this->res->config[$confkey];
//		
////		if(!$this->remotedbSlave){
//			try {
//				$this->remotedbSlave = new PDO("mysql:host=".$dbconf['slave_host'].";port=".$this->dbconf['slave_port'].";dbname=".$dbconf['db']."", $dbconf['user'], $dbconf['pass'],
//						array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
//
//			} catch (PDOException $e) {
//				echo "{\"result\":-1,\"msg\":\"PDO Remote Slave Error.\"}";
//				throw new DBException("PDO Master Error: " . $e->getMessage());
//				die();
//			}
//
////		}
//		return $this->remotedbSlave;
        return $this->remotedbSlave = $this->getRemoteDatabase($dbconfname);
        
	}
    /*
     * @brief 검색관련 SQL을 만들어 준다.
     * @param 싱글필드
     * @param 검색필드
     * @param 검색값
     */
    protected function getListSearchSQL($singleFields,$field='',$value=''){
        $sql_where = '';
        if($value){
            $sql_where = " WHERE {$field} LIKE CONCAT('%',?,'%')";
            for($j=0;$j<count($singleFields);$j++){
                if($field==$singleFields[$j]){
                    $sql_where = " WHERE {$field}=?";
                }
            }
        }

        return $sql_where;
    }

    /*
     * @brief 정렬관련 SQL을 만들어 준다.
     * @param 싱글필드
     * @param 기본 sort 필드
     */
    protected function getListOrderSQL($singleFields,$pri){

        $sql_order = '';
        if(count($this->list_order_by)){
            while (list($key, $val) = each($this->list_order_by)) {
                if(in_array($key,$singleFields)){
                    $sql_order .= (($sql_order)?',':'') . $key.' '.$val;
                }
            }
        }else{
            if($this->list_order_by_rand) $sql_order .= 'RAND()';
            else $sql_order .= $pri.' DESC';
        }
        if(!$sql_order) $sql_order = $pri . ' DESC';
        return ' ORDER BY ' .$sql_order;
    }

	public function setListLimitStart($start){
		$this->list_limit_start = $start;
	}

	public function setListLimitRow($row){
		$this->list_limit_row = $row;
	}
	public function setListOrderBy($fields){
		$this->list_order_by = $fields;
	}
	public function setListOrderByRand(){
            $this->list_order_by_rand = true;
	}
	public function getListLimitStart(){
		return $this->list_limit_start;
	}
	public function getListLimitRow(){
            return $this->list_limit_row;
	}
	protected function setResult($int){
        $this->result = $int;
	}

	protected function getResult(){
		return $this->result;
	}
	protected function setResultMessage($msg){
		$this->resultMessage = $msg;
	}
	protected function getResultMessage(){
		return $this->resultMessage;
	}
    private function checkSqlQuestionMarkCount($sql){
        $sqlquto = preg_replace("/[^?]/", "", $sql);
        $cnt = strlen($sqlquto);
        if(strpos(strtolower($sql),' limit ?,?')!==false){
            $cnt = $cnt-2;
        }
        return $cnt;
    }
    
    private function isSlaveQuery(){
                        //마스터 슬레이브 에러 대처
        $pos = strpos(strtoupper($sql), 'UPDATE ');
        $pos2 = strpos(strtoupper($sql), 'INSERT ');
        $pos3 = strpos(strtoupper($sql), 'DELETE ');
        $pos4 = strpos(strtoupper($sql), 'CALL ');
        if ($pos !== false || $pos2 !== false || $pos3 !== false || $pos4 !== false) {
            return false;
        }else{
            return true;
        }
    }

    /*
     * @brief 조회
     * @param json설정
     * @param 파라미터
     */
    
    protected function execRemoteLists(string $remotedbName, array $jsonCon,array $param,$paramPage=1){
        return $this->_execLists($jsonCon,$param,$paramPage,$remotedbName);
    }
    
    protected function execLists(array $jsonCon,array $param,$paramPage=1){
        return $this->_execLists($jsonCon,$param,$paramPage);
    }
    
    private function _execLists(array $jsonCon,array $param,$paramPage=1,$remotedbName = null){

        if(!$jsonCon || count($jsonCon)==0){
            $jsonCon=array('sql'=>$this->res->ctrl->sql,
                'mapkeys'=>(isset($this->res->ctrl->mapkeys))?$this->res->ctrl->mapkeys:array(),
                'rowlimit'=>(isset($this->res->ctrl->rowlimit))?$this->res->ctrl->rowlimit:1 );
        }

        $dto = array();
        
        if(!$this->dbSlave) $this->db=$this->getDatabaseSlave();
		$seldb = $this->dbSlave;
        
        //다른 원격DB사용시
		if($remotedbName){
            $seldb = $this->getRemoteDatabaseSlave($remotedbName);
		}
		
        if($seldb){
            try{
                $start = 0;
                $rowlimit = (isset($jsonCon['rowlimit'])?(int)$jsonCon['rowlimit']:1);
                if(!$rowlimit) $rowlimit = 10;
                if((int)$paramPage){
                    $start = ($rowlimit * ((int)$paramPage -1));
                }
                

                
                $stmt = $seldb->prepare($jsonCon['sql']);

                if($stmt){
                    $p=1;
                    if( isset($jsonCon['mapkeys']) ){
                        $resarray = $this->getBindValueFromParamData($jsonCon['mapkeys'],$param);
                        if(count($resarray['ruleserror']) > 0 ){
                            return array('success'=>'false','error'=>$resarray['ruleserror']);
                        }else{
                            for($k=0;$k<count($resarray['data']);$k++){
                                $stmt->bindValue( $p++, $resarray['data'][$k], PDO::PARAM_STR);
                            }
                        }

                        $questioncount = $this->checkSqlQuestionMarkCount($jsonCon['sql']);

                        //파라미터 개수가 일치하는 지 확인
                        if($questioncount!=($p-1)){
                            return array(
                            "result" => -30,
                            "error"=>$this->res->lang->model->err30);
                        }
                    }

                    if(strpos(strtolower($jsonCon['sql']),' limit ?,?')!==false){
                        $stmt->bindValue( $p++, $start, PDO::PARAM_INT);
                        $stmt->bindValue( $p++, $rowlimit, PDO::PARAM_INT);
                    }
                    $stmt->execute();

                    $dto=$stmt->fetchAll(PDO::FETCH_ASSOC);
                    $this->setResult(count($dto));
                }else{
                    return array(
                    "result" => -21,
                    "error"=>$this->res->lang->model->err21 );
                }
            }catch(PDOException $e){
                return array(
                    "result" => -22,
                    "error"=>$this->res->lang->model->err22 );
            }
        }else{
            if(!$param){
                return array(
                    "result" => -1,
                    "error"=>$this->res->lang->model->err99 );
            }
        }
        $dto[0]['result'] = $this->getResult();
        return $dto;
    }
    /*
     * @brief 수정 업데이트
     * @param json설정
     * @param 파라미터
     */
    protected function execRemoteUpdate(string $remotedbName, array $jsonCon,array $param){
        return $this->_execUpdate($jsonCon,$param, $remotedbName);
    }
    protected function execUpdate(array $jsonCon,array $param){
        return $this->_execUpdate($jsonCon,$param);
    }
    private function _execUpdate(array $jsonCon,array $param, $remotedbName = null){

        if(!$jsonCon || count($jsonCon)==0){
            $jsonCon=array('sql'=>$this->res->ctrl->sql,
                'mapkeys'=>$this->res->ctrl->mapkeys);
        }

        $this->setResult(-10);
        if(!$param){
            return array(
                "result" => $this->result,
                "error"=>$this->res->lang->model->err10 );
        }

        if(!$this->db) $this->db=$this->getDatabase();
		$seldb = $this->db;
        
        //다른 원격DB사용시
		if($remotedbName){
            $seldb = $this->getRemoteDatabase($remotedbName);
		}
		
        if($seldb){
            try{

                $stmt = $seldb->prepare($jsonCon['sql']);
                if($stmt){
                    $p=1;
                    if(isset($jsonCon['mapkeys']) && $param){
                        $resarray = $this->getBindValueFromParamData($jsonCon['mapkeys'],$param);
                        if(count($resarray['ruleserror']) > 0 ){
                            return array('success'=>'false','error'=>$resarray['ruleserror']);
                        }else{
                            for($k=0;$k<count($resarray['data']);$k++){
                                $stmt->bindValue( $p++, $resarray['data'][$k], PDO::PARAM_STR);
                            }
                            $stmt->execute();
                        }
                    }

                    if($stmt->rowCount()==1){
                        if($seldb->lastInsertId())
                            $this->setResult($seldb->lastInsertId());
                        else
                            $this->setResult($stmt->rowCount());
                    }
                    else $this->setResult(0);

                    return array(
                        "result" => $this->result,
                        "error"=>'');

                }else{
                    return array(
                    "result" => -21,
                    "error"=>$this->res->lang->model->err21 );
                }

            }catch(PDOException $e){

                return array(
                    "result" => -22,
                    "error"=>$this->res->lang->model->err212 );
            }
        }else{
            $this->setResult(-1);
            if(!$param){
                return array(
                    "result" => $this->result,
                    "error"=>$this->res->lang->model->err99 );
            }
        }
        return array("result" => $this->result,
                    "error"=>'' );
    }
	
    
	

    /*
     * @brief 메일보내기
     */
    protected function mailer($to, $subject, $content, $type=0, $file="", $cc="", $bcc="")
    {
        include_once ('./Plugin/PHPMailer/PHPMailerAutoload.php');

        if ($type != 1)
            $content = nl2br($content);

        $mail = new PHPMailer(); // defaults to using php "mail()"

        $mail->isSMTP(); // telling the class to use SMTP
        $mail->CharSet = 'UTF-8';
        $mail->SMTPDebug = 0;   // debug on 3 off 0
        if($this->res->config['email']['secure']) $mail->SMTPSecure = $this->res->config['email']['secure']; // sets the prefix to the servier
        $mail->Host = $this->res->config['email']['host']; // sets GMAIL as the SMTP server
        $mail->Port = $this->res->config['email']['port']; // set the SMTP port for the GMAIL server
        $mail->Username = $this->res->config['email']['username']; // GMAIL username
        $mail->Password = $this->res->config['email']['password']; // GMAIL password
        if($mail->Password) $mail->SMTPAuth = true; // enable SMTP authentication
        $mail->From = $this->res->config['manage']['email'];
        $mail->FromName = $this->res->config['manage']['name'];

        $mail->Subject = $subject;
        $mail->AltBody = ""; // optional, comment out and test
        $mail->MsgHTML($content);
        $mail->AddAddress($to);
        if ($cc)
            $mail->AddCC($cc);
        if ($bcc)
            $mail->AddBCC($bcc);
        //print_r2($file); exit;
        if ($file != "") {
            foreach ($file as $f) {
                $mail->AddAttachment($f['path'], $f['name']);
            }
        }
        return ($mail->send())?ResError::no:-1;
    }
    
    

    /*
     ** @brief 토큰 유효성 검사
     * @return int
     */
    protected function checkToken($checkWriteTime=true){
        $result = 1;
        Session::startSession();
        $ss_token = Session::getSession("token");
        if($ss_token==null){
            $result = ResError::token;
            return $result;
        }

        $token = Utils::getPostParam('token',ResError::no);
        if(!$token || $ss_token!=$token){
            $result = ResError::token;
            return $result;
        }

        // 연속 공격 막기
        if($checkWriteTime){
            $write_time = str_replace(Session::getSessionId(),'',$ss_token);
            if ($write_time >= (time() - $this->res->config['logic']['write_sec'])){  //n초 후에 업데이트
                $result = ResError::writeSec;
                return $result;
            }
        }
        Session::delSession("token");
//        Utils::createToken();
        return $result;
    }
}