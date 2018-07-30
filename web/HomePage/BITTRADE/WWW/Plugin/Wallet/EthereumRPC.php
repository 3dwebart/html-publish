<?php      
/**
* Description of WebBbsMainCmtDAO
* @description Funhansoft PHP auto templet
* @date 2014-01-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 0.3.0
*/
class EthereumRPC{


    private $rpcserver;
    

    function __construct() {
        $this->cfg = Config::getConfig();
    }
    
    
    public function initServer($proto,$server,$user_id=null,$user_pwd=null){
        try {
            if($user_id && $user_pwd){
                $this->rpcserver = new jsonRPCClient($proto.'://'.$user_id.':'.$user_pwd.'@'.$server.'/',true);
            }else{
                $this->rpcserver = new jsonRPCClient($proto.'://'.$server.'/',true);
            }
        }
        catch (Exception $e) {
            new RPCException("<p>Bitcoin RPC Server error! ".$e);
        }
    }
    
     public function call(){
        return $this->rpcserver;
    }
    
    
    public function rpc($function,$param=null){
        if($param) return $this->rpcserver->$function($param);
        return $this->rpcserver->$function();
    }
   
    


    function __destruct(){

    }
}