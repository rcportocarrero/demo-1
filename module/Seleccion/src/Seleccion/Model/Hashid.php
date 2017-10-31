<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Seleccion\Model;

use Hashids\Hashids;

/**
 * Description of Hashid
 *
 * @author hnker
 */
class Hashid{

    protected $hash = 'xApd57v9tg';
    
    public function encode($params) {
        
        $hashids = new Hashids('xApd57v9tg');
//        $hashids = new Hashids($hash);
        return $hashids->encode($params);
     
    }
    
    public function decode($params){
        $hashids = new Hashids('xApd57v9tg');
        $hashid_decode= $hashids->decode($params);
        return $hashid_decode[0];
    }

}
