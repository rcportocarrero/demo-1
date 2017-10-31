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
 * Description of GrupoPreguntaTable
 *
 * @author hnker
 */
class GrupoPreguntaTable extends \BaseX\Model\Table {

    protected $adapter = null;
    protected $tableGateway = null;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function listar($params = [], $order = '', $rows = 0, $page = 0)
    {

        /*
          select id_grupo_pregunta,id_instrumento,nombre,descripcion,orden from grupo_pregunta where id_instrumento=1 and estado=1 order by orden;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['gp' => 'grupo_pregunta'])
                    ->columns([
                        'id_grupo_pregunta',
                        'nombre',
                        'descripcion',
                        'orden',
            ]);
            $params['gp.ESTADO'] = 1;
            if (count($params) > 0)
            {
                $select->where($params);
            }
            $order = 'gp.orden asc';
            if ($order !== '')
            {
                $select->order($order);
            }

            if ($rows > 0)
            {
                $select->limit($rows);
            }

            $desde = ($page - 1) * $rows;
            if ($desde > 0)
            {
                $select->offset($desde);
            }

            $selectString = $sql->getSqlStringForSqlObject($select);
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
    
    public function guardar($params_sp)
    {
        try {
            $sql = "call INS_UPD_GRUPO_PREGUNTA(:p_xml,'/reg/cab/item','/reg/det/item')";
            $driver = $this->adapter->getDriver();
            $stmt = $driver->createStatement($sql);
            $stmt->prepare();
            $result = $stmt->execute($params_sp);
            $current = $result->current();
            $result->getResource()->closeCursor();
            return $current;
        } catch (\Exception $e) {
            return array('id' => -100, 'msg' => $e->getMessage());
        }
    }
    
}
