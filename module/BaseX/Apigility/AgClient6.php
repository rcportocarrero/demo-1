<?php

/**
 * Description of Client
 *
 * @author CARLOS SING RAMOS
 */

namespace BaseX\Apigility;

use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Client;

class AgClient6 {

    protected $_url_ag = '';
    protected $_username = '';
    protected $_password = '';
    protected $_client_id = '';
    protected $_client_secret = '';
    protected $_grant_type = '';
    protected $_service = '';
    protected $_client = null;
    protected $_token = '';
    protected $_auth_url = '';
    protected $_is_debug = true;
    protected $_user_agent = true;
    protected $_headers = true;

    public function __construct($options) {

        $this->_url_ag = isset($options['url']) === true ? $options['url'] : '';
        $this->_username = isset($options['username']) === true ? $options['username'] : '';
        $this->_password = isset($options['password']) === true ? $options['password'] : '';
        $this->_client_id = isset($options['client_id']) === true ? $options['client_id'] : '';
        $this->_client_secret = isset($options['client_secret']) === true ? $options['client_secret'] : '';
        $this->_grant_type = isset($options['grant_type']) === true ? $options['grant_type'] : '';
        $this->_auth_url = isset($options['auth_url']) === true ? $options['auth_url'] : 'oauth';
        $this->_client = new \GuzzleHttp\Client(['base_uri' => $this->_url_ag]);
        $this->_is_debug = isset($options['debug']) === true ? $options['debug'] : false;
        $this->_user_agent = isset($options['user_agent']) === true ? $options['user_agent'] : 'Ag Client/1.0';
        $this->_headers = isset($options['headers']) === true ? $options['headers'] : [
            'User-Agent' => $this->_user_agent,
            'Accept' => 'application/json',
            'application/json' => 'application/json',
        ];
    }

    public function set_auth_url($auth_url) {
        $this->_auth_url = $auth_url;
    }

    public function get_auth_url() {
        return $this->_auth_url;
    }

    public function auth($p_username, $p_password) {
        $params = [
            'username' => $p_username,
            'password' => $p_password,
            'grant_type' => $this->_grant_type,
            'client_id' => $this->_client_id,
            'client_secret' => $this->_client_secret,
        ];
//        var_dump($this->_url_ag.''.$this->_auth_url);
        return json_decode($this->Curl_Post_Login($this->_url_ag . '' . $this->_auth_url, $params));
//        return $this->post($this->_auth_url, $params, 'json');
    }

    public function post($method, $params = [], $type = 'json') {

        if ($this->_is_debug) {
            var_dump($params);
        }

        try {

            $resp = $this->_client->request('POST', $method, [
                $type => $params,
                'headers' => $this->_headers
            ]);

            if ($this->_is_debug) {
                var_dump($resp->getBody());
                var_dump($resp->getProtocolVersion());
                var_dump($resp->getStatusCode());
                var_dump($resp->getReasonPhrase());
                var_dump($resp->getBody()->getContents());
                var_dump($resp->getBody()->getSize());
            }
            return json_decode($resp->getBody());
        } catch (\Exception $ex) {

            echo 'Error <br />';
            echo $ex->getMessage();
            return null;
        }
    }

    function Curl_Post_Login($url, $params = array()) {
//        $data = array("name" => "Hagrid", "age" => "36");
        $data_string = json_encode($params);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        $result = curl_exec($ch);
        return $result;
    }

    public function get($function, $params = []) {

        if ($this->_is_debug) {
            var_dump($params);
        }

        try {
            if ($this->_is_debug) {
                echo $function;
            }

//            if (count($params) > 0) {
            $resp = $this->_client->request('GET', $function, [
                'query' => http_build_query($params),
                'headers' => $this->_headers
            ]);
            if ($this->_is_debug) {
                var_dump($resp->getBody());
            }
            return json_decode($resp->getBody());
        } catch (\Exception $ex) {

            echo $ex->getMessage();
            return null;
        }
    }

    public function put($method, $params, $type = 'json') {

        if ($this->_is_debug) {
            var_dump($params);
        }

        try {

            $resp = $this->_client->request('PUT', $method, [
                $type => $params,
                'headers' => $this->_headers
            ]);
            if ($this->_is_debug) {
                var_dump($resp->getBody());
                var_dump($resp->getProtocolVersion());
                var_dump($resp->getStatusCode());
                var_dump($resp->getReasonPhrase());
                var_dump($resp->getBody()->getContents());
                var_dump($resp->getBody()->getSize());
            }
            return json_decode($resp->getBody());
        } catch (\Exception $ex) {

            echo $ex->getMessage();
            return null;
        }
    }

    public function delete($method, $params = [], $type = 'json') {

        if ($this->_is_debug) {
            var_dump($params);
        }

        try {

            $resp = $this->_client->request('DELETE', $method, [
                $type => $params,
                'headers' => $this->_headers
            ]);
            if ($this->_is_debug) {
                var_dump($resp->getBody());
                var_dump($resp->getProtocolVersion());
                var_dump($resp->getStatusCode());
                var_dump($resp->getReasonPhrase());
                var_dump($resp->getBody()->getContents());
                var_dump($resp->getBody()->getSize());
                if ((int) $resp->getStatusCode() === 204) {
                    $class = new \stdClass();

                    return $class;
                }
            }
            return json_decode($resp->getBody());
        } catch (\Exception $ex) {
            echo '<br />';
            echo '<br />';
            echo '<br />';
            echo '<br />';
            echo '<br />';
            echo $ex->getMessage();
            return null;
        }
    }

}
