<?php

namespace Seleccion\Controller;

use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use ZfcItp\Controller\BaseController;
use Zend\Crypt\Password\Bcrypt;
use Zend\File\Transfer\Adapter\Http;
use Zend\Mail\Message;
use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;
use Zend\Mime\Mime;
use Zend\Mime\Part as MimePart;
use Zend\Mime\Message as MimeMessage;
use Zend\Validator\ValidatorChain;
use Zend\Validator\StringLength;
use Zend\Validator\Regex;
use GuzzleHttp\Client;
use BaseX\Util\Util;
use Hashids\Hashids;

/*
 * Description of ConfiguracionController
 *
 * @author hnkr
 */

class ConfiguracionController extends \BaseX\Controller\BaseController {

    protected $needAuthentication = TRUE;
    protected $enable_layout = false;
    protected $_seleccionTable = null;
    protected $_ambitoTable = null;
    protected $_muestraTable = null;
    protected $_dreTable = null;
    protected $_ugelTable = null;
    protected $_aplicadorTable = null;
    protected $_instrumentoTable = null;
    protected $_tipo_informanteTable = null;
    protected $_tipo_instrumentoTable = null;
    protected $_grupo_preguntaTable = null;
    protected $_hashid = null;
    //Estados
    protected $_state_closed = 'FINALIZADO';
    protected $_state_expired = 'VENCIDO';
    protected $_state_id_closed = 2;
    protected $_state_id_expired = 3;
    
    
    
