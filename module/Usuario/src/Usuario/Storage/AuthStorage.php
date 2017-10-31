<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AuthStorage
 *
 * @author sing88
 */

namespace Usuario\Storage;

use Zend\Authentication\Storage;

class AuthStorage extends Storage\Session {

    protected $data = [];
    protected $auth = false;

    public function setRememberMe($rememberMe = 0, $time = 1209600) {
        if ($rememberMe == 1) {
            $this->session->getManager()->rememberMe($time);
        }
    }

    public function forgetMe() {
        $this->session->getManager()->forgetMe();
    }

    public function add($key, $value) {
        //$this->data = $this->read();
        $this->data[$key] = $value;
    }

    public function get($key = '') {
        $this->data = $this->read();
        if ($key === '') {
            return $this->data;
        }

        if (!isset($this->data[$key])) {
            return NULL;
        }
        return $this->data[$key];
    }

    public function save() {
        //var_dump($this->data);
        $this->write($this->data);
    }

    public function isAuthenticate() {
        $this->data = $this->read();
        if (isset($this->data['auth']) === false) {
            return false;
        }

        return $this->data['auth'];
    }

    public function setAuth($flag) {
        $this->data['auth'] = $flag;
        $this->save();
    }

    public function logout() {

        $this->data['auth'] = FALSE;
        $this->save();
        $this->clear();
    }
}
