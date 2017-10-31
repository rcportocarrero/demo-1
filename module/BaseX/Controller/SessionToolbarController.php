<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace BaseX\Controller;

/**
 * Description of ProfilerXController
 *
 * @author CSING
 */
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use BaseX\Collector\SessionCollector;
use Zend\View\Model\JsonModel;

class SessionToolbarController extends AbstractActionController {

    public function holaAction() {

        phpinfo();

        return false;
    }

    public function debugAction() {

//        SessionCollector::g
//        echo \Zend\Debug\Debug::dump(SessionCollector::getSessionData());
//        $html;
        $html = \Zend\Debug\Debug::dump(SessionCollector::getSessionData(), null, false);
        $success = [
            'html' => $html
        ];
        return new JsonModel([
            'success' => $success,
        ]);
        return false;
    }

}
