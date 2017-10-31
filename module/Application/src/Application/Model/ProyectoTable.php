<?php

namespace Application\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;

/**
 * Description of ProyectoTable
 *
 * @author Carlos Sing Ramos
 */
class ProyectoTable extends \BaseX\Model\Table {

    protected $_name = 'proyecto';
    protected $_primary = 'id';
    protected $_columns = [
        'id',
        'nombre',
        'autor',
        'email',
        'activo',
    ];
    protected $_estado = 1;
    protected $_active_column_name = 'activo';

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function listarAction() {
        return $this->fetchAll();
    }

    public function insertar($data) {
        return $this->bx_insert($data);
    }

    public function editar($id, $data) {
        return $this->bx_update($id, $data);
    }

    public function eliminar($id) {
        return $this->bx_delete($id);
    }

}
