<?php

/**
 * Description of BaseController
 *
 * @author Carlos Sing Ramos
 */

namespace BaseX\Controller;

use Zend\Mvc\Controller\AbstractActionController;
//use Zend\I18n\Validator\Alnum;
//use Zend\I18n\Validator\Alpha;
//use Zend\Validator\Date;
//use Zend\Validator\Digits;
//use Zend\Validator\EmailAddress;
//use Zend\I18n\Validator\IsFloat;
//use Zend\I18n\Validator\IsInt;
//use Zend\I18n\Validator\IsInt;
//use Zend\Validator\NotEmpty;
//use Zend\Validator\Regex;
//use Zend\Validator\StringLength;
use Zend\View\Model\ViewModel;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;

class BaseController extends AbstractActionController {

    const INT = 10;
    const ALPHA = 20;
    const ALNUM = 30;
    const DATE = 40;
    const EMAIL = 50;
    const REGEX = 60;
    const NOTEMPTY = 70;
    const DIGIT = 80;
    const LENGTH = 90;

    protected $_need_auth = true;
    protected $storage;
    protected $fields = [];
    protected $last_validator = null;
    protected $container = null;

    public function getAuthService()
    {
        if (!$this->authservice)
        {
            $this->authservice = $this->getServiceLocator()->get('AuthService');
        }

        return $this->authservice;
    }

    public function getSessionStorage()
    {
        if (!$this->storage)
        {
            $this->storage = $this->getServiceLocator()
                    ->get('Usuario\Storage\AuthStorage');
        }
        return $this->storage;
    }

    public function getSoapClient()
    {
        $soap_client = $this->getServiceLocator()->get('Soap_Client');
        return $soap_client;
    }

    public function getAgClient()
    {
        $ag_client = $this->getServiceLocator()->get('Ag_Client');
        return $ag_client;
    }

    public function getConfig($key = '')
    {

        $config = $this->getServiceLocator()->get('config');

        if ($key === '')
        {
            return $config;
        }
        return $config[$key];
    }

    public function getTwig()
    {
        $loader = new \Twig_Loader_Filesystem('templates');
        $twig = new \Twig_Environment($loader, array(
//            'debug' => true,
//            'cache' => 'data/cache/compilation_cache',
        ));
        return $twig;
    }

    function httpPostLogin($url, $params)
    {

        $postData = '';
        //create name value pairs seperated by &
        foreach ($params as $k => $v)
        {
            $postData .= $k . '=' . $v . '&';
        }
        rtrim($postData, '&');

        $ch = \curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_POST, count($postData));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);
        $output = curl_exec($ch);

        curl_close($ch);
        return $output;
    }

    function httpPost($url, $params = array())
    {
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

    function httpPut($url, $params)
    {
        $postData = '';
        //create name value pairs seperated by &
        foreach ($params as $k => $v)
        {
            $postData .= $k . '=' . $v . '&';
        }
        rtrim($postData, '&');

        $ch = curl_init($url);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen(json_encode($params))
        ));

