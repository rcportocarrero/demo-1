<?php

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BaseX\Apigility\AgAdapter;
use Zend\Session\Container;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mail\Message;
use Zend\Mime\Message as MimeMessage;
use Zend\Mime\Part as MimePart;
use Zend\Crypt\Password\Bcrypt;
use Zend\View\Model\JsonModel;
use BaseX\Util\Util;
use Zend\Validator\ValidatorChain;
use Zend\Validator\StringLength;
use Zend\I18n\Validator\Alnum;
use Zend\Validator\Regex;
use Zend\Validator\Date;

class SecController extends \BaseX\Controller\BaseController {

    protected $_need_auth = false;
    protected $container;
    protected $_oauth_temp_table = null;
    protected $_seleccionTable = null;

    public function __construct()
    {
        $this->container = new Container('sesion');
    }

    public function getSeleccionTable()
    {
        if (!$this->_seleccionTable)
        {
            $sm = $this->getServiceLocator();
            $this->_seleccionTable = $sm->get('Seleccion\Model\SeleccionTable');
        }
        return $this->_seleccionTable;
    }

    public function getOauthTempTable()
    {
        if (!$this->_oauth_temp_table)
        {
            $sm = $this->getServiceLocator();
            $this->_oauth_temp_table = $sm->get('Usuario\Model\OauthTempTable');
        }
        return $this->_oauth_temp_table;
    }

    public function indexAction()
    {
        $app_config = $this->getConfig('app');
        $usuario_config = $this->getConfig('usuario');
        $this->layout()->apps_var = $app_config;
        $this->layout()->apps_config_caracteres = $usuario_config['num_caracteres'];

        $params_view = [
            'apps_var' => $app_config,
            'apps_usuario' => $usuario_config,
            'msg' => $this->flashMessenger()->getMessages(),
        ];
        if ($this->getSessionStorage()->isAuthenticate())
        {
            return $this->redirect()->toRoute('inicio');
        }
        $view = new ViewModel($params_view);
        return $view;
    }

    public function tplayudaAction()
    {
        $view = new ViewModel();
        $view->setTerminal(TRUE);
        return $view;
    }

