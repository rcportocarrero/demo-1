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
 * Description of IndexController
 *
 * @author hnkr
 */

class IndexController extends \BaseX\Controller\BaseController {

    protected $needAuthentication = TRUE;
    protected $enable_layout = false;
    protected $_seleccionTable = null;
    protected $_ambitoTable = null;
    protected $_muestraTable = null;
    protected $_dreTable = null;
    protected $_ugelTable = null;
    protected $_aplicadorTable = null;
    protected $_instrumentoTable = null;
    //Estados
    protected $_state_closed = 'FINALIZADO';
    protected $_state_expired = 'VENCIDO';
    protected $_state_id_closed = 2;
    protected $_state_id_expired = 3;
    protected $_hashid = null;

    public function getHashid() {
        if (!$this->_hashid) {
            $sm = $this->getServiceLocator();
            $this->_hashid = $sm->get('Seleccion\Model\Hashid');
        }
        return $this->_hashid;
    }

    public function getSeleccionTable() {
        if (!$this->_seleccionTable) {
            $sm = $this->getServiceLocator();
            $this->_seleccionTable = $sm->get('Seleccion\Model\SeleccionTable');
        }
        return $this->_seleccionTable;
    }

    public function getInstrumentoTable() {
        if (!$this->_instrumentoTable) {
            $sm = $this->getServiceLocator();
            $this->_instrumentoTable = $sm->get('Seleccion\Model\InstrumentoTable');
        }
        return $this->_instrumentoTable;
    }

    public function getAmbitoTable() {
        if (!$this->_ambitoTable) {
            $sm = $this->getServiceLocator();
            $this->_ambitoTable = $sm->get('Seleccion\Model\AmbitoTable');
        }
        return $this->_ambitoTable;
    }

    public function getMuestraTable() {
        if (!$this->_muestraTable) {
            $sm = $this->getServiceLocator();
            $this->_muestraTable = $sm->get('Seleccion\Model\MuestraTable');
        }
        return $this->_muestraTable;
    }

    public function getDreTable() {
        if (!$this->_dreTable) {
            $sm = $this->getServiceLocator();
            $this->_dreTable = $sm->get('Seleccion\Model\DreTable');
        }
        return $this->_dreTable;
    }

    public function getUgelTable() {
        if (!$this->_ugelTable) {
            $sm = $this->getServiceLocator();
            $this->_ugelTable = $sm->get('Seleccion\Model\UgelTable');
        }
        return $this->_ugelTable;
    }

    public function getAplicadorTable() {
        if (!$this->_aplicadorTable) {
            $sm = $this->getServiceLocator();
            $this->_aplicadorTable = $sm->get('Seleccion\Model\AplicadorTable');
        }
        return $this->_aplicadorTable;
    }

