<?php      
/**
* Description of WebBbsMainCmtDAO
* @description Funhansoft PHP auto templet
* @date 2014-01-06
* @copyright (c)funhansoft.com
* @license http://funhansoft.com/license
* @version 4.0.0
*/
class SiacoinRPCDAO{


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
    
    public function wallet_balance() {
		return $wallet = $this->rpc('/wallet');
	}
    
    /**
	 * Backup wallet
	 *
	 * @param string $destination Path and filename of backup file
	 * @return stdClass
	 * @uses rpc()
	 */
	public function wallet_backup($destination) {
		$result = $this->rpc('/wallet/backup', 'GET', 'destination='.$destination);
		return $result;
	}

	/**
	 * Return information about consensus set
	 *
	 * @return stdClass
	 * @uses rpc()
	 */
	public function get_consensus() {
		$result = $this->rpc('/consensus');
		if (empty($result)) {
			throw new \Exception('Failed retrieving consensus set');
			return false;
		}
		return $result;
	}

	/**
	 * Verify that daemon is connected to peers
	 *
	 * @return bool
	 * @uses rpc()
	 */
	public function is_connected_to_peers() {
		$status = $this->get_gateway();
		if (count($status->peers) <= 2)
			return false;
		return true;
	}

	/**
	 * Split an IP address into the IP and port
	 *
	 * @param string IP address and port (optional)
	 * @return array IP address and port number
	 */
	public function get_peer_address_parts($address) {
		$pos = strpos($address, ':');
		if (empty($pos)) {
			$port = 9981;
			$ip = $address;
		} else {
			$ip = substr($address, 0, $pos);
			$port = substr($address, $pos + 1);
		}
		return (object) array('ip' => $ip, 'port' => $port);
	}

	/**
	 * Add a peer to the gateway
	 *
	 * @return bool
	 * @uses rpc()
	 */
	public function add_peer($address) {
		$address = $this->get_peer_address_parts($address);
		$result = $this->rpc('/gateway/connect/'.$address->ip.':'.$address->port, 'POST');
		if (!array_key_exists('Success', $result))
			throw new \Exception($result->message);
		return true;
	}

	/**
	 * Remove a peer from the gateway
	 *
	 * @return bool
	 * @uses rpc()
	 */
	public function remove_peer($address) {
		$address = $this->get_peer_address_parts($address);
		$result = $this->rpc('/gateway/disconnect/'.$address->ip.':'.$address->port, 'POST');
		if (!array_key_exists('Success', $result))
			throw new \Exception($result->message);
		return true;
	}

	/**
	 * Return information the gateway
	 *
	 * @return stdClass
	 * @uses rpc()
	 */
	public function get_gateway() {
		$json = $this->rpc('/gateway');
		return $json;
	}

	/**
	 * Get list of all active hosts in the hostdb
	 *
	 * @return stdClass List of hosts with details
	 * @uses rpc()
	 */
	public function hostdb() {
		$json = $this->rpc('/hostdb/active');
		return $json->hosts;
	}

	/**
	 * Get new address from wallet that can receive siacoins or siafunds
	 *
	 * @return string New siacoin/fund address
	 * @uses rpc()
	 */
	public function wallet_address() {
		$json = $this->rpc('/wallet/address');
		return $json->address;
	}

	/**
	 * Fetch the list of addresses from the wallet
	 *
	 * @return string Array of wallet addresses
	 * @uses rpc()
	 */
	public function wallet_addresses() {
		if ($this->wallet_islocked()) {
			throw new \Exception('Wallet is locked');
			return false;
		}
		$result = $this->rpc('/wallet/addresses');
		return $result->addresses;
	}

	/**
	 * Return a list of transactions IDs related to the wallet.
	 *
	 * @param int $startheight Block height where transaction history should start
	 * @param int $endheight Block height where transaction history should end
	 * @return stdClass Array of strings containing transactions IDs
	 * @uses rpc()
	 */
	public function wallet_transactions($startheight, $endheight) {
		$json = $this->rpc('/wallet/transactions?startheight='.$startheight.'&endheight='.$endheight);
		$transactions = new stdClass();
		$transactions->confirmed = array();
		$transactions->unconfirmed = array();
		if (!empty($json->confirmedtransactions)) {
			foreach($json->confirmedtransactions as $transaction)
				$transactions->confirmed[] = $transaction->transactionid;
		}
		if (!empty($json->unconfirmedtransactions)) {
			foreach($json->unconfirmedtransactions as $transaction)
				$transactions->unconfirmed[] = $transaction->transactionid;
		}
		return $transactions;
	}

