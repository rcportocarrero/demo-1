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
 * Description of DreTable
 *
 * @author hnker
 */
class DreTable extends \BaseX\Model\Table {

    protected $adapter = null;
    protected $tableGateway = null;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getDre($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          SELECT dre.ID_DRE,dre.NOMBRE
          FROM instrumento_empleado AS ie
          INNER JOIN empleado AS em ON (ie.ID_INFORMANTE = em.ID_EMPLEADO AND ie.ID_APLICADOR IN (SELECT emp.ID_EMPLEADO FROM usuario AS us INNER JOIN empleado AS emp ON (us.ID_PERSONA = emp.ID_PERSONA AND emp.ESTADO = 1 AND us.NOMBRE_USUARIO='invitado2'))  AND ie.ID_INSTRUMENTO = 1 AND ie.ESTADO = 1 AND em.ESTADO = 1)
          INNER JOIN dre AS dre  ON (em.ID_DRE = dre.ID_DRE)
          GROUP BY dre.ID_DRE;
          
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
           $select->from(['i' => 'instrumento_empleado'])
                    ->columns([])
                    ->join(['e' => 'empleado'], 'i.ID_INFORMANTE = e.ID_EMPLEADO', [])
                    ->join(['d' => 'dre'], 'e.ID_DRE = d.ID_DRE', ['ID_DRE','NOMBRE']);
//            $params['i.ID_INSTRUMENTO'] = 1; //Para prueba
            $params['i.ESTADO'] = [1,2,3];
            $params['e.ESTADO'] = 1;
            if (count($params) > 0) {
                $select->where($params);
            }
             $select->group('d.ID_DRE');
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