    public function instrumentosAction() {
        $lista_estrategia = $this->getSeleccionTable()->getEstrategia();
        $lista_intervencion = $this->getSeleccionTable()->getIntevencion();
        $lista_ambito = $this->getAmbitoTable()->getAmbito();
        $lista_muestra = $this->getMuestraTable()->getMuestra();
//        $lista_dre = $this->getSeleccionTable()->getDre();

        $viewModel = new ViewModel([
            'estrategia' => $lista_estrategia,
            'intervencion' => $lista_intervencion,
            'ambito' => $lista_ambito,
            'muestra' => $lista_muestra,
//            'dre' => $lista_dre
        ]);
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function instrumentosdetAction() {
        $where_e = [];
        $where_e['us.NOMBRE_USUARIO'] = $this->getSessionStorage()->get('user');
        $lista_empleados = $this->getAplicadorTable()->getEmpleados($where_e);

        $arr_emp = [];
        foreach ($lista_empleados as $lis) {
            array_push($arr_emp, $lis['ID_EMPLEADO']);
        }

        $params = [];
        $params['i.ID_APLICADOR'] = $arr_emp;
        $lista_dre = $this->getDreTable()->getDre($params);

        $id_ = Util::Session()->id_instrumento;
        $id_instrumento = $this->getHashid()->decode($id_);
        $params_i = [];
        $params_i['ID_INSTRUMENTO'] = intval($id_instrumento);
        $datos_ins = $this->getInstrumentoTable()->getInstrumento($params_i);

        $viewModel = new ViewModel(['lista_dre' => $lista_dre, 'datos' => $datos_ins]);
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function inicioAction() {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function reportesAction() {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

    public function getUgelDreInsAction() {
        $request = $this->params();
        $id = $request->fromQuery('id', '');
        $params = [];
        $params['u.ID_DRE'] = $id;
        $params['u.ESTADO'] = 1;
        $lista_ugel = $this->getSeleccionTable()->getUgel($params);
        return new JsonModel($lista_ugel);
    }

//    public function getInstrumentosAction() {
//        
//    }
    public function getInstrumentosAction() {
        $request = $this->params();
        $id_estrategia = (int) $request->fromQuery('le', 0);
        $id_intervencion = (int) $request->fromQuery('li', 0);
        $id_ambito = (int) $request->fromQuery('la', 0);
        $id_muestra = (int) $request->fromQuery('lm', 0);
        $where = [];
//        $id_tipo_instrumento = 1;

        $where['usuario'] = $this->getSessionStorage()->get('user');
        if (intval($id_estrategia) !== 0) {
            $where['estrategia'] = ' AND te.ID_TIPO_ESTRATEGIA = ' . $id_estrategia;
        } else {
            $where['estrategia'] = '';
        }
        if (intval($id_intervencion) !== 0) {
            $where['intervencion'] = ' AND ti.ID_TIPO_INTERVENCION = ' . $id_intervencion;
        } else {
            $where['intervencion'] = '';
        }

        if (intval($id_ambito) !== 0) {
            $where['ambito'] = ' AND ta.ID_TIPO_AMBITO= ' . $id_ambito;
        } else {
            $where['ambito'] = '';
        }
        if (intval($id_muestra) !== 0) {
            $where['muestra'] = ' AND tm.ID_TIPO_MUESTRA = ' . $id_muestra;
        } else {
            $where['muestra'] = '';
            $where['tipo_instrumento'] = '';
        }

        $where_e['us.NOMBRE_USUARIO'] = $this->getSessionStorage()->get('user');
        $lista_empleados = $this->getAplicadorTable()->getEmpleados($where_e);
        $arr_emp = [];
        foreach ($lista_empleados as $lis) {
            array_push($arr_emp, $lis['ID_EMPLEADO']);
        }
        $where['aplicador'] = implode(",", $arr_emp);
        $lista = $this->getSeleccionTable()->getInstrumentoXaplicador($where);

        $data = [];
        foreach ($lista as $lis) {
            $row = [];
            $n_sinc = 0;
            $c_inst = 0;
            $where['ID_INSTRUMENTO'] = $lis['ID_INSTRUMENTO'];
            $lista_emp = $this->getSeleccionTable()->getInstrumentoEmpleadoXaplicador($where);

            foreach ($lista_emp as $lemp) {
                $c_inst++;
                if (intval($lemp['SINCRONIZADO']) === 1 && intval($lemp['INSTRUMENTO_COMPLETADO']) === 1) {
                    $n_sinc++;
                }
            }

            $faltantes = intval($c_inst) - intval($n_sinc);

            //Verificar si el intrumento está vencido
            if (intval($lis['ESTADO_NR']) === 1) {
                //Vencido
                if ($c_inst === $n_sinc) {
                    //Instrumento empleado completado
                    $campo_estado = $this->_state_closed;
                } else {
                    //Instrumento con fichas incompletas
                    $campo_estado = $this->_state_expired;
                }
            } else {
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

    public function setInstrumentosAction() {

        $request = $this->getRequest();
        $id = $request->getPost('k', '');
        Util::Session()->id_instrumento = $id;

        $params_i = [];
        $params_i['ID_INSTRUMENTO'] = $this->getHashid()->decode(Util::Session()->id_instrumento);
        $datos_ins = $this->getInstrumentoTable()->getInstrumento($params_i);
        if (count($datos_ins) > 0) {
            Util::Session()->validate_ins = [
                'instrumento' => $this->getHashid()->decode(Util::Session()->id_instrumento),
                'fecha_inicio' => $datos_ins[0]['FECHA_INICIO'],
                'fecha_fin' => $datos_ins[0]['FECHA_FIN'],
                'ESTADO_NR' => $datos_ins[0]['ESTADO_NR'],
            ];
        }
        $respuesta = [
            'sucess' => 200,
        ];
        return new JsonModel($respuesta);
    }

    public function saveFrmFnAction() {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('a', '');
            $id_empleado = base64_decode($id);
            $params = [
                'id_instrumento_empleado' => $id_empleado
            ];

            $username = $this->getSessionStorage()->get('user');
            $password = $this->getSessionStorage()->get('pass');
            $result = $this->curl_('question/close', $params, true, ['user' => $username, 'pass' => $password]);

            if (intval($result['code']) === 200) {
                $rpta_json = json_decode($result['data'], true);
                if ($rpta_json['success']) {
                    $respuesta = [
                        'id' => 200,
                        'msg' => 'El instrumento fue finalizado.',
                    ];
                } else {
                    $respuesta = [
                        'id' => -100,
                        'msg' => 'Aún hay items sin responder. Para finalizar debe responder todos los items del instrumento.',
                    ];
                }
            } else {
                $respuesta = [
                    'id' => -100,
                    'msg' => 'Por favor, volver a intentarlo en unos minutos.',
                ];
            }
        } else {
            $respuesta = [
                'id' => -100,
                'msg' => 'Método aceptado : POST',
            ];
        }


        return new JsonModel($respuesta);
    }

    public function saveFrmAction() {

        $request = $this->getRequest();
        if ($request->isPost()) {
            $id = $request->getPost('a', '');
            if ($this->is_base64_encoded($id)) {
                $username = $this->getSessionStorage()->get('user');
                $password = $this->getSessionStorage()->get('pass');

                $result = $this->curl_2('question/save', base64_decode($id), ['user' => $username, 'pass' => $password]);
                $resultados = [];
                if (intval($result['code']) === 200) {
                    $rpta_json = json_decode($result['data'], true);
                    if ($rpta_json['success']) {
                        $respuesta = [
                            'id' => 200,
                            'msg' => 'Guardado correctamente.',
                            'rest' => intval($rpta_json['message'])
                        ];
                    } else {
                        $respuesta = [
                            'id' => -100,
                            'msg' => 'Error al guardar.',
                        ];
                    }
                }
            } else {
                $respuesta = [
                    'id' => -100,
                    'msg' => 'Formato erróneo.',
                ];
            }
        } else {
            $respuesta = [
                'id' => -100,
                'msg' => 'Método aceptado : POST',
            ];
        }


        return new JsonModel($respuesta);
    }

    public function is_base64_encoded($data) {
        if (preg_match('%^[a-zA-Z0-9/+]*={0,2}$%', $data)) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    public function getResumenAction() {

        $request = $this->params();
        $id = $request->fromQuery('id', '');

        $params = [
            'id_instrumento' => $id
        ];

        $username = $this->getSessionStorage()->get('user');
        $password = $this->getSessionStorage()->get('pass');

        $result = $this->curl_('instrumentemployee/list', $params, true, ['user' => $username, 'pass' => $password]);

        if (intval($result['code']) === 200) {
            $json_response = json_decode($result['data'], true);
            $resultados = [];
            if (count($json_response) > 0) {
                $total_finalizados = 0;
                $total_pendientes = 0;
                $total_proceso = 0;
                foreach ($json_response as $listaInstrumentos) {
                    if (intval($listaInstrumentos['l']) === intval($listaInstrumentos['k'])) {
                        //Finalizado
                        $total_finalizados++;
                    } else if (intval($listaInstrumentos['l']) === 0) {
                        $total_pendientes++;
                    } else if (intval($listaInstrumentos['l']) > 0) {
                        if (intval($listaInstrumentos['l']) != intval($listaInstrumentos['k'])) {
                            $total_proceso++;
                        }
                    }
                }

                $respuesta = [
                    ['name' => 'Pendientes', 'y' => $total_pendientes],
                    ['name' => 'Finalizados', 'y' => $total_finalizados],
                    ['name' => 'En proceso', 'y' => $total_proceso],
                ];
            }
        } else {
            $respuesta = [];
        }
        return new JsonModel($respuesta);
    }

    public function getInstrumentosDetailAction() {

        $request = $this->params();
        $id_dre = (int) $request->fromQuery('d', 0);
        $id_ugel = (int) $request->fromQuery('u', 0);
        $id_iiee = $request->fromQuery('i', '');
        $desc_nombres = $request->fromQuery('n', '');
        $num_doc = $request->fromQuery('k', '');

        $where_e = [];
        $where_e['us.NOMBRE_USUARIO'] = $this->getSessionStorage()->get('user');
        $lista_empleados = $this->getAplicadorTable()->getEmpleados($where_e);
        $arr_emp = [];
        foreach ($lista_empleados as $lis) {
            array_push($arr_emp, $lis['ID_EMPLEADO']);
        }

        $params = [];
        $params['i.ID_APLICADOR'] = $arr_emp;
        $lista_dre = $this->getDreTable()->getDre($params);

        $params_e = [];
        $params_e['ie.ID_INSTRUMENTO'] = $this->getHashid()->decode(Util::Session()->id_instrumento);
        $params_e['ie.ID_APLICADOR'] = $arr_emp;
        if ($id_dre !== 0) {
            $params_e['dre.ID_DRE'] = $id_dre;
        }

        if ($id_ugel !== 0) {
            $params_e['ugel.ID_UGEL'] = $id_ugel;
        }
        if (is_numeric($num_doc) && strlen($num_doc) === 8) {
            $params_e['pe.DNI'] = $num_doc;
        }

        $like = [];
        if (!empty($id_iiee)) {
            $like['iiee'] = $id_iiee;
        } else {
            $like['iiee'] = '';
        }
        if (!empty($desc_nombres)) {
            $like['nombres'] = $desc_nombres;
        } else {
            $like['nombres'] = '';
        }
        $valida_instrumento = Util::Session()->validate_ins;
        $lista_inst_emp = $this->getInstrumentoTable()->getInstrumentoEmpleado($params_e, $like);

        $resultados = [];
        foreach ($lista_inst_emp as $listaInstrumentos) {
            if (intval($listaInstrumentos['SINCRONIZADO']) === 1 && intval($listaInstrumentos['INSTRUMENTO_COMPLETADO']) === 1) {
                $sincronizado = 1;
            } else {
                $sincronizado = 0;
            }
            $estado_instrumento_empleado = 1;
            $params_rpta = [];
            $params_rpta['ID_INSTRUMENTO_EMPLEADO'] = $listaInstrumentos['ID_INSTRUMENTO_EMPLEADO'];
            $lista_inst_emp_rpta = $this->getInstrumentoTable()->getPreguntasRespuestas($params_rpta);
            if (intval($valida_instrumento['ESTADO_NR']) === 1) {
                //Instrumento vencido
                if (intval($listaInstrumentos['ESTADO']) === 2) {
                    $campo_estado = $this->_state_closed;
                    $estado_instrumento_empleado = $this->_state_id_closed;
                } else {
                    $campo_estado = $this->_state_expired;
                    $estado_instrumento_empleado = $this->_state_id_expired;
                }

                if (intval($listaInstrumentos['ESTADO']) === 1) {
                    //Se cambió el estado para que pueda seguir respondiendo
                    $t_editable = 0;
                } else {
                    //Finalizado o vencido pero sólo puede visualizar respuestas
                    $t_editable = 1;
                }
            } else {
                $campo_estado = $lista_inst_emp_rpta[0]['TOTAL_AVANCE'] . '/' . $lista_inst_emp_rpta[0]['TOTAL_PREGUNTAS'];
                if (intval($listaInstrumentos['INSTRUMENTO_COMPLETADO']) === 1) {
                    // Ya completó así que sólo puede visualizar
                    $t_editable = 1;
                } else {
                    // Puede llenar respuestas
                    $t_editable = 0;
                }
            }
            $rpta = [
                'id_instrumento' => $this->getHashid()->encode($listaInstrumentos['ID_INSTRUMENTO']),
                'id_instrumento_empleado' => $this->getHashid()->encode($listaInstrumentos['ID_INSTRUMENTO_EMPLEADO']),
                'a' => $this->getHashid()->encode($listaInstrumentos['ID_INSTRUMENTO_EMPLEADO']),
                'informante' => $listaInstrumentos['NOMBRES'] . ' ' . $listaInstrumentos['APELLIDO_PATERNO'] . ' ' . $listaInstrumentos['APELLIDO_MATERNO'],
//                'estado' => $lista_inst_emp_rpta[0]['TOTAL_AVANCE'] . '/' . $lista_inst_emp_rpta[0]['TOTAL_PREGUNTAS'],
                'estado' => $campo_estado,
                'id_dre' => $listaInstrumentos['ID_DRE'],
                'id_ugel' => $listaInstrumentos['ID_UGEL'],
                'ndoc' => $listaInstrumentos['DNI'],
                'codmod' => $listaInstrumentos['CODIGO'],
                'institucion_educativa' => $listaInstrumentos['NOMBRE'],
                'total' => $lista_inst_emp_rpta[0]['TOTAL_PREGUNTAS'],
                'd' => $listaInstrumentos['NOM_DRE'],
                'ug' => $listaInstrumentos['NOM_UGEL'],
                'r' => $listaInstrumentos['ESTADO'],
                's' => $sincronizado,
                't' => $t_editable,
                'u' => $estado_instrumento_empleado,
                'ns' => $lista_inst_emp_rpta[0]['TOTAL_AVANCE'],
            ];
            array_push($resultados, $rpta);
        }

        $respuesta = [
            'empleados' => $resultados,
            'lista_dre' => $lista_dre,
        ];

        return new JsonModel($respuesta);
    }

    public function generarDocDigital($nombre_pdf) {
        $config = $this->getConfig();
        $mpdf = new \mPDF('c', 'A4', '10px', 'Tahoma', 10, 10, 20, 20, 10, 0, 'L');
        $mpdf->mirrorMargins = 0;

        $mpdf->SetHTMLFooter('
            <table width="100%" style="vertical-align: bottom; font-family: Tahoma; font-size: 12px;">
                <tr>
                    <td width="50%" style="text-align: left; ">MINEDU – AP Móvil v.1.0</td>
                    <td width="50%" align="right" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                </tr>
            </table>
            ');

        $where = [];
        $id_tipo_instrumento = 1;
        $where['usuario'] = $this->getSessionStorage()->get('user');
        $where_e['us.NOMBRE_USUARIO'] = $this->getSessionStorage()->get('user');
        $lista_empleados = $this->getAplicadorTable()->getEmpleados($where_e);
        $arr_emp = [];
        foreach ($lista_empleados as $lis) {
            array_push($arr_emp, $lis['ID_EMPLEADO']);
        }
        $where['aplicador'] = implode(",", $arr_emp);
        $lista = $this->getSeleccionTable()->getInstrumentoXaplicador($where);
        $lista_emp = $this->getSeleccionTable()->getInstrumentoEmpleadoXaplicador($where);
        
        $data = [];
        foreach ($lista as $lis) {
            $row = [];
            $n_sinc = 0;
            $c_inst = 0;
            foreach ($lista_emp as $lemp) {
                $c_inst++;
                if (intval($lemp['SINCRONIZADO']) === 1 && intval($lemp['INSTRUMENTO_COMPLETADO']) === 1) {
                    $n_sinc++;
                }
            }

            $faltantes = intval($c_inst) - intval($n_sinc);
            $row['a'] = $this->getHashid()->encode($lis['ID_INSTRUMENTO']);
            $row['b'] = $lis['NOMBRE'];
            $row['c'] = $lis['ID_TIPO_ESTRATEGIA'];
            $row['d'] = $lis['ID_TIPO_INTERVENCION'];
            $row['e'] = $lis['DES_ESTRATEGIA'];
            $row['f'] = $lis['DES_INTERVENCION'];
            $row['g'] = $lis['DES_TIP_INSTRUMENTO'];
            $row['h'] = $lis['DES_AMBITO'];
            $row['i'] = $lis['DES_MUESTRA'];
            $row['n'] = $faltantes . '/' . $c_inst;
            $row['i'] = 'Docentes';
            $row['t'] = $c_inst;
            $row['s'] = $faltantes;
            array_push($data, $row);
        }

        $view_param = [
            'datos_instrumento' => $data,
        ];
        $html = $this->procesarTemplate('seleccion/templates/template_doc_digital.phtml', $view_param);
        $mpdf->WriteHTML($html);
        $uploaddir = $config['usuario']['path_files']['pdf'];
        $nombre_fichero = realpath($uploaddir) . '/' . $nombre_pdf . '.pdf';
        if (is_dir($uploaddir)) {
            $mpdf->Output($nombre_fichero, 'F');
        }
        return true;
    }

    public function generarDocDigitalDet($nombre_pdf) {
        $config = $this->getConfig();
        $mpdf = new \mPDF('c', 'A4', '10px', 'Tahoma', 10, 10, 20, 20, 10, 0, 'L');
        $mpdf->mirrorMargins = 0;

        $mpdf->SetHTMLFooter('
            <table width="100%" style="vertical-align: bottom; font-family: Tahoma; font-size: 12px;">
                <tr>
                    <td width="50%" style="text-align: left; ">MINEDU – AP Móvil v.1.0</td>
                    <td width="50%" align="right" style="font-weight: bold;">{PAGENO}/{nbpg}</td>
                </tr>
            </table>
            ');

        $where_e = [];
        $where_e['us.NOMBRE_USUARIO'] = $this->getSessionStorage()->get('user');
        $lista_empleados = $this->getAplicadorTable()->getEmpleados($where_e);
        $arr_emp = [];
        foreach ($lista_empleados as $lis) {
            array_push($arr_emp, $lis['ID_EMPLEADO']);
        }
        
        
        $params_e = [];
        $params_e['ie.ID_INSTRUMENTO'] = $this->getHashid()->decode(Util::Session()->id_instrumento);
        $params_e['ie.ID_APLICADOR'] = $arr_emp;
        $lista_inst_emp = $this->getInstrumentoTable()->getInstrumentoEmpleado($params_e, $like);

        $resultados = [];
        foreach ($lista_inst_emp as $listaInstrumentos) {
            if (intval($listaInstrumentos['SINCRONIZADO']) === 1 && intval($listaInstrumentos['INSTRUMENTO_COMPLETADO']) === 1) {
                $sincronizado = 1;
            } else {
                $sincronizado = 0;
            }

            $params_rpta = [];
            $params_rpta['ID_INSTRUMENTO_EMPLEADO'] = $listaInstrumentos['ID_INSTRUMENTO_EMPLEADO'];
            $lista_inst_emp_rpta = $this->getInstrumentoTable()->getPreguntasRespuestas($params_rpta);

            $rpta = [
                'id_instrumento' => $this->getHashid()->encode($listaInstrumentos['ID_INSTRUMENTO']),
                'id_instrumento_empleado' => $this->getHashid()->encode($listaInstrumentos['ID_INSTRUMENTO_EMPLEADO']),
                'a' => $this->getHashid()->encode($listaInstrumentos['ID_INSTRUMENTO_EMPLEADO']),
                'informante' => $listaInstrumentos['NOMBRES'] . ' ' . $listaInstrumentos['APELLIDO_PATERNO'] . ' ' . $listaInstrumentos['APELLIDO_MATERNO'],
                'estado' => $lista_inst_emp_rpta[0]['TOTAL_AVANCE'] . '/' . $lista_inst_emp_rpta[0]['TOTAL_PREGUNTAS'],
                'id_dre' => $listaInstrumentos['ID_DRE'],
                'id_ugel' => $listaInstrumentos['ID_UGEL'],
                'ndoc' => $listaInstrumentos['DNI'],
                'codmod' => $listaInstrumentos['CODIGO'],
                'institucion_educativa' => $listaInstrumentos['NOMBRE'],
                'total' => $lista_inst_emp_rpta[0]['TOTAL_PREGUNTAS'],
                'd' => $listaInstrumentos['NOM_DRE'],
                'u' => $listaInstrumentos['NOM_UGEL'],
                's' => $sincronizado,
                'ns' => $lista_inst_emp_rpta[0]['TOTAL_AVANCE'],
            ];
            array_push($resultados, $rpta);
        }
        $params_i = [];
        $params_i['ID_INSTRUMENTO'] = $hashid_instrumento[0];
        $datos_ins = $this->getInstrumentoTable()->getInstrumento($params_i);

        $view_param = [
            'datos_instrumento' => $resultados,
            'datos' => $datos_ins
        ];
        $html = $this->procesarTemplate('seleccion/templates/template_doc_digital_det.phtml', $view_param);
        $mpdf->WriteHTML($html);
        $uploaddir = $config['usuario']['path_files']['pdf'];
        $nombre_fichero = realpath($uploaddir) . '/' . $nombre_pdf . '.pdf';
        if (is_dir($uploaddir)) {
            $mpdf->Output($nombre_fichero, 'F');
        }
        return true;
    }

    public function pdfdwAction() {
        $config = $this->getConfig();
        $nombre_pdf = 'lista_instrumentos';
        $this->generarDocDigital($nombre_pdf);
        $data_file1 = $config['usuario']['path_files']['pdf'] . '/' . $nombre_pdf . '.pdf';
        $data_file = file_get_contents($data_file1);
        return new ViewModel(['data_pdf' => $data_file, 'nombre_pdf' => $nombre_pdf . '.pdf']);
    }

    public function pdfdwdetAction() {
        $config = $this->getConfig();
        $nombre_pdf = 'lista_instrumentos_det';
        $this->generarDocDigitalDet($nombre_pdf);
        $data_file1 = $config['usuario']['path_files']['pdf'] . '/' . $nombre_pdf . '.pdf';
        $data_file = file_get_contents($data_file1);
        return new ViewModel(['data_pdf' => $data_file, 'nombre_pdf' => $nombre_pdf . '.pdf']);
    }

    public function getUgelDreAction() {
        $request = $this->params();
        $id = (int) $request->fromQuery('id', 0);
        $where_e = [];
        $where_e['us.NOMBRE_USUARIO'] = $this->getSessionStorage()->get('user');
        $lista_empleados = $this->getAplicadorTable()->getEmpleados($where_e);
        $arr_emp = [];
        foreach ($lista_empleados as $lis) {
            array_push($arr_emp, $lis['ID_EMPLEADO']);
        }

        $params = [];
        $params['i.ID_APLICADOR'] = $arr_emp;
        $params['d.ID_DRE'] = $id;
        $lista_dre = $this->getUgelTable()->getUgel($params);

        return new JsonModel($lista_dre);
    }

    public function getPreguntasFrecuentesAction() {
        $params = [
        ];
        $username = $this->getSessionStorage()->get('user');
        $password = $this->getSessionStorage()->get('pass');

        $result = $this->curl_('versionapp/getcurrent', $params, false, ['user' => $username, 'pass' => $password]);
        $json_response = json_decode($result['data'], true);
        return new JsonModel($json_response);
    }

    public function getPreguntasAction() {
        $request = $this->params();
        $id = Util::Session()->id_instrumento;
        $id_emp = $request->fromQuery('k', '');

        $params = [
            'id_instrumento' => $id,
            'id_instrumento_empleado' => $id_emp,
        ];
        $username = $this->getSessionStorage()->get('user');
        $password = $this->getSessionStorage()->get('pass');
        $result = $this->curl_('instrumentemployee/questions', $params, true, ['user' => $username, 'pass' => $password]);
        $json_response = json_decode($result['data'], true);

        return new JsonModel($json_response);
    }

    public function preguntasAction() {
        $view = new ViewModel();
        $view->setTerminal(TRUE);
        return $view;
    }

    public function tplinfoAction() {

        $request = $this->params();
        $a = $request->fromQuery('a', '');
        $b = (int) $request->fromQuery('b', '');
        
        $id_instrumento = $this->getHashid()->encode($a);
        $param = [];
        $param['i.ID_INSTRUMENTO'] = $id_instrumento;
        $lista = $this->getInstrumentoTable()->getInfoInstrumento($param);
        $params = [
            'info' => $lista,
            't' => $b
        ];
        $viewModel = new ViewModel($params);
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }

}
