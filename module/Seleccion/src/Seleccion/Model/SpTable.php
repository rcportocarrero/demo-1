<?php

namespace Seleccion\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;

class SpTable extends \BaseX\Model\Table {

    protected $_name = '';
    protected $_primary = '';
    protected $_active_column_name = '';

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function listar($params = [], $order = '', $rows = 0, $page = 0)
    {
        // Esta forma es para llamar a los Select simples, como tablas o tablas con FK usando Joins
     
    }

}
