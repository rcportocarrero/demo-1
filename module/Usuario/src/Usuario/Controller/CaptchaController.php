<?php

namespace Usuario\Controller;

use Zend\View\Model\ViewModel;
use Zend\Session\Container;

class CaptchaController extends \BaseX\Controller\BaseController {

    protected $needAuthentication = FALSE;
    protected $container;

    public function __construct() {
        $this->container = new Container('sesion');
    }
    
    public function genRandomText() {
        $config = $this->getConfig();
        $diccionario = $config['usuario']['num_caracteres']['captcha']['diccionario'];
        $tamano = $config['usuario']['num_caracteres']['captcha']['tamano_codigo'];
        $tam_diccionario = strlen($diccionario);
        $valores = str_split($diccionario, 1);
        $a_cadena_aleatorio = array();
        //Generamos el texto aleatorio
        for ($i = 0; $i < $tamano; $i++) {
            $a_cadena_aleatorio[] = $valores[rand(0, $tam_diccionario - 1)];
        }
        $cadena_aleatorio = strtoupper(implode('', $a_cadena_aleatorio));
        return $cadena_aleatorio;
    }

    public function genCaptchaImagen($text) {
        $root_path = getcwd();
        $fonts_path = $root_path . '/fonts/';
        $image = imagecreatefrompng($fonts_path . 'button.png');
        $font = 'Anorexia.ttf'; // font style 
        $color = imagecolorallocate($image, 51, 122, 183); // color
        $rotate = rand(-5, 5);
        imagettftext($image, 20, $rotate, 18, 30, $color, $fonts_path . $font, strtoupper($text));
        return $image;
    }

    public function registrocaptchaAction() {

        $cadena_aleatorio = $this->genRandomText();
        $image = $this->genCaptchaImagen($cadena_aleatorio);
        
        $this->container->registro_captcha = strtoupper($cadena_aleatorio);
        header("Content-type: image/png");
        imagepng($image);
        exit;
    }

    public function clavecaptchaAction() {

        $cadena_aleatorio = $this->genRandomText();
        $image = $this->genCaptchaImagen($cadena_aleatorio);

        $this->container->clave_captcha = strtoupper($cadena_aleatorio);
        header("Content-type: image/png");
        imagepng($image);
        exit;
    }
    
    public function logincaptchaAction() {
        $cadena_aleatorio = $this->genRandomText();
        $image = $this->genCaptchaImagen($cadena_aleatorio);
        $this->container->login_captcha = strtoupper($cadena_aleatorio);
        header("Content-type: image/png");
        imagepng($image);
        exit;
    }

}