    public function getHashid()
    {
        
        if (!$this->_hashid)
        {
            $sm = $this->getServiceLocator();
            $this->_hashid = $sm->get('Seleccion\Model\Hashid');
        }
        return $this->_hashid;
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

    public function getGrupoPreguntaTable()
    {
        if (!$this->_grupo_preguntaTable)
        {
            $sm = $this->getServiceLocator();
            $this->_grupo_preguntaTable = $sm->get('Seleccion\Model\GrupoPreguntaTable');
        }
        return $this->_grupo_preguntaTable;
    }

    public function getInstrumentoTable()
    {
        if (!$this->_instrumentoTable)
        {
            $sm = $this->getServiceLocator();
            $this->_instrumentoTable = $sm->get('Seleccion\Model\InstrumentoTable');
        }
        return $this->_instrumentoTable;
    }

    public function getAmbitoTable()
    {
        if (!$this->_ambitoTable)
        {
            $sm = $this->getServiceLocator();
            $this->_ambitoTable = $sm->get('Seleccion\Model\AmbitoTable');
        }
        return $this->_ambitoTable;
    }

    public function getMuestraTable()
    {
        if (!$this->_muestraTable)
        {
            $sm = $this->getServiceLocator();
            $this->_muestraTable = $sm->get('Seleccion\Model\MuestraTable');
        }
        return $this->_muestraTable;
    }

    public function getDreTable()
    {
        if (!$this->_dreTable)
        {
            $sm = $this->getServiceLocator();
            $this->_dreTable = $sm->get('Seleccion\Model\DreTable');
        }
        return $this->_dreTable;
    }

    public function getUgelTable()
    {
        if (!$this->_ugelTable)
        {
            $sm = $this->getServiceLocator();
            $this->_ugelTable = $sm->get('Seleccion\Model\UgelTable');
        }
        return $this->_ugelTable;
    }

    public function getAplicadorTable()
    {
        if (!$this->_aplicadorTable)
        {
            $sm = $this->getServiceLocator();
            $this->_aplicadorTable = $sm->get('Seleccion\Model\AplicadorTable');
        }
        return $this->_aplicadorTable;
    }

    public function getTipoInformanteTable()
    {
        if (!$this->_tipo_informanteTable)
        {
            $sm = $this->getServiceLocator();
            $this->_tipo_informanteTable = $sm->get('Seleccion\Model\TipoInformanteTable');
        }
        return $this->_tipo_informanteTable;
    }

    public function getTipoInstrumentoTable()
    {
        if (!$this->_tipo_instrumentoTable)
        {
            $sm = $this->getServiceLocator();
            $this->_tipo_instrumentoTable = $sm->get('Seleccion\Model\TipoInstrumentoTable');
        }
        return $this->_tipo_instrumentoTable;
    }

    public function pruebaAction()
    {
//        $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
//        $usuario_json = $users_acl->rows;
        echo '<pre>';
        print_r(Util::Session()->active_edit_instrumento['secciones']);
        echo '</pre>';
exit;
        $array_session = array(1, 2, 3, 4, 5, 6, 7);
        $array_post = array(5, 6, 0, 0, 0);
        //Desactivar registros
        $resultado = array_diff($array_session, $array_post);
        //Nuevos registros
        $resultado2 = array_diff($array_post, $array_session);
        //Actualizar registros
        $resultado3 = array_intersect($array_post, $array_session);
        echo '<pre>';
        echo 'Desactivar registros:';
        echo '<br>';
        print_r($resultado);
        echo '<br>';
        echo '<br>';
        echo 'Nuevos registros:';
        echo '<br>';
        print_r($resultado2);
        echo '<br>';
        echo '<br>';
        echo 'Actualizar nombres,desc,orden:';
        echo '<br>';
        print_r($resultado3);

        echo '</pre>';

//        var_dump($usuario_json->usuario_id);
//        echo $this->getHashid()->encode('1');
//        echo '<br>';
//        echo '<br>';
//        echo $this->getHashid()->decode('Vg');
        exit;
    }

    public function setInstrumentoConfigAction()
    {

        $request = $this->getRequest();
        $id = $request->getPost('a', '');
        $id_tipo = (int) $request->getPost('b', '');

        if ($id_tipo === 0)
        {
            //entero 
            $id = intval($id);
        }
        else
        {
            //decode hashid
            $id = $this->getHashid()->decode($id);
        }

        Util::Session()->active_edit_instrumento = [
            'instrumento' => $id,
            'secciones' => []
        ];

        $respuesta = [
            'sucess' => 200,
        ];
        return new JsonModel($respuesta);
    }

    public function seccionesGuardarAction()
    {
        $request = $this->getRequest();
        error_reporting(E_ERROR | E_PARSE);
        $post_secciones = $request->getPost('sec', '');
        //Verificar que registros cambiaron o se agregaron
        if (count($post_secciones) > 0)
        {
            
              //Datos de sessión  usuario logueado
            $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
            $usuario_json = $users_acl->rows;

            // Inicio de cabecera
            $params = [
                'cab' => [
                    'item' => [
                        'id_instrumento' => Util::Session()->active_edit_instrumento['instrumento'],
                        'id_usuario' => $usuario_json->usuario_id,
                    ]
                ]
            ];
            
            // Inicio de detalle
            
//            $post_secciones_order = [];
            $array_ids_post = [];
            $orden = 0;
            $mbr_inseval = [];
            foreach ($post_secciones as $post_se)
            {
                $orden++;
                $datos = json_decode(base64_decode($post_se), true);
                $index = 0;
                if (strval($datos['a']) !== '0')
                {
                    $index = $this->getHashid()->decode(strval($datos['a']));
                    array_push($array_ids_post, $index);
                }
                
                
                
                if(empty($datos['c'])){
                    $datos['c'] = '-';
                }
                        
                $new = [
                    "c1" => $index,
                    "c2" => $datos['b'],
                    "c3" => $datos['c'],
                    "c4" => $orden,
                    "c5" => 0,
                ];
                $mbr_inseval[] = $new;                              

//                array_push($post_secciones_order, $new);
            }
            //Agregar los items que sólo se insetarán o modificarán
            $params['det'] = $mbr_inseval;
            //Agregar los que se eliminarán
            $array_ids_disabled = array_diff(Util::Session()->active_edit_instrumento['secciones'], $array_ids_post);
            //Recorrer array con ids de grupos a eliminar para agregarlos al xml de detalle
            foreach ($array_ids_disabled as $post_del)
            {
                $del = [
                    "c1" => $post_del,
                    "c2" => '-',
                    "c3" => '-',
                    "c4" => '-',
                    "c5" => 1,
                ];
                //Add items a eliminar en conjunto con sus preguntas
                array_push($params['det'] , $del);
            }

            $xml_format = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><reg></reg>");
            $this->array_to_xml($params, $xml_format);
            $xml_text = $xml_format->asXML();
            $data = [
                'p_xml' => "$xml_text",
            ];

//            var_dump($data);
//            exit;
            $rp = $this->getGrupoPreguntaTable()->guardar($data);
            
//            var_dump($rp);
            $lista = $this->getGrupoPreguntaTable()->listar(['id_instrumento' => Util::Session()->active_edit_instrumento['instrumento']]);
            $session_secciones =[];
            foreach ($lista as $l){
                array_push($session_secciones, $l['id_grupo_pregunta']);
            }
            
            Util::Session()->active_edit_instrumento['secciones'] = $session_secciones;

            return new JsonModel($rp);
        }
        else
        {
             return new JsonModel([
                'id' => -100,
                'codigo' => -100,
                'msg' => 'No se agregó ningún grupo de preguntas.',
            ]);
        }
    }

    public function instrumentoGuardarAction()
    {
        $request = $this->getRequest();

        $id_estrategia = intval($request->getPost('id_estrategia', 0));
        $id_intervencion = intval($request->getPost('id_intervencion', 0));
        $id_ambito = intval($request->getPost('id_ambito', 0));
        $id_tipo_instrumento = intval($request->getPost('id_tipo_instrumento', 0));
        $id_tipo_informante = intval($request->getPost('id_tipo_informante', 0));
        $id_muestra = intval($request->getPost('id_muestra', 0));
        $tx_nombre = $request->getPost('nombre', '');
        $tx_descripcion = $request->getPost('descripcion', '');
        $tx_fecha_inicio = $request->getPost('fec_inicio', '');
        $tx_fecha_fin = $request->getPost('fec_fin', '');

        $validator_fecha = new \Zend\Validator\Date(array('format' => 'd/m/Y'));

        if (!$validator_fecha->isValid($tx_fecha_inicio))
        {
            return new JsonModel([
                'id' => -100,
                'codigo' => -100,
                'msg' => 'Fecha inicio de instrumento inválida',
            ]);
        }
        if (!$validator_fecha->isValid($tx_fecha_fin))
        {
            return new JsonModel([
                'id' => -100,
                'codigo' => -100,
                'msg' => 'Fecha fin de instrumento inválida',
            ]);
        }

        $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
        $usuario_json = $users_acl->rows;
        // Formateando fecha antes de guardar
//        $date = new \DateTime($tx_fecha_inicio);
//        echo $date->format('Y-m-d H:i:s');

        $fec_i = $porciones = explode("/", $tx_fecha_inicio);
        $fec_in = $fec_i[2] . '-' . $fec_i[1] . '-' . $fec_i[0];
        $fec_f = $porciones = explode("/", $tx_fecha_fin);
        $fec_fn = $fec_f[2] . '-' . $fec_f[1] . '-' . $fec_f[0];
        $entrevista = [
            'cab' => [
                'item' => [
                    'id_instrumento' => Util::Session()->active_edit_instrumento['instrumento'],
                    'id_tipo_estrategia' => $id_estrategia,
                    'id_tipo_intervencion' => $id_intervencion,
                    'id_tipo_ambito' => $id_ambito,
                    'id_tipo_muestra' => $id_muestra,
                    'id_tipo_instrumento' => $id_tipo_instrumento,
                    'id_tipo_informante' => $id_tipo_informante,
                    'nombre' => $tx_nombre,
                    'descripcion_instrumento' => $tx_descripcion,
                    'fec_inicio' => $fec_in,
                    'fec_fin' => $fec_fn,
                    'id_usuario' => $usuario_json->usuario_id,
                ]
            ]
        ];
        
        

        $xml_entrevista = new \SimpleXMLElement("<?xml version=\"1.0\" encoding=\"UTF-8\" ?><reg></reg>");
        $this->array_to_xml($entrevista, $xml_entrevista);
        $xml_text = $xml_entrevista->asXML();
        $data = [
            'p_xml' => "$xml_text",
        ];

//        var_dump($data);
//        exit;
        $rp = $this->getInstrumentoTable()->guardar($data);

        return new JsonModel($rp[0]);
    }

    public function getInstrumentosAction()
    {
        $request = $this->params();
        $id_estrategia = (int) $request->fromQuery('le', 0);
        $id_intervencion = (int) $request->fromQuery('li', 0);
        $id_ambito = (int) $request->fromQuery('la', 0);
        $id_muestra = (int) $request->fromQuery('lm', 0);
        $where = [];
//        $id_tipo_instrumento = 1;

        $where['usuario'] = $this->getSessionStorage()->get('user');
        if (intval($id_estrategia) !== 0)
        {
            $where['estrategia'] = ' AND te.ID_TIPO_ESTRATEGIA = ' . $id_estrategia;
        }
        else
        {
            $where['estrategia'] = '';
        }
        if (intval($id_intervencion) !== 0)
        {
            $where['intervencion'] = ' AND ti.ID_TIPO_INTERVENCION = ' . $id_intervencion;
        }
        else
        {
            $where['intervencion'] = '';
        }

        if (intval($id_ambito) !== 0)
        {
            $where['ambito'] = ' AND ta.ID_TIPO_AMBITO= ' . $id_ambito;
        }
        else
        {
            $where['ambito'] = '';
        }
        if (intval($id_muestra) !== 0)
        {
            $where['muestra'] = ' AND tm.ID_TIPO_MUESTRA = ' . $id_muestra;
        }
        else
        {
            $where['muestra'] = '';
            $where['tipo_instrumento'] = '';
        }

        $lista = $this->getSeleccionTable()->getAllInstrumentos($where);

        $data = [];
        foreach ($lista as $lis)
        {
            $row = [];
            $n_sinc = 0;
            $c_inst = 0;
            $where['ID_INSTRUMENTO'] = $lis['ID_INSTRUMENTO'];
            $lista_emp = $this->getSeleccionTable()->getInstrumentoEmpleadoXaplicador($where);

            foreach ($lista_emp as $lemp)
            {
                $c_inst++;
                if (intval($lemp['SINCRONIZADO']) === 1 && intval($lemp['INSTRUMENTO_COMPLETADO']) === 1)
                {
                    $n_sinc++;
                }
            }

            $faltantes = intval($c_inst) - intval($n_sinc);

            //Verificar si el intrumento está vencido
            if (intval($lis['ESTADO_NR']) === 1)
            {
                //Vencido
                if ($c_inst === $n_sinc)
                {
                    //Instrumento empleado completado
                    $campo_estado = $this->_state_closed;
                }
                else
                {
                    //Instrumento con fichas incompletas
                    $campo_estado = $this->_state_expired;
                }
            }
            else
            {
                $campo_estado = $faltantes . '/' . $c_inst;
            }

            $row['a'] = $this->getHashid()->encode($lis['ID_INSTRUMENTO']);
            $row['b'] = $lis['NOMBRE'];
            $row['c'] = $lis['ID_TIPO_ESTRATEGIA'];
            $row['d'] = $lis['ID_TIPO_INTERVENCION'];
            $row['e'] = $lis['DES_ESTRATEGIA'];
            $row['f'] = $lis['DES_INTERVENCION'];
            $row['g'] = $lis['DES_TIP_INSTRUMENTO'];
            $row['h'] = $lis['DES_AMBITO'];
            $row['i'] = $lis['DES_MUESTRA'];
//            $row['n'] = $faltantes . '/' . $c_inst;
            $row['n'] = $campo_estado;
            $row['m'] = 20;
            $row['i'] = $lis['DESCRIPCION_INFORMANTE'];
            $row['t'] = $c_inst;
            $row['r'] = $lis['ESTADO_NR']; //para saber si está vencido o aún se encuentra activo 1=vencido  0=activo
            $row['s'] = $faltantes;

            array_push($data, $row);
        }

        return new JsonModel($data);
    }

    public function nuevoInstrumentoAction()
    {
        $lista_estrategia = $this->getSeleccionTable()->getEstrategia();
        $lista_intervencion = $this->getSeleccionTable()->getIntevencion();
        $lista_ambito = $this->getAmbitoTable()->getAmbito();
        $lista_muestra = $this->getMuestraTable()->getMuestra();
        $lista_instrumento = $this->getTipoInstrumentoTable()->listar();
        $lista_informante = $this->getTipoInformanteTable()->listar();

        $viewModel = new ViewModel([
            'estrategia' => $lista_estrategia,
            'intervencion' => $lista_intervencion,
            'ambito' => $lista_ambito,
            'muestra' => $lista_muestra,
            'l_instrumento' => $lista_instrumento,
            'l_informante' => $lista_informante,
        ]);
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function configInstrumentosAction()
    {

        $lista_estrategia = $this->getSeleccionTable()->getEstrategia();
        $lista_intervencion = $this->getSeleccionTable()->getIntevencion();
        $lista_ambito = $this->getAmbitoTable()->getAmbito();
        $lista_muestra = $this->getMuestraTable()->getMuestra();

        $viewModel = new ViewModel([
            'estrategia' => $lista_estrategia,
            'intervencion' => $lista_intervencion,
            'ambito' => $lista_ambito,
            'muestra' => $lista_muestra,
        ]);
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function configInformantesAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function configProgramacionInstrumentosAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function getInstrumentosDetailAction()
    {
//        $request = $this->params();
//        $lista_intervencion = $this->getSeleccionTable()->getIntevencion();
//        $lista_ambito = $this->getAmbitoTable()->getAmbito();
//        $lista_muestra = $this->getMuestraTable()->getMuestra();

        $params_i = [];
        $params_i['ID_INSTRUMENTO'] = Util::Session()->active_edit_instrumento['instrumento'];
        $instrumento = $this->getInstrumentoTable()->getInstrumento($params_i);
        $secciones = $this->getGrupoPreguntaTable()->listar(['id_instrumento' => Util::Session()->active_edit_instrumento['instrumento']]);

        $lista_secciones = [];
        $session_secciones = [];
        foreach ($secciones as $sec)
        {
            $new = [
                "id_grupo_pregunta" => $this->getHashid()->encode($sec['id_grupo_pregunta']),
                "nombre" => $sec['nombre'],
                "descripcion" => $sec['descripcion'],
                "orden" => $sec['orden'],
            ];
            array_push($lista_secciones, $new);
            array_push($session_secciones, $sec['id_grupo_pregunta']);
        }

        Util::Session()->active_edit_instrumento['secciones'] = $session_secciones;

        $rp = [
            'a' => $instrumento,
            'b' => $lista_secciones,
        ];
        return new JsonModel($rp);
    }

}
