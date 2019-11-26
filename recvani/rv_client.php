<?php

require_once("rv_requests.php");

class rv_client {
    protected $api_key;
    protected $model;
    protected $secret_key;
    protected $uri;
    protected $con;
    protected $id;

    function __construct($api_key, $model, $secret_key, $uri=NULL){
        $this->api_key = $api_key;
        $this->model = $model;
        $this->secret_key = $secret_key;
        if ($uri == NULL){
            $this->uri = "https://api.recvani.com/rpc";
        }
        else {
            $this->uri = $uri;
        }
        $this->id = 0;
        $this->con = curl_init($this->uri);
        curl_setopt($this->con, CURLOPT_POST, true);
        curl_setopt($this->con, CURLOPT_RETURNTRANSFER, true);

    }
   
    function __destruct(){
        curl_close($this->con);
    }

    function post($data, $headers){

        curl_setopt($this->con, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($this->con, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($this->con);
        $httpcode = curl_getinfo($this->con, CURLINFO_HTTP_CODE);
        echo $httpcode."\n";
        return $response;
    }

    function send($request){
        $this->id = $this->id+1;
        $params = $request->get_params();
        array_unshift($params , $this->model);
        $dt = array("method"=>$request->get_method(), "params"=>$params, "id"=>$this->id);
        $raw_data = json_encode($dt);
        $sign = $this->get_sign($raw_data);
        $headers = $this->get_headers($sign);
        $response = $this->post($dt, $headers);
        return $response;
    }

    function get_sign($data){
        $raw_sign = hash_hmac("sha256", $data, $this->secret_key, True);
        $sign = base64_encode($raw_sign);
        return $sign;
    }

    function get_headers($sign){
        $headers = array();
        $headers[0] = "Content-Type: application/json";
        $headers[1]  = "Authorization: = ".$sign;
        $headers[2] = "apikey: ".$this->api_key;
        return $headers;
    }
}
?>
