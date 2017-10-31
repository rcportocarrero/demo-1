<?php

namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use BaseX\Util\Util;

class PerfilController extends \BaseX\Controller\BaseController {

    protected $_need_auth = true;
    protected $container;

    public function __construct()
    {
        $this->container = new Container('sesion');
    }

    public function indexAction()
    {
        $config = $this->getConfig();
        $app_config = $config['app'];
        $usuario_cambio = $config['usuario']['num_caracteres'];
        $usuario_config = $this->getConfig('usuario');
        $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
        $usuario_json = $users_acl->rows;

        $this->layout()->apps_var = $app_config;
        $this->layout()->apps_mod_usuario = $usuario_config;

        $usuario = $this->getSessionStorage()->get('user');

        $data_usuario = $this->getAgClient()->get('ws_user_profile/' . $usuario);
        $data_codigo_ciudad = $this->getAgClient()->get('ws_ciudad');
        $data_departamento = $this->getAgClient()->get('ws_departamento');

        $pardepaPut = [
            'id_departamento' => $data_usuario->id_departamento
        ];
        $data_provincia = $this->getAgClient()->get('ws_provincia', $pardepaPut);

        $parprovPut = [
            'id_departamento' => $data_usuario->id_departamento,
            'id_provincia' => $data_usuario->id_provincia
        ];
        $data_distrito = $this->getAgClient()->get('ws_distrito', $parprovPut);

        $params_view = [
            'apps_var' => $app_config,
            'msg' => $this->flashMessenger()->getMessages(),
            'apps_usuario' => $usuario_config,
            'apps_perfil' => $usuario_config['menu_perfil'],
            'users_acl' => $usuario_json,
            'data_usuario' => $data_usuario,
            'cambio_config' => $usuario_cambio,
            'data_ciudad' => $data_codigo_ciudad,
            'data_departamento' => $data_departamento,
            'data_provincia' => $data_provincia,
            'data_distrito' => $data_distrito,
        ];
        $view = new ViewModel($params_view);
        $view->setTerminal(TRUE);
        return $view;
    }

    public function cargaprovinciaAction()
    {
        $request = $this->params();
        $id_departamento = $request->fromQuery('id_departamento', 0);
        $paramsPut = [
            'id_departamento' => $id_departamento
        ];
        $obj_provincia = null;
        $data_provincia = array();
        if ($id_departamento !== 0)
        {
            $obj_provincia = $this->getAgClient()->get('ws_provincia', $paramsPut);
        }
        if ($obj_provincia !== null)
        {
            $data_provincia = $obj_provincia;
        }
        $jsonModel = new JsonModel($data_provincia);
        return $jsonModel;
    }

    public function cargadistritoAction()
    {
        $request = $this->params();
        $id_departamento = $request->fromQuery('id_departamento', 0);
        $id_provincia = $request->fromQuery('id_provincia', 0);

        $paramsPut = [
            'id_departamento' => $id_departamento,
            'id_provincia' => $id_provincia
        ];

        $obj_distrito = null;
        $data_distrito = array();
        if ($id_departamento !== 0 && $id_provincia !== 0)
        {
            $obj_distrito = $this->getAgClient()->get('ws_distrito', $paramsPut);
        }
        if ($obj_distrito !== null)
        {
            $data_distrito = $obj_distrito;
        }
        $jsonModel = new JsonModel($data_distrito);
        return $jsonModel;
    }

