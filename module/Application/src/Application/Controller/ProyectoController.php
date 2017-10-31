<?php

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;

class ProyectoController extends \BaseX\Controller\BaseController {

    protected $needAuthentication = false;
    protected $_proyecto_table = null;

    public function getProyectoTable() {
        if (!$this->_proyecto_table) {
            $sm = $this->getServiceLocator();
            $this->_proyecto_table = $sm->get('Application\Model\ProyectoTable');
        }
        return $this->_proyecto_table;
    }

    public function indexAction() {
        
    }

    public function listarAction() {

        $data = $this->getProyectoTable()->fetchAll(['activo' => 1]);
        return new JsonModel($data);
    }

    public function eliminarAction() {
        $request = $this->params();
        $id = intval($request->fromPost('id', 0));
        $data = $this->getProyectoTable()->eliminar($id);
        return new JsonModel($data);
    }

    public function guardarAction() {
        $request = $this->params();
        $id = intval($request->fromPost('id', 0));
        $nombre = $request->fromPost('nombre', '');
        $autor = $request->fromPost('autor', '');
        $email = $request->fromPost('email', '');
//        $id = intval($request->from('id', 0));
        $data = [
            'nombre' => $nombre,
            'autor' => $autor,
            'email' => $email,
            'activo' => 1
        ];

        if ($id === 0) {
            $data = $this->getProyectoTable()->insertar($data);
        }

        return new JsonModel($data);

        return new JsonModel($_POST);
//        return new JsonModel($_REQUEST);
        $data = $this->getProyectoTable()->eliminar($id);
        return new JsonModel($data);
    }

}
