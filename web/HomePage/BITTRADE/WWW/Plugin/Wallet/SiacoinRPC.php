<?php      
/**
* Description of WebBbsMainCmtDAO
* @description Funhansoft PHP auto templet
* @date 2014-01-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
*/
class SiacoinRPC{


    private $rpcserver;
    private $filepath = '../WebApp/QrImage';
    private $rpc_address = '127.0.0.1:9980';
    private $rpc_pwd = '';
    

    function __construct() {
        $this->cfg = Config::getConfig();
    }
    
    public function initServer($proto,$server,$user_id=null,$user_pwd=null){
        $this->rpc_address  = $server;
        $this->rpc_pwd      = $user_pwd;
    }
    
    public function wallet_unlock($encryptionpassword) {
		if (!$this->wallet_islocked()) {
//			echo('Wallet is already unlocked');
			return false;
		}
		$result = $this->rpc('/wallet/unlock', 'POST', array('encryptionpassword'=>$encryptionpassword));
		return $result;
	}
    
    public function wallet_islocked() {
		$wallet = $this->rpc('/wallet');
		if ($wallet->unlocked == 1)
			return false;
		return true;
	}
    
    public function wallet_address() {
		return $wallet = $this->rpc('/wallet/address');
	}
    
    /**
	 * Validate a Sia address
	 *
	 * @param string $address
	 * @return bool
	 * @uses rpc()
	 */
	public function is_valid_address($address) {
		if (
			!ctype_alnum($address) ||
			strlen($address) != 76
		) {
			return false;
		}
		return true;
	}
    
    public function rpc($cmd, $request='GET', $postfields=null) {
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, $this->rpc_address.$cmd);
		// For debugging, set URL to http://httpbin.org/post and read output
		//curl_setopt($c, CURLOPT_URL, 'http://httpbin.org/post');
        
        //Authorization: Basic OmZvb2Jhcg==':'.$this->rpc_pwd
        $header = array();
        $header[] = 'Content-length: 0';
        $header[] = 'Content-type: application/json';
        $header[] = 'Authorization: Basic ' . base64_encode(':'.$this->rpc_pwd);
//        curl_setopt($c, CURLOPT_HTTPHEADER, $header);
		curl_setopt($c, CURLOPT_CUSTOMREQUEST, $request);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($c, CURLOPT_USERAGENT, 'Sia-Agent');
		if (!strcasecmp($request, 'POST')) {
			curl_setopt($c, CURLOPT_POST, count($postfields));
			curl_setopt($c, CURLOPT_POSTFIELDS, $postfields);
		}
		$data = curl_exec($c);
		curl_close($c);
		$json = json_decode($data);

		// Throw any non-JSON string as error
		if (json_last_error() != JSON_ERROR_NONE) {
			//throw new \Exception($data);
			return (object) array('result' => '0'); 
		}
		return $json;
	}
   
    


    function __destruct(){

    }
}