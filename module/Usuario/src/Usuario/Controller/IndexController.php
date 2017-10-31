<?php

namespace Usuario\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BaseX\Controller\BaseController;
use Zend\View\Model\JsonModel;

class IndexController extends BaseController {

    protected $_proyecto_table = null;

    public function getProyectoTable()
    {
        if (!$this->_proyecto_table)
        {
            $sm = $this->getServiceLocator();
            $this->_proyecto_table = $sm->get('Application\Model\ProyectoTable');
        }
        return $this->_proyecto_table;
    }

    public function indexAction()
    {
        
    }

}