	/**
	 * Return all transaction related to a specific address
	 *
	 * @param int $address 
	 * @return stdClass Array of strings containing transactions IDs
	 * @uses rpc()
	 */
	public function wallet_transactions_addr($address) {
		$json = $this->rpc('/wallet/transactions/'.$address);
		$transactions = new stdClass();
		if (!empty($json->confirmedtransactions)) {
			$transactions->confirmed = array();
			foreach($json->confirmedtransactions as $transaction)
				$transactions->confirmed[] = $transaction->transactionid;
		}
		if (!empty($json->unconfirmedtransactions)) {
			$transactions->unconfirmed = array();
			foreach($json->unconfirmedtransactions as $transaction)
				$transactions->unconfirmed[] = $transaction->transactionid;
		}
		return $transactions;
	}

	/**
	 * Get transaction details associated with a specific transaction id
	 *
	 * @param int $transactionid Transaction ID to look up
	 * @return stdClass Transaction object
	 * @uses rpc()
	 */
	public function wallet_transaction($transactionid) {
		$json = $this->rpc('/wallet/transaction/'.$transactionid);
		return $json->transaction;
	}

	/**
	 * Get Siacoin balance in wallet
	 *
	 * @param int $decimals Rounding factor
	 * @uses rpc()
	 */
	public function wallet_sc_balance($decimals=NULL) {
		$json = $this->rpc('/wallet');
		return $this->sia_round($json->confirmedsiacoinbalance / 10E+23, $decimals);
	}

	/**
	 * Get Siafund balance in wallet
	 *
	 * @param int $decimals Rounding factor
	 * @uses rpc()
	 */
	public function wallet_sf_balance($decimals = NULL) {
		$json = $this->rpc('/wallet');
		return $this->sia_round($json->siafundbalance / 10E+23, $decimals);
	}

	private function sia_round($n, $decimals = NULL) {
//		if ($decimals != NULL)
//			$n = number_format($n, (int)$decimals);
		return $n;
	}

