<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Seleccion\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;

/**
 * Description of AplicadorTable
 *
 * @author hnker
 */
class AplicadorTable extends \BaseX\Model\Table {

    protected $adapter = null;
    protected $tableGateway = null;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getEmpleados($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          SELECT emp.ID_EMPLEADO FROM usuario AS us 
         INNER JOIN empleado AS emp ON (us.ID_PERSONA = emp.ID_PERSONA AND emp.ESTADO = 1 AND us.NOMBRE_USUARIO='invitado2')
          
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
           $select->from(['us' => 'usuario'])
                    ->columns([])
                    ->join(['emp' => 'empleado'], 'us.ID_PERSONA = emp.ID_PERSONA', ['ID_EMPLEADO']);
            $params['emp.ESTADO'] = 1;
//            $params['us.NOMBRE_USUARIO'] = 1;
            if (count($params) > 0) {
                $select->where($params);
            }
            if ($order !== '') {
                $select->order($order);
            }

            if ($rows > 0) {
                $select->limit($rows);
            }

            $desde = ($page - 1) * $rows;
            if ($desde > 0) {
                $select->offset($desde);
            }

            $selectString = $sql->getSqlStringForSqlObject($select);
//            echo $selectString;
            $resultSet = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            return $resultSet->toArray();
        } catch (\Exception $ex) {
            return [
                'id' => 0,
                'codigo' => -100,
                'mensaje' => $ex->getMessage(),
            ];
        }
    }

}
