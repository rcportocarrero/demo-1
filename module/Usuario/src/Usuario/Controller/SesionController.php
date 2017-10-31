<?php

namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use BaseX\Controller\BaseController;
use Zend\Session\Container;
use Zend\View\Model\JsonModel;
use BaseX\Util\Util;

class SesionController extends BaseController {

    protected $_need_auth = false;
    protected $_miembro_comite_table = null;
    protected $_docente_table = null;
    protected $config;

    public function __construct() {
        $this->container = new Container('sesion');
    }

    public function validaAction() {
        $request = $this->params();

        $result = [];
        $result['activo'] = FALSE;
        $token_cliente = $request->fromPost('neko', '');
        $token_vfs = $request->fromPost('vfs', 0);

        if ($token_cliente !== $this->Session()->token_sesion) {
            return new JsonModel($result);
        }
        if ($this->getSessionStorage()->isAuthenticate() === null) {
            $result['activo'] = FALSE;
        }
        if ($this->getSessionStorage()->isAuthenticate() === true) {
            $result['activo'] = true;
        }

        // Validar Fechas
        if ((int) $token_vfs === 1) {
            $usuario_acl = json_decode($this->getSessionStorage()->get('users_acl'));
            date_default_timezone_set('America/Lima');
            Util::Session()->fec_ini_proceso = $usuario_acl->rows->fec_ini_proceso;
            Util::Session()->fec_fin_proceso = $usuario_acl->rows->fec_fin_proceso;
            $hoy = new \DateTime(date('Y-m-d H:i:s')); // Fecha menor (Actual)
            $fecha_inicio = Util::Session()->fec_ini_proceso;
            $fecha_fin = Util::Session()->fec_fin_proceso;

            $sw_ini = false;
            $sw_fin = false;

            $date_ini = new \DateTime($fecha_inicio);
            $date_fin = new \DateTime($fecha_fin);

            $dias_para_inicio = intval($hoy->diff($date_ini)->format('%R%a%H%I%S'));
            $dias_para_fin = intval($hoy->diff($date_fin)->format('%R%a%H%I%S'));

            if ($dias_para_inicio < 0) {
                $sw_ini = true;
            } else {
                $sw_ini = false;
            }

            if ($sw_ini === true) {
                if ($dias_para_fin > 0) {
                    $sw_fin = false;
                } else {
                    $sw_fin = true;
                }
            }
            $result['ini'] = $sw_ini;
            $result['ini_fecha'] = $date_ini->format('d/m/Y');
            $result['fin'] = $sw_fin;
            $result['fin_fecha'] = $date_fin->format('d/m/Y');
            $txt = intval($sw_ini).''. intval($sw_fin).''. intval($sw_fin).''. intval($sw_fin);
            $b64_enc = base64_encode(sha1(sha1($txt)));
//            $result['txt'] = $txt;
            $result['neko'] = $b64_enc;
        }

        return new JsonModel($result);
    }

}