//        $output = curl_exec($ch);
        $response = curl_exec($ch);
        if (!$response)
        {
            return false;
        }

        curl_close($ch);
        return $response;
    }

    function httpGet($url)
    {

//        echo $url;
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $output = curl_exec($ch);

        curl_close($ch);
        return $output;
    }

    public function destroySession()
    {
        unset($_SESSION['zf_comite_cpm']);
    }

    public function procesarTemplate($ruta_template = '', $params = [])
    {
        $renderer = $this->getServiceLocator()->get('ViewRenderer');
        $model = new ViewModel($params);
        $model->setTemplate($ruta_template);
        $model->setTerminal(TRUE);
        $html = $renderer->render($model);
        return $html;
    }

    /* Send Mail */

    public function sendEmail($to, $from, $subject, $html, $text, $attachments = null, $bcc = '', $config_server = '')
    {
        $config = $this->getConfig();
        $transportConfig = new SmtpOptions($config['email_server'][$config_server]);
        $message = new Message();

        $message->addTo($to);
        $message->addFrom($from, 'Dirección de Evaluación Docente');
        if ($bcc != '')
        {
            $message->addBcc($bcc);
        }
        $message->setSubject($subject);

        // HTML part
        $htmlPart = new MimePart($html);
        $htmlPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $htmlPart->type = "text/html; charset=UTF-8";

        // Plain text part
        $textPart = new MimePart($text);
        $textPart->encoding = Mime::ENCODING_QUOTEDPRINTABLE;
        $textPart->type = "text/plain; charset=UTF-8";

        $body = new MimeMessage();
        if ($attachments)
        {
            // With attachments, we need a multipart/related email. First part
            // is itself a multipart/alternative message        
            $content = new MimeMessage();
            $content->addPart($textPart);
            $content->addPart($htmlPart);

            $contentPart = new MimePart($content->generateMessage());
            $contentPart->type = "multipart/alternative;\n boundary=\"" .
                    $content->getMime()->boundary() . '"';

            $body->addPart($contentPart);
            $messageType = 'multipart/related';

            // Add each attachment
            foreach ($attachments as $thisAttachment)
            {
                $attachment = new MimePart(fopen($thisAttachment['content'], 'r'));
                $attachment->filename = $thisAttachment['filename'];
                $attachment->type = Mime::TYPE_OCTETSTREAM;
                $attachment->encoding = Mime::ENCODING_BASE64;
                $attachment->disposition = Mime::DISPOSITION_ATTACHMENT;

                $body->addPart($attachment);
            }
        }
        else
        {
            // No attachments, just add the two textual parts to the body
            $body->setParts(array($textPart, $htmlPart));
            $messageType = 'multipart/alternative';
        }

        // attach the body to the message and set the content-type
        $message->setBody($body);
        $message->getHeaders()->get('content-type')->setType($messageType);
        $message->setEncoding('UTF-8');
        try {
            $transport = new SmtpTransport();
            $transport->setOptions($transportConfig);
            $result = $transport->send($message);
            return true;
        } catch (Exception $exc) {
            return false;
        }
    }

    public function onDispatch(\Zend\Mvc\MvcEvent $e)
    {
        parent::onDispatch($e);
        if ($this->_need_auth)
        {
            if (!$this->getServiceLocator()->get('Usuario\Storage\AuthStorage')->isAuthenticate())
            {
                return $this->redirect()->toRoute('usuario/sec');
            }
        }
    }

    public function validate_fields()
    {

//        print_r($this->fields);
//        exit;
//Zend\I18n\Validator\IsInt
        $validators_class = [
            BaseController::INT => 'Zend\Validator\Digits',
            BaseController::ALNUM => 'Zend\I18n\Validator\Alnum',
            BaseController::ALPHA => 'Zend\I18n\Validator\Alpha',
            BaseController::DATE => 'Zend\Validator\Date',
            BaseController::EMAIL => 'Zend\Validator\EmailAddress',
            BaseController::REGEX => 'Zend\Validator\Regex',
            BaseController::NOTEMPTY => 'Zend\Validator\NotEmpty',
            BaseController::DIGIT => 'Zend\Validator\Digits',
            BaseController::LENGTH => 'Zend\Validator\StringLength',
        ];

        foreach ($this->fields as $field)
        {

            foreach ($field['rules'] as $rule => $key)
            {
                $options = $field['rules'][$rule]['options'];
                $messages = $field['rules'][$rule]['messages'];

                $validator_selected = $validators_class[$rule];

                if (count($options) > 0)
                {
                    $validator = new $validator_selected($options);
                    $validator->setOptions($options);
                }
                else
                {
                    $validator = new $validator_selected();
                }
                if (count($messages) > 0)
                {

                    if (count($messages) === 1)
                    {
//                        array_values($array)[0]
                        $validator->setMessage(array_values($messages)[0]);
                    }
                    else
                    {
                        $validator->setMessages($messages);
                    }
                }
                if (!$validator->isValid($field['value']))
                {
                    $this->last_validator = $validator;
//                    var_dump($validator->getMessages());
                    return false;
                }
            }
        }
        return true;
    }

    public function add_rule($value = '', $rules = [])
    {
        $_rules = [];
        foreach ($rules as $rule => $key)
        {
            if (is_array($rules[$rule]))
            {
                if (!isset($rules[$rule]['options']))
                {
                    $rules[$rule]['options'] = [];
                }
                $_rules[$rule] = $rules[$rule];
            }
            else
            {
                $_rules[$key] = [
                    'options' => [],
                    'message' => [],
                ];
            }
        }
        array_push($this->fields, ['value' => $value, 'rules' => $_rules]);
//        $this->fields[count($this->fields)] = ['value' => $value, 'rules' => $_rules];
    }

    public function getValidationMessages()
    {
        $msgs = array_values($this->last_validator->getMessages());
        return $msgs;
    }

    public function array_to_xml($array, &$xml_user_info)
    {
        foreach ($array as $key => $value)
        {
            if (is_array($value))
            {
                if (!is_numeric($key))
                {
                    $subnode = $xml_user_info->addChild("$key");
                    $this->array_to_xml($value, $subnode);
                }
                else
                {
                    $subnode = $xml_user_info->addChild("item");
                    $this->array_to_xml($value, $subnode);
                }
            }
            else
            {
                $xml_user_info->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public function Session()
    {

        $config = $this->getConfig('session');
        return new \Zend\Session\Container($config['name']);
    }

    public function field_validate_range($fields, $data, $range)
    {
        foreach ($fields as $row)
        {
            if (!in_array($data[$row], $range))
            {
                return false;
            }
//            if (!in_array(intval($data[$row]), $range)) {
//                return new JsonModel([
//                    'id' => -100,
//                    'codigo' => -100,
//                    'msg' => 'Se ha detectado un valor inválido',
//                ]);
//            }
        }
        return TRUE;
    }

//    function firmar(data,columns){
//        var pre_txt = [];
//        var i=0;
//        var column = '';
//        for(i=0;i<columns.length;i++){
//          column = columns[i];
//          pre_txt.push(data[column]);
//        }
//
//        return Base64.encode(Kakashi.hash(Kakashi.hash(Kakashi.hash(pre_txt.join('')))));
//    }


    public function Ambu($data = [], $columns = [])
    {
        $pre_text = [];

        foreach ($columns as $column)
        {
            $pre_text[] = $data[$column];
        }
//        print_r($pre_text);

        $sha1 = sha1(sha1(sha1(implode('', $pre_text))));
        $b64 = base64_encode($sha1);
        return $b64;
    }

    function transform_detalle_to_format_xml($array, $error_msg)
    {
        $detalle_lista_tmp = [];
        foreach ($array as $row)
        {

            if (!is_numeric($row['id']))
            {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            if (!is_numeric($row['value']))
            {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            $row = [
                'c1' => intval($row['id']),
                'c2' => intval($row['value']),
            ];
            $detalle_lista_tmp[] = $row;
        }
        return $detalle_lista_tmp;
    }

    function transform_detalle_to_format_xml_c3($array, $error_msg)
    {
        $detalle_lista_tmp = [];
        foreach ($array as $row)
        {

            if (!is_numeric($row['id']))
            {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            if (!is_numeric($row['value']))
            {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            if (!is_numeric($row['rpt']))
            {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            $row = [
                'c1' => intval($row['id']),
                'c2' => intval($row['value']),
                'c3' => intval($row['rpt']),
            ];
            $detalle_lista_tmp[] = $row;
        }
        return $detalle_lista_tmp;
    }

    function curl_2($method, $data_string, $auth)
    {
        $config = $this->getConfig();
        $rest_con = $config['apigility']['config'];
        $login = $auth['user'];
        $password = $auth['pass'];
        $url = $rest_con['url'] . $method;

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string),
            'indago-tipo-login: 2')
        );
        $result = curl_exec($ch);
        $statusCode = curl_getInfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response = [
            'code' => $statusCode,
            'data' => $result
        ];
        return $response;
    }

    function curl_($method, $params, $post, $auth)
    {
        $config = $this->getConfig();
        $rest_con = $config['apigility']['config'];
        $login = $auth['user'];
        $password = $auth['pass'];
        $url = $rest_con['url'] . $method;

        $ch = curl_init();
        if (count($params) > 0)
        {
            $datos = http_build_query($params);
            $url = $url . '?' . $datos;
        }
        if ($post)
        {
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        }
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        $result = curl_exec($ch);
        $statusCode = curl_getInfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $response = [
            'code' => $statusCode,
            'data' => $result
        ];

        return $response;
    }

    function curl_login($method, $params)
    {
        $config = $this->getConfig();
        $rest_con = $config['apigility']['config'];
        $login = $params['user'];
        $password = $params['pass'];
        $url = $rest_con['url'] . $method;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        curl_setopt($ch, CURLOPT_USERPWD, "$login:$password");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER,array('indago-tipo-login: 2'));
        $result = curl_exec($ch);
        $statusCode = curl_getInfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $response = [
            'code' => $statusCode,
            'data' => $result
        ];

        return $response;
    }

}