	/**
	 * Send Siacoin to an address
	 *
	 * @param double $siacoins
	 * @param string $address
	 * @return string Transactions IDs
	 * @uses rpc()
	 */
	public function send_sc($siacoins, $address) {
		if ($this->wallet_islocked()) {
			throw new \Exception('Wallet is locked');
			return false;
		}
		if (!$this->is_valid_address($address)) {
			throw new \Exception('Invalid address');
			return false;
		}
		$hastings = $this->sc_to_hastings($siacoins);
		$json = $this->rpc('/wallet/siacoins', 'POST', array('amount'=>$hastings, 'destination'=>$address));
		return $json->transactionids[count($json->transactionids) - 1];
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

	/**
	 * Get all transactions involving a particular wallet address. Returns an array
	 * of objects containing transaction id, amount and timestamp.
	 *
	 * Note: This method is higher-level compared to wallet_transactions_addr() which
	 *		 only returns transactions IDs.
	 *
	 * @param string $address
	 * @return array Array of objects
	 * @uses rpc()
	 */
	public function wallet_addr_txn($address) {
		if (!$this->is_valid_address($address)) {
			throw new \Exception('Invalid address');
			return false;
		}
		$txns = $this->wallet_transactions_addr($address);
		$arr = (array)$txns;
		if (empty($arr)) {
			throw new \Exception('No transactions found');
			return $txns;
		}
		$transactions = array();
		foreach ($txns->confirmed as $id) {
			if (empty($id)) continue;
			$txn = $this->wallet_transaction($id);
			$txn_net = $this->wallet_transaction_hastings_net($txn);
			if ($txn_net == 0) continue;
			$transactions[] = (object) array(
				"txid" 		=> $id,
				"amount"	=> $this->hastings_to_sc($txn_net),
				"timestamp"	=> $txn->confirmationtimestamp * 1000,
			);
		}
		return $transactions;
	}

	/**
	 * Get net amount moved from/to wallet in a transaction
	 *
	 * @param string $transaction Transaction id
	 * @return float Siacoin total
	 */
	public function wallet_transaction_hastings_net($transaction) {
		$sum = 0;
		if (empty($transaction)) return $sum;
		if (is_array($transaction->inputs)) {
			foreach ($transaction->inputs as $input)
				if ($input->walletaddress) $sum -= $input->value;
		}
		if (is_array($transaction->outputs)) {
			foreach ($transaction->outputs as $output)
				if ($output->walletaddress) $sum += $output->value;
		}
		return $sum;
	}

	/**
	 * Upload a file
	 * Source and nickname must share the same extension
	 *
	 * @param string $source Path to the file to be uploaded.
	 * @param string $nickname Name that will be used to reference the file
	 * @return bool Success
	 * @uses rpc()
	 */
	public function renter_upload($source, $siapath) {
		if ($this->renter_check_siapath($siapath)) {
			throw new \Exception('Siapath is already in use');
			return false;
		}
		if (!$this->is_connected_to_peers()) {
			throw new \Exception('Not connected to peers');
			return false;
		}
		$result = $this->rpc('/renter/upload/'.$siapath, 'POST', array('source'=>$source));
		if (!array_key_exists('Success', $result))
			throw new \Exception($result->message);
		return $result->Success;
	}

	/**
	 * Delete a file by siapath
	 * 
	 * @param string $siapath Siapath of file
	 * @return bool Success
	 * @uses rpc()
	 */
	public function renter_delete($siapath) {
		if (!$this->renter_check_siapath($siapath)) {
			throw new \Exception('Siapath does not exist');
			return false;
		}
		$result = $this->rpc('/renter/delete/'.$siapath, 'POST');
		return $result->Success;
	}

	/**
	 * Rename a file by siapath
	 *
	 * @TODO Renaming temporarily disabled as of v0.4.2
	 * @param string $siapath Siapath of file
	 * @param string $newname New siapath of file
	 * @return bool
	 * @uses rpc()
	 */
	public function renter_rename($siapath, $newsiapath) {
		if (!$this->renter_check_siapath($siapath)) {
			throw new \Exception('Siapath does not exist');
			return false;
		}
		if (!strcasecmp(pathinfo($siapath, PATHINFO_EXTENSION), pathinfo($newsiapath, PATHINFO_EXTENSION)) == 0) {
			throw new \Exception('Extension of existing and new siapath must match');
			return false;
		}
		$result = $this->rpc('/renter/rename/'.$siapath, 'POST', array('newsiapath'=>$newsiapath));
		return $result->Success;
	}

	/**
	 * Download a file by siapath
	 *
	 * @param string $siapath Siapath of file
	 * @param string $destination Destination filepath
	 * @return bool
	 * @uses rpc(), renter_check_siapath()
	 */
	public function renter_download($siapath, $destination) {
		if (!$this->renter_check_siapath($siapath)) {
			throw new \Exception('Siapath does not exist');
			return false;
		}
		$result = $this->rpc('/renter/download/'.$siapath, 'GET', array('destination'=>$destination));
		if (!array_key_exists('Success', $result))
			throw new \Exception($result->message);
		return $result->Success;
	}

	/**
	 * Check whether a siapath is already in use
	 *
	 * @param string $siapath Siapath to check
	 * @return bool
	 * @uses rpc()
	 */
	public function renter_check_siapath($siapath) {
		$files = $this->renter_files();
		foreach($files as $file)
			if (strcmp($siapath, $file->siapath) == 0)
				return true; // nickname is already in use
		return false;
	}

	/**
	 * Get list of uploaded files
	 *
	 * @return array
	 * @uses rpc()
	 */
	public function renter_files() { return $this->rpc('/renter/files')->files; }

	/**
	 * Get the details of file in renter
	 *
	 * @param string $nick Nickname
	 * @return array File details or 0 if no such file found
	 * @uses rpc()
	 */
	public function renter_file($siapath) {
		$files = $this->renter_files();
		foreach ($files as $file) {
			if (strcmp($file->siapath, $siapath) == 0)
				return $file;
		}
		return 0;
	}

	/**
	 * Convert hastings to siacoins
	 *
	 * @param string $hastings
	 * @return double Siacoins
	 */
	public function hastings_to_sc($hastings) { return bcdiv(sprintf('%f', $hastings), "1000000000000000000000000"); }

	/**
	 * Convert siacoins to hastings
	 *
	 * @param string $siacoins
	 * @return double Hastings
	 */
	public function sc_to_hastings($siacoins) { return bcmul($siacoins, "1000000000000000000000000"); }

    
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