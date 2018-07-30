<?php
class RpcServer
{
    /**
     * Configuration for the litecoind rpc
     * @var array
     */
    private $conf = [
        "user" => "",
        "pass" => "",
        "host" => "",
        "port" => 8332
    ];
    private $config;

    /**
     * Constructor
     *
     * @param array $conf See the setConf() method
     */
    public function __construct(array $conf = [])
    {
        $this->config = Config::getConfig();
        $this->setConf($conf);
    }

    /**
     * Sets the configuration for the rpc
     *
     * The configuration array should contain 4 items:
     *  "user" - The rpc username, default "test"
     *  "pass" - The rpc password, default "test"
     *  "host" - The rpc server host, default "localhost"
     *  "port" - The rpc server port, default 9332
     *
     * @param array $conf Configuration for the rpc
     * @return $this
     */
    public function setConf(array $conf)
    {
        $this->conf = array_merge($this->conf, $conf);
        return $this;
    }

    /**
     * Sends a raw query the litecoind rpc
     *
     * Returns an instance of stdClass which contains the server response.
     *
     * Example:
     * <code>
     *  $server = new Server();
     *  $info = $server->query("getinfo");
     *  echo $info->version;
     *  echo $info->balance;
     *  echo $info->difficulty;
     * </code>
     *
     * @param  string $method The method to call
     * @param  array  $params The method parameters
     * @return object
     * @throws JsonException When encoding or decoding the server data fails
     * @throws ServerException When the server returns an error message
     */
    public function query($method, $params = [])
    {
        $request_id = rand();
        $query = json_encode([
            "method" => strtolower($method),
            "params" => $params,
            "id"     => $request_id
        ]);
        if (false === $query) {
            throw new JsonException(
                "Unable to encode server request."
            );
        }

        $response = $this->exec($query);
        $obj = json_decode(trim($response));
        if (!$obj) {
            throw new JsonException(
                "Unable to decode server response."
            );
        }

        if (!empty($obj->error)) {
            throw new ServerException($obj->error->message);
        }
        if ($obj->id != $request_id) {
            throw new ServerException(
                "Server returned ID {$obj->id}, was expecting {$request_id}."
            );
        }

        return $obj->result;

    }

    /**
     * Sends the query string to the server and returns the response
     *
     * @param string $query The query string to send
     * @return string
     * @throws AuthenticationException When the wrong rpc username or password was sent
     * @throws HttpException When there was an error sending the request
     */
    protected function exec($query)
    {
        $error_str = null;
        $error_num = 0;

        $ch = curl_init("http://{$this->conf['host']}:{$this->conf['port']}");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
        curl_setopt($ch, CURLOPT_USERPWD, "{$this->conf['user']}:{$this->conf['pass']}");
        $response    = curl_exec($ch);
        $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if (false === $response) {
            $error_str = curl_error($ch);
            $error_num = curl_errno($ch);
        }
        curl_close($ch);

        if (null !== $error_str) {
            throw new RPCException($error_str, $error_num);
        }
        if (401 === $status_code) {
            throw new RPCException(
                "The RPC username or password was incorrect.",$status_code
            );
        }
        if (200 !== $status_code) {
            throw new RPCException(
                "Received HTTP status code {$status_code} from the server.",$status_code
            );
        }

        return $response;
    }
} 