<?php

namespace Inicio\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends \BaseX\Controller\BaseController {

    protected $_need_auth = true;

    public function indexAction() {
        $config = $this->getConfig();
        $app_config = $config['app'];
        $usuario_dashboard = $config['usuario']['dashboard'];
        $usuario_cambio = $config['usuario']['num_caracteres'];        
        $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
        $username = $this->getSessionStorage()->get('user');
        $token_sesion = sha1($username . time());
        $this->Session()->token_sesion = $token_sesion;
        
        $this->layout()->apps_config_caracteres = $usuario_cambio;
        $this->layout()->apps_var = $app_config;
        $this->layout()->users_acl = $users_acl;
        $this->layout()->dashboard_config = $usuario_dashboard;
        $this->layout()->cambio_config = $usuario_cambio;        
        $this->layout()->token_sesion = $this->Session()->token_sesion;

        $params_view = [
            'apps_var'          => $app_config,
            'users_acl'         => $users_acl,
            'dashboard_config'  => $usuario_dashboard,
        ];

        $view = new ViewModel($params_view);
        return $view;
    }

    public function presentacionAction() {
        $config = $this->getConfig();
        $app_config = $config['app'];
        
        $params_view = [
            'apps_var' => $app_config        
        ];
        $view = new ViewModel($params_view);
        $view->setTerminal(true);
        return $view;
    }
    
    public function prociniAction() {
        $config = $this->getConfig();
        $app_config = $config['app']; 
        $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
        $fec_ini_proc = $users_acl->rows->fec_ini_proceso;
        $fec_fin_proc = $users_acl->rows->fec_fin_proceso;
        
        $params_view = [
            'apps_var' => $app_config,
            'fec_ini_proc' => $fec_ini_proc,
            'fec_fin_proc' => $fec_fin_proc,
        ];
        
        $view = new ViewModel($params_view);
        $view->setTerminal(true);
        return $view;
    }
    
    public function procfinAction() {
        $config = $this->getConfig();
        $app_config = $config['app']; 
        $users_acl = json_decode($this->getSessionStorage()->get('users_acl'));
        $fec_fin_proc = $users_acl->rows->fec_fin_proceso;
        
        $params_view = [
            'apps_var' => $app_config,
            'fec_fin_proc' => $fec_fin_proc,
        ];
        
        $view = new ViewModel($params_view);
        $view->setTerminal(true);
        return $view;
    }
    
}