    public function perfilCambiarClaveAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();
        if ($request->isPost())
        {

            $usuario_config = $config['usuario'];
            $form_password = mb_strtoupper($request->getPost('password_pri', ''));
            $form_password_old = mb_strtoupper($request->getPost('password_old', ''));
            $form_repeat_password = mb_strtoupper($request->getPost('password_ver', ''));

            $template = $usuario_config['general']['templates']['template_cambio_clave_id'];
            $min_clave = $usuario_config['num_caracteres']['contrasenias']['min'];
            $max_clave = $usuario_config['num_caracteres']['contrasenias']['max'];

            $diccionario_clave = $usuario_config['general']['diccionario_clave'];
            $enviar_correo_confirmacion = $usuario_config['menu_perfil']['flags_ws']['correo_conf_cambio_clave'];
            $ultimas_claves = $usuario_config['general']['ultimas_claves'];

            //Validar que contraseñas sean iguales
            if ($form_password === $form_repeat_password)
            {

                if (strlen($form_password) === 0)
                {
                    $jsonModel = new JsonModel(array('msg' => 'Debe ingresar una contraseña.', 'id' => -100));
                    return $jsonModel;
                }

                if (strlen($form_password) < $min_clave)
                {
                    $jsonModel = new JsonModel(array('msg' => 'La contraseña debe tener como mínimo ' . $min_clave . ' caracteres.', 'id' => -100));
                    return $jsonModel;
                }

                if (strlen($form_password) > $max_clave)
                {
                    $jsonModel = new JsonModel(array('msg' => 'La contraseña debe tener como máximo ' . $min_clave . ' caracteres.', 'id' => -100));
                    return $jsonModel;
                }

                $array_diccionario = str_split($diccionario_clave, 1);
                $array_clave = str_split(mb_strtoupper($form_password), 1);
                $resultado = array_diff($array_clave, $array_diccionario);
                if (count($resultado) > 0)
                {
                    $jsonModel = new JsonModel(array('msg' => 'La contraseña ingresada no cumple con los requisitos establecidos.', 'id' => -100));
                    return $jsonModel;
                }

                $acl_id = $config['auth']['acl_id'];

                $paramsPut = [
                    'password_old' => $form_password_old,
                    'password' => $form_password,
                    'repeat_password' => $form_repeat_password,
                    'limite' => $ultimas_claves,
                    'template' => $template,
                    'envio_correo' => $enviar_correo_confirmacion,
                    'acl' => $acl_id,
                ];

                $objResp = $this->getAgClient()->put('wsi_cambiar_clave/' . $username, $paramsPut);
                $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
                return $jsonModel;
            }
            else
            {
                $jsonModel = new JsonModel(array('msg' => 'Las contraseñas ingresadas deben ser idénticas.', 'id' => -100));
                return $jsonModel;
            }
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilCambiarCorreoAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();

        if ($request->isPost())
        {

            $form_correo = mb_strtoupper($request->getPost('correo_pri', ''));
            $form_repeat_correo = mb_strtoupper($request->getPost('correo_ver', ''));

            $template = $config['usuario']['general']['templates']['template_cambio_correo_id'];
            $max_correo = $config['usuario']['num_caracteres']['general']['correo'];
            $diccionario_correo = $config['usuario']['general']['diccionario_correo'];

            if ($form_correo === $form_repeat_correo)
            {

                if (strlen($form_correo) === 0)
                {
                    $jsonModel = new JsonModel(array('msg' => 'Ingrese los datos solicitados para poder realizar el cambio de correo electrónico.', 'id' => -100));
                    return $jsonModel;
                }

                if (strlen($form_correo) > $max_correo)
                {
                    $jsonModel = new JsonModel(array('msg' => 'La contraseña debe tener como máximo ' . $max_correo . ' caracteres.', 'id' => -100));
                    return $jsonModel;
                }

                $array_diccionario = str_split($diccionario_correo, 1);
                $array_correo = str_split(mb_strtoupper($form_correo), 1);
                $resultado = array_diff($array_correo, $array_diccionario);
                if (count($resultado) > 0)
                {
                    $jsonModel = new JsonModel(array('msg' => 'El correo ingresado no cumple con los requisitos establecidos.', 'id' => -100));
                    return $jsonModel;
                }

                $app_id = $config['auth']['app_id'];
                $acl_id = $config['auth']['acl_id'];

                $paramsPut = [
                    'correo' => $form_correo,
                    'repeat_correo' => $form_repeat_correo,
                    'template' => $template,
                    'acl' => $acl_id,
                    'appid' => $app_id,
                    'user_login' => $username
                ];
                $objResp = $this->getAgClient()->put('wsi_cambiar_correo/' . $username, $paramsPut);
                $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
                return $jsonModel;
            }
            else
            {
                $jsonModel = new JsonModel(array('msg' => 'Los correos electrónicos no coinciden, por favor verifique.', 'id' => -100));
                return $jsonModel;
            }
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilCambiarCelularAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();
        //Seteo en Sesión Nro. de intentos para el Codigo de Validación del Celular
        Util::Session()->nroint_valcel = 0;

        if ($request->isPost())
        {

            $form_celular = $request->getPost('celular_pri', '');
            $form_celular_old = $request->getPost('celular_old', '');
            $long_celular = $config['usuario']['num_caracteres']['general']['celular'];

            if (strlen($form_celular) === 0)
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor, ingrese un número de celular.', 'id' => -100));
                return $jsonModel;
            }

            //Validar que sea diferente al actual
            if ($form_celular === $form_celular_old)
            {
                $jsonModel = new JsonModel(array('msg' => 'Los celulares ingresados son idénticos.', 'id' => -100));
                return $jsonModel;
            }
            //Validar que tenga 9 digitos
            if (strlen($form_celular) != $long_celular)
            {
                $jsonModel = new JsonModel(array('msg' => 'El celular debe tener ' . $long_celular . ' digitos.', 'id' => -100));
                return $jsonModel;
            }
            //Validar si es numerico
            if (!is_numeric($form_celular))
            {
                $jsonModel = new JsonModel(array('msg' => 'Debe ingresar sólo números.', 'id' => -100));
                return $jsonModel;
            }
            //Validar que empiece con 9
            if (intval(substr($form_celular, 0, 1)) !== 9)
            {
                $json = new JsonModel(array("msg" => 'El número de celular ingresado no es válido. Por favor, verifique.', 'id' => -100));
                return($json);
            }

            $acl_id = $config['auth']['acl_id'];

            $paramsPut = [
                'username' => $username,
                'tipo_reg' => 5,
                'celular' => $form_celular_old,
                'celular_actualizar' => $form_celular,
                'email' => '',
                'email_actualizar' => '',
                'acl_id' => $acl_id,
                'user_login' => $username
            ];

            $objResp = $this->getAgClient()->put('wsi_cambiar_celcorreo/' . $username, $paramsPut);
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function validarCodigoCelularAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();
        $nro_intentos_codval_cel = $config['usuario']['general']['nro_intentos_codval_cel'];

        if ($request->isPost())
        {
            $form_codigo_valcel = $request->getPost('codigo_valcel', '');
            $long_ccelular = $config['usuario']['num_caracteres']['general']['token_sms'];

            //Validar si es numerico
            if (!is_numeric($form_codigo_valcel))
            {
                $jsonModel = new JsonModel(array('msg' => 'Debe ingresar el código de validación.', 'id' => -100));
                return $jsonModel;
            }
            //Validar que tenga 6 digitos
            if (strlen($form_codigo_valcel) != $long_ccelular)
            {
                $jsonModel = new JsonModel(array('msg' => 'El código de validación debe tener ' . $long_ccelular . ' digitos.', 'id' => -100));
                return $jsonModel;
            }

            $paramsPut = [
                'uid' => base64_encode($username),
                'state' => '0',
            ];
            $ag_client = $this->getAgClient();
            $objResp = $ag_client->get('ws_validar_token/' . $form_codigo_valcel, $paramsPut);

            if (intval($objResp->id) > 0)
            {
                $paramsPut = [
                    'token' => $form_codigo_valcel,
                    'user_login' => $username
                ];
                $objRespuesta = $this->getAgClient()->post('wsi_cambiar_celcorreo', $paramsPut);
                if (intval($objRespuesta->id) > 0)
                {
                    $params = array(
                        'id' => intval($objResp->id),
                        'msg' => $objRespuesta->msg,
                    );
                    $jsonModel = new JsonModel($params);
                    return $jsonModel;
                }
                else
                {
                    $params = array(
                        'id' => intval($objResp->id),
                        'msg' => $objRespuesta->msg,
                    );
                    $jsonModel = new JsonModel($params);
                    return $jsonModel;
                }
            }
            else
            {
                $nroint_valcel = Util::Session()->nroint_valcel + 1;
                Util::Session()->nroint_valcel = $nroint_valcel;

                if ($nroint_valcel === $nro_intentos_codval_cel)
                {
                    Util::Session()->nroint_valcel = 0;
                    $params = array(
                        'id' => intval(-200),
                        'msg' => 'Usted ha superado el límite de intentos para validar el cambio de número de celular.',
                    );
                }
                else
                {
                    $params = array(
                        'id' => intval($objResp->id),
                        'msg' => $objResp->msg,
                    );
                }

                $jsonModel = new JsonModel($params);
                return $jsonModel;
            }
        }
    }

    public function perfilCambiarTelefonoAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();
        if ($request->isPost())
        {

            $usuario_config = $config['usuario']['num_caracteres']['general'];
            $min_telefono = $usuario_config['telefono_fijo']['min'];
            $max_telefono = $usuario_config['telefono_fijo']['max'];
            $form_codigo_telefono = $request->getPost('codigo_telefono', '');
            $codigo_telefono = str_pad($form_codigo_telefono, 2, "0", STR_PAD_LEFT);
            $form_telefono = $request->getPost('telefono', '');

            if ((int) $form_codigo_telefono === 0)
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor seleccione el código de ciudad.', 'id' => -100));
                return $jsonModel;
            }
            //Validar que no empieze con 0
            if (intval(substr($form_telefono, 0, 1)) === 0)
            {
                $json = new JsonModel(array("msg" => 'Por favor ingrese un número de teléfono.', 'id' => -100));
                return($json);
            }

            if (strlen($form_telefono) < $min_telefono)
            {
                $jsonModel = new JsonModel(array('msg' => 'El número de telefono fijo debe tener como minimo ' . $min_telefono . ' caracteres.', 'id' => -100));
                return $jsonModel;
            }

            if (strlen($form_telefono) > $max_telefono)
            {
                $jsonModel = new JsonModel(array('msg' => 'El número de telefono fijo debe tener como máximo ' . $max_telefono . ' caracteres.', 'id' => -100));
                return $jsonModel;
            }

            $paramsPut = [
                'telefono_fijo' => '(' . $codigo_telefono . ') ' . $form_telefono
            ];

            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut); //
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilEliminarTelefonoAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $paramsPut = [
                'telefono_fijo' => '-'
            ];
            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut); //
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilCambiarDireccionAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();

        if ($request->isPost())
        {
            $usuario_config = $config['usuario']['num_caracteres']['general'];
            $min_direccion = $usuario_config['direccion_domicilio']['min'];
            $max_direccion = $usuario_config['direccion_domicilio']['max'];

            $id_departamento = $request->getPost('id_departamento', '');
            $id_provincia = $request->getPost('id_provincia', '');
            $id_distrito = $request->getPost('id_distrito', '');
            $des_direccion = trim(mb_strtoupper($request->getPost('des_direccion', '')));
            $des_referencia = trim(mb_strtoupper($request->getPost('des_referencia', '')));

            if ((int) $id_departamento === 0)
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor seleccione el departamento.', 'id' => -100));
                return $jsonModel;
            }

            if ((int) $id_provincia === 0)
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor seleccione la provincia.', 'id' => -100));
                return $jsonModel;
            }

            if ((int) $id_distrito === 0)
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor seleccione el distrito.', 'id' => -100));
                return $jsonModel;
            }

            if (strlen($des_direccion) === 0)
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor ingrese la dirección.', 'id' => -100));
                return $jsonModel;
            }

            if (strlen($des_direccion) < $min_direccion)
            {
                $jsonModel = new JsonModel(array('msg' => 'Ingrese una dirección válida.', 'id' => -100));
                return $jsonModel;
            }

            if (strlen($des_direccion) > $max_direccion)
            {
                $jsonModel = new JsonModel(array('msg' => 'Ingrese una dirección válida.', 'id' => -100));
                return $jsonModel;
            }
            if (strlen($des_referencia) != 0)
            {
                if (strlen($des_referencia) < $min_direccion)
                {
                    $jsonModel = new JsonModel(array('msg' => 'La referencia de dirección ingresada debe tener como minimo ' . $min_direccion . ' caracteres.', 'id' => -100));
                    return $jsonModel;
                }

                if (strlen($des_referencia) > $max_direccion)
                {
                    $jsonModel = new JsonModel(array('msg' => 'La referencia de dirección ingresada debe tener como máximo ' . $max_direccion . ' caracteres.', 'id' => -100));
                    return $jsonModel;
                }
            }


            $paramsPut = [
                'id_departamento' => $id_departamento,
                'id_provincia' => $id_provincia,
                'id_distrito' => $id_distrito,
                'des_direccion' => $des_direccion,
                'des_referencia' => $des_referencia
            ];

            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut);
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilEliminarDireccionAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $paramsPut = [
                'id_departamento' => '-',
                'id_provincia' => '-',
                'id_distrito' => '-',
                'des_direccion' => '-',
                'des_referencia' => '-'
            ];
            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut); //
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilCambiarCelularAltAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();

        if ($request->isPost())
        {

            $form_celular_alt = mb_strtoupper($request->getPost('celular_alt_pri', ''));
            $form_celular_alt_old = mb_strtoupper($request->getPost('celular_alt_old', ''));
            $long_celular = $config['usuario']['num_caracteres']['general']['celular'];

            //Validar que no este vacio
            if ($form_celular_alt === '')
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor ingrese un número de celular.', 'id' => -100));
                return $jsonModel;
            }
            //Validar que sea diferente al actual
            if ($form_celular_alt === $form_celular_alt_old)
            {
                $jsonModel = new JsonModel(array('msg' => 'El número de celular ya se encuentra registrado. Por favor, verifique.', 'id' => -100));
                return $jsonModel;
            }
            //Validar que tenga 9 digitos
            if (strlen($form_celular_alt) != $long_celular)
            {
                $jsonModel = new JsonModel(array('msg' => 'El celular debe tener ' . $long_celular . ' digitos.', 'id' => -100));
                return $jsonModel;
            }
            //Validar si es numerico
            if (!is_numeric($form_celular_alt))
            {
                $jsonModel = new JsonModel(array('msg' => 'Debe ingresar sólo números.', 'id' => -100));
                return $jsonModel;
            }
            //Validar que empiece con 9
            if (intval(substr($form_celular_alt, 0, 1)) !== 9)
            {
                $json = new JsonModel(array("msg" => 'Debe ingresar un número de celular válido.', 'id' => -100));
                return($json);
            }

            $paramsPut = [
                'celular_alterno' => $form_celular_alt
            ];

            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut); //
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilEliminarCelularAltAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $paramsPut = [
                'celular_alterno' => '-'
            ];
            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut); //
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilCambiarCorreoAltAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();

        if ($request->isPost())
        {
            $usuario_config = $config['usuario']['num_caracteres']['general'];
            $max_correo = $usuario_config['correo'];
            $form_correo_alt = mb_strtoupper($request->getPost('correo_alt_pri', ''));

            //Validar que sea diferente al actual
            if ($form_correo_alt === '')
            {
                $jsonModel = new JsonModel(array('msg' => 'Ingrese los datos solicitados para poder realizar el cambio de correo electrónico.', 'id' => -100));
                return $jsonModel;
            }
            if (strlen($form_correo_alt) > $max_correo)
            {
                $jsonModel = new JsonModel(array('msg' => 'El correo debe tener como máximo ' . $max_correo . ' caracteres.', 'id' => -100));
                return $jsonModel;
            }

            $paramsPut = [
                'email_alterno' => $form_correo_alt,
            ];
            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut);
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilEliminarCorreoAltAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $paramsPut = [
                'email_alterno' => '-'
            ];
            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut); //
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilCambiarContactoRefAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        $config = $this->getConfig();

        if ($request->isPost())
        {
            $usuario_config = $config['usuario']['num_caracteres'];

            //Nombres de Contacto de Referencia
            $min_nombres = $usuario_config['general']['nombre_contacto']['min'];
            $max_nombres = $usuario_config['general']['nombre_contacto']['max'];
            $form_nombre_ref = mb_strtoupper($request->getPost('nombre_ref_pri', ''));

            //Celular de Contacto de Referencia
            $long_celular = $usuario_config['general']['celular'];
            $form_celular_ref = $request->getPost('celular_ref_pri', '');

            //Telefono de Contacto de Referencia
            $min_telefono = $usuario_config['general']['telefono_fijo']['min'];
            $max_telefono = $usuario_config['general']['telefono_fijo']['max'];
            $form_codigo_telefono_ref = $request->getPost('codigo_telefono_ref', '');
            $codigo_telefono_ref = str_pad($form_codigo_telefono_ref, 2, "0", STR_PAD_LEFT);
            $form_telefono_ref = $request->getPost('telefono_ref', '');

            //**===Nombres de Contacto de Referencia===**//
            //Validar que ingrese del nombre del contacto
            if (strlen($form_nombre_ref) === 0)
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor ingrese el nombre del contacto de referencia.', 'id' => -100));
                return $jsonModel;
            }
            //Validar la longitud minima del nombre del contacto
            if (strlen($form_nombre_ref) < $min_nombres)
            {
                $jsonModel = new JsonModel(array('msg' => 'El nombre del contacto debe tener como mínimo ' . $min_nombres . ' caracteres.', 'id' => -100));
                return $jsonModel;
            }
            //Validar la longitud maxima del nombre del contacto
            if (strlen($form_nombre_ref) > $max_nombres)
            {
                $jsonModel = new JsonModel(array('msg' => 'El nombre del contacto debe tener como máximo ' . $max_nombres . ' caracteres.', 'id' => -100));
                return $jsonModel;
            }
            //**===Celular de Contacto de Referencia===**//
            if (strlen(trim($form_celular_ref)) > 0)
            {
                //Validar que tenga 9 digitos
                if (strlen(trim($form_celular_ref)) != $long_celular)
                {
                    $jsonModel = new JsonModel(array('msg' => 'El celular debe tener ' . $long_celular . ' digitos.', 'id' => -100));
                    return $jsonModel;
                }
                //Validar si es numerico
                if (!is_numeric($form_celular_ref))
                {
                    $jsonModel = new JsonModel(array('msg' => 'Por favor ingresar sólo números.', 'id' => -100));
                    return $jsonModel;
                }
                //Validar que empiece con 9
                if (intval(substr($form_celular_ref, 0, 1)) !== 9)
                {
                    $json = new JsonModel(array("msg" => 'El número de celular ingresado no es válido. Por favor verifique.', 'id' => -100));
                    return($json);
                }
            }
            //**===Telefono de Contacto de Referencia===**//
            if ((int) $codigo_telefono_ref > 0 || strlen(trim($form_telefono_ref)) > 0)
            {
                //Validar que seleccione codigo de ciudad
                if ((int) $form_codigo_telefono_ref === 0)
                {
                    $jsonModel = new JsonModel(array('msg' => 'Por favor seleccione el código de ciudad.', 'id' => -100));
                    return $jsonModel;
                }
                if (strlen(trim($form_telefono_ref)) === 0)
                {
                    $jsonModel = new JsonModel(array('msg' => 'Por favor ingrese el número de teléfono fijo de referencia.', 'id' => -100));
                    return $jsonModel;
                }
                //Validar que no empieze con 0
                if (intval(substr($form_telefono_ref, 0, 1)) === 0)
                {
                    $json = new JsonModel(array("msg" => 'El número de teléfono fijo ingresado no es válido. Por favor, verifique.', 'id' => -100));
                    return($json);
                }
                //Validar la longitud minima del numero de telefono
                if (strlen(trim($form_telefono_ref)) < $min_telefono)
                {
                    $jsonModel = new JsonModel(array('msg' => 'El número de telefono fijo debe tener como minimo ' . $min_telefono . ' caracteres.', 'id' => -100));
                    return $jsonModel;
                }
                //Validar la longitud maxima del numero de telefono    
                if (strlen(trim($form_telefono_ref)) > $max_telefono)
                {
                    $jsonModel = new JsonModel(array('msg' => 'El número de telefono fijo debe tener como máximo ' . $max_telefono . ' caracteres.', 'id' => -100));
                    return $jsonModel;
                }
            }

            if (strlen($form_nombre_ref) > 0 && ( (strlen(trim($form_celular_ref)) === 0 ) && ( strlen(trim($form_telefono_ref)) === 0 ) ))
            {
                $jsonModel = new JsonModel(array('msg' => 'Por favor ingrese un número de celular o de teléfono fijo del contacto de referencia.', 'id' => -100));
                return $jsonModel;
            }

            if (strlen(trim($form_celular_ref)) === 0)
            {
                $form_celular_ref = '-';
            }

            if (strlen(trim($form_telefono_ref)) === 0)
            {
                $telefono_ref = '-';
            }
            else
            {
                $telefono_ref = '(' . $codigo_telefono_ref . ') ' . $form_telefono_ref;
            }

            $paramsPut = [
                'contacto_nombres' => $form_nombre_ref,
                'contacto_celular' => $form_celular_ref,
                'contacto_telfijo' => $telefono_ref
            ];
            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut);
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function perfilEliminarContactoRefAction()
    {
        $username = $this->getSessionStorage()->get('user');
        $request = $this->getRequest();
        if ($request->isPost())
        {
            $paramsPut = [
                'contacto_nombres' => '-',
                'contacto_celular' => '-',
                'contacto_telfijo' => '-'
            ];
            $objResp = $this->getAgClient()->put('ws_user_profile/' . $username, $paramsPut); //
            $jsonModel = new JsonModel(array('msg' => $objResp->msg, 'id' => $objResp->id));
            return $jsonModel;
        }
        else
        {
            return $this->redirect()->toRoute('inicio');
        }
    }

    public function call_ws_user_profile_put($username, $params)
    {
        return $this->getAgClient()->put('ws_user_profile/' . $username, $params);
    }

}
