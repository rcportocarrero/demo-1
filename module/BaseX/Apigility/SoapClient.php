<?php

/**
 * Description of Client
 *
 * @author ROY CESPEDES P.
 */

namespace BaseX\Apigility;

class SoapClient {

    protected $_url_sc = '';
    protected $_username = '';
    protected $_password = '';
    protected $_is_debug = true;
    protected $_user_agent = true;

    public function __construct($options)
    {

        $this->_url_sc = isset($options['url']) === true ? $options['url'] : '';
        $this->_username = isset($options['username']) === true ? $options['username'] : '';
        $this->_password = isset($options['password']) === true ? $options['password'] : '';
        $this->_ip = isset($options['ip']) === true ? $options['ip'] : '';
        $this->_is_debug = isset($options['debug']) === true ? $options['debug'] : false;
    }

    public function get($function, $params = [])
    {

        if ($this->_is_debug)
        {
            var_dump($params);
        }

        try {
            if ($this->_is_debug)
            {
                echo $function;
            }

            $params['usuario'] = $this->_username;
            $params['clave'] = $this->_password;
            $params['ipsistema'] = $this->_ip;

            $soapClient = new \SoapClient($this->_url_sc, array('exceptions' => true));
            $soapResult = $soapClient->__getFunctions();
            $soapResult = $soapClient->__soapCall($function, array('parameters' => $params));
            return $soapResult;
        } catch (\SoapFault $e) {            
            $array = [
                'return' => [
                    'codigo' => 'E_404',
                    'mensaje' => $e->getMessage(),
                ]
            ];
            $object = json_decode(json_encode($array));

            return $object;
        }
    }

}
