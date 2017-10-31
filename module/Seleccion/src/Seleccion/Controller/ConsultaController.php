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

/*
 * Description of ConsultaController
 *
 * @author hnkr
 */
class ConsultaController extends \BaseX\Controller\BaseController {

    protected $needAuthentication = TRUE;
    protected $enable_layout = false;
  
     public function reportesAction() {

        $viewModel = new ViewModel();
        $viewModel->setTerminal(TRUE);
        return $viewModel;
    }



}