    public function changePasswordAction()
    {
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $pass_current = $request->getPost('pass_current', '');
            $pass_new = $request->getPost('pass_new', '');
            $pass_confirmation = $request->getPost('pass_confirmation', '');
            $error = [];

            $params = [
                'currentPassword' => $pass_current,
                'newPassword' => $pass_new,
            ];
            $username = $this->getSessionStorage()->get('user');
            $password = $this->getSessionStorage()->get('pass');

            $result_ = $this->curl_2('user/changepassword', json_encode($params), ['user' => $username, 'pass' => $password]);
            if (intval($result_['code']) === 200)
            {
                $rpta_json = json_decode($result_['data'], true);
                if ($rpta_json['success'])
                {
                    $this->getSessionStorage()->add('pass', $pass_new);
                    $this->getSessionStorage()->save();
                    $respuesta = [
                        'id' => 200,
                        'msg' => $rpta_json['message'],
                    ];
                }
                else
                {
                    $respuesta = [
                        'id' => -100,
                        'msg' => $rpta_json['message'],
                    ];
                }
            }
            else
            {
                $respuesta = [
                    'id' => -100,
                    'msg' => 'Por favor vuelva a intentarlo en unos segundos.',
                ];
            }

            return new JsonModel($respuesta);
        }
        else
        {
            $respuesta = [
                'id' => -100,
                'msg' => 'M�todo aceptado POST.',
            ];
            return new JsonModel($respuesta);
        }
    }

    public function pruebaAction()
    {
        $p_query = [];
        $p_query['NOMBRE_USUARIO'] = 'invitado2';
        $rol = $this->getSeleccionTable()->getRol($p_query);
        var_dump($rol);
        exit;
    }

    public function validateAction()
    {

        $request = $this->getRequest();
        if ($request->isPost())
        {
            $documento = $request->getPost('usuario_sec_documento', '');
            $clave = strtoupper($request->getPost('usuario_sec_password', ''));
            $captcha = strtoupper($request->getPost('usuario_sec_captcha_login', ''));
            $config = $this->getConfig();
            $captcha_enabled = $config['usuario']['num_caracteres']['captcha']['enabled'];
            $captcha_tamanio = $config['usuario']['num_caracteres']['captcha']['tamano_codigo'];
            if ($captcha_enabled)
            {
                if (strlen($captcha) !== intval($captcha_tamanio) || $captcha !== $this->container->login_captcha)
                {
                    $this->flashMessenger()->clearMessages();
                    $this->flashmessenger()->addMessage('<div class = "alert alert-danger alert-dismissable"><i class = "fa fa-ban"></i><button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">x</button><b>Alerta!</b> El código de la imagen ingresado es incorrecto.</div>');
                    return $this->redirect()->toRoute('usuario/sec');
                }
            }

            if ($documento === '')
            {
                $this->flashMessenger()->clearMessages();
                $this->flashmessenger()->addMessage('<div class = "alert alert-danger alert-dismissable"><i class = "fa fa-ban"></i><button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">x</button><b>Alerta!</b> Debe de ingresar su usuario.</div>');
                return $this->redirect()->toRoute('usuario/sec');
            }


            if ($clave === '')
            {
                $this->flashMessenger()->clearMessages();
                $this->flashmessenger()->addMessage('<div class = "alert alert-danger alert-dismissable"><i class = "fa fa-ban"></i><button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">x</button><b>Alerta!</b> Debe de ingresar la contraseña.</div>');
                return $this->redirect()->toRoute('usuario/sec');
            }

            $params = [
                'user' => $documento,
                'pass' => $clave
            ];

            $result_ = $this->curl_login('loginsuccess', $params);
            if (intval($result_['code']) === 200)
            {
                $json_datos = json_decode($result_['data'], true);
                $this->getSessionStorage()->add('user', $documento);
                $this->getSessionStorage()->add('pass', $clave);
                $p_query = [];
                $p_query['NOMBRE_USUARIO'] = $documento;
                $rol = $this->getSeleccionTable()->getRol($p_query);
                if (count($rol) > 0)
                {
                    $id_rol_usuario = $rol[0]['ID_ROL'];
                    $des_rol_usuario = $rol[0]['DESCRIPCION'];
                    $id_usuario = $rol[0]['ID_USUARIO'];
                }
                else
                {
                    $id_rol_usuario = 0;
                    $rol_usuario = '';
                    $id_usuario = 0;
                }
                
                if(intval($id_rol_usuario) === 1  ){
                    $ruta_json = 'uadmin.json';
                }else{
                    $ruta_json = 'uadmin.json';
                }
                $users_acl = [
                    'rows' => [
                        'username' => $documento,
                        'password' => $clave,
                        'ruta' => $ruta_json,
                        'display_name' => $json_datos['b'] . ' ' . $json_datos['c'] . ' ' . $json_datos['d'],
                        'acl_id' => 59,
                        'usuario_id' => $id_usuario,
                        'id_rol' => $id_rol_usuario,
                        'rol' => $des_rol_usuario,
                        'fec_ultimo_acceso' => $json_datos['e'],
                        'fec_ini_proceso' => '2017-04-01 00:00:00',
                        'fec_fin_proceso' => '2017-06-30 00:00:00',
                        'dias_restantes' => ''
                    ]
                ];

                $this->getSessionStorage()->setAuth(true);
                $this->getSessionStorage()->save();
                $this->getSessionStorage()->add('users_acl', json_encode($users_acl));
                $this->getSessionStorage()->save();
            }
            else
            {
                $this->flashMessenger()->clearMessages();
                $this->flashmessenger()->addMessage('<div class = "alert alert-danger alert-dismissable"><button type = "button" class = "close" data-dismiss = "alert" aria-hidden = "true">x</button><b>Alerta!</b> Usuario y/o contraseña incorrecta.</div>');
                $this->getSessionStorage()->add('user', '');
                $this->getSessionStorage()->setAuth(false);
                $this->getSessionStorage()->save();
            }

            if ($this->getSessionStorage()->isAuthenticate())
            {
                return $this->redirect()->toRoute('inicio');
            }
            else
            {
                return $this->redirect()->toRoute('usuario/sec');
            }
        }
        else
        {
            return $this->redirect()->toRoute('usuario/sec');
        }
    }

    public function logoutAction()
    {
        $config = $this->getConfig();
        $this->destroySession();
        unset($_SESSION[$config['session']['name']]);
        unset($_COOKIE['PHPSESSID']);
        $_SESSION = [];
        session_destroy();

        $this->getSessionStorage()->logout();
        return $this->redirect()->toRoute('usuario/sec');
    }

    public function correo_restringido($correo)
    {

        if (!empty($correo))
        {
            //despues del @
            $correo_after = $this->after('@', $correo);
            //antes del @
            $correo_before = $this->before('@', $correo);
            $cantidad = strlen($correo_before) - 3;
            //correo restringido
            $correo_restringido = substr($correo_before, -3);
            $correo_asteriscos = '';
            for ($i = 0; $i < $cantidad; $i++)
            {
                $correo_asteriscos.='*';
            }
            $completo = $correo_asteriscos . $correo_restringido . '@' . $correo_after;
            return $completo;
        }
        return false;
    }

    public function validate_fields_bx($params_a_validar = [])
    {
        if (count($params_a_validar) > 0)
        {
            for ($i = 0; $i < count($params_a_validar); $i++)
            {
                if (!empty($params_a_validar[$i]['value']))
                {

                    //Evaluar regex pattern
                    if (isset($params_a_validar[$i]['regex']))
                    {
                        $validator_nombres_ = new \Zend\Validator\Regex(['pattern' => $params_a_validar[$i]['regex']]);
                        if (isset($params_a_validar[$i]['regex_state']) && $params_a_validar[$i]['regex_state'] === true)
                        {
                            if ($validator_nombres_->isValid($params_a_validar[$i]['value']))
                            {
                                $jsonModel = array('codigo' => -1, 'msg' => $params_a_validar[$i]['regex_message']);
                                return $jsonModel;
                                break;
                            }
                        }
                        else
                        {
                            if (!$validator_nombres_->isValid($params_a_validar[$i]['value']))
                            {
                                $jsonModel = array('codigo' => -1, 'msg' => $params_a_validar[$i]['regex_message']);
                                return $jsonModel;
                                break;
                            }
                        }
                    }
                    //Evaluar límites
                    if (isset($params_a_validar[$i]['limit']))
                    {
                        $validator_limites_nombres = new ValidatorChain();
                        $validator_limites_nombres->attach(new StringLength(array('min' => $params_a_validar[$i]['limit']['min'], 'max' => $params_a_validar[$i]['limit']['max'])));
                        if (!$validator_limites_nombres->isValid($params_a_validar[$i]['value']))
                        {
                            $jsonModel = array('codigo' => -1, 'msg' => $params_a_validar[$i]['limit_message']);
                            return $jsonModel;
                            break;
                        }
                    }
                    //Evaluar fecha
                    if (isset($params_a_validar[$i]['date']))
                    {
                        $valid_fecha = new \Zend\Validator\Date(array('format' => $params_a_validar[$i]['date']['format']));
                        if (!$valid_fecha->isValid($params_a_validar[$i]['value']))
                        {
                            $jsonModel = array('codigo' => -1, 'msg' => $params_a_validar[$i]['date_message']);
                            return $jsonModel;
                        }
                    }
                }
                else
                {
                    $jsonModel = array('codigo' => -1, 'msg' => $params_a_validar[$i]['required_message']);
                    return $jsonModel;
                }
            }
        }
    }

    public function calcularEdad($fecha_nac)
    {
        $fecha = time() - strtotime($fecha_nac);
        $edad = floor($fecha / 31556926);
        return $edad;
    }

    public function xml_log_reniec($params = [])
    {
        $cab = "/reg/cab/item";
        $log_reniec_xml = new \SimpleXMLElement('<?xml version="1.0" encoding="utf-8"?><reg></reg>');
        $this->array_to_xml($params, $log_reniec_xml);
        $xml_file = $log_reniec_xml->asXML();
        //parar parametros a store
        $params_sp = [];
        $params_sp['p_xml'] = $xml_file;
        $params_sp['node_cab'] = $cab;
        $params_sp['extra'] = '';
        $rp = $this->getOauthTempTable()->sp_log_reniec($params_sp);
    }

    public function fingerprint($value, $ciclos)
    {

        $hash = $value;
        while ($ciclos > 0)
        {
            $hash = sha1($hash);
            $ciclos--;
        }
        return $hash;
    }

    public function after($variable, $inthat)
    {
        if (!is_bool(strpos($inthat, $variable)))
        {
            return substr($inthat, strpos($inthat, $variable) + strlen($variable));
        }
    }

    public function before($variable, $inthat)
    {
        return substr($inthat, 0, strpos($inthat, $variable));
    }

}
