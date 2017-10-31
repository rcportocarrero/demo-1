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
 * Description of InstrumentoTable
 *
 * @author hnker
 */
class InstrumentoTable extends \BaseX\Model\Table {

    protected $adapter = null;
    protected $tableGateway = null;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }
    
     public function guardar($params_sp)
    {
        try {
            $sql = "call INS_UPD_INSTRUMENTO(:p_xml,'/reg/cab/item')";
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
    
//    public function guardar($datos) {
//
//        $sql = "call INS_UPD_INSTRUMENTO(:p_xml,'/reg/cab/item')";
//
//        try {
//            $resultset = $this->adapter->query($sql, $datos);
//            $data = $resultset->toArray();
//
//            return $data;
//        } catch (\Exception $ex) {
//            return [
//                'data' => $ex->getMessage(),
//            ];
//        }
//    }

    public function getUgel($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          SELECT ugel.ID_UGEL,ugel.NOMBRE
          FROM instrumento_empleado AS ie
          INNER JOIN empleado AS em ON (ie.ID_INFORMANTE = em.ID_EMPLEADO AND ie.ID_APLICADOR IN (SELECT emp.ID_EMPLEADO FROM usuario AS us INNER JOIN empleado AS emp ON (us.ID_PERSONA = emp.ID_PERSONA AND emp.ESTADO = 1 AND us.NOMBRE_USUARIO='invitado2'))  AND ie.ID_INSTRUMENTO = 1 AND ie.ESTADO = 1 AND em.ESTADO = 1)
          INNER JOIN dre AS dre  ON (em.ID_DRE = dre.ID_DRE)
          LEFT JOIN ugel AS ugel  ON (em.ID_UGEL = ugel.ID_UGEL)
          GROUP BY ugel.ID_UGEL;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['i' => 'instrumento_empleado'])
                    ->columns([])
                    ->join(['e' => 'empleado'], 'i.ID_INFORMANTE = e.ID_EMPLEADO', [])
                    ->join(['d' => 'dre'], 'e.ID_DRE = d.ID_DRE', [])
                    ->join(['u' => 'ugel'], 'e.ID_UGEL = u.ID_UGEL', ['ID_UGEL', 'NOMBRE']);
            $params['i.ID_INSTRUMENTO'] = 1;
            $params['i.ESTADO'] = 1;
            $params['e.ESTADO'] = 1;
            $params['i.ID_APLICADOR'] = array();
            if (count($params) > 0) {
                $select->where($params);
            }
            $select->group('u.ID_UGEL');
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

    public function getInfoInstrumento($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          SELECT
          te.NOMBRE as des_estrategia, ti.NOMBRE as des_intervencion, tins.NOMBRE as des_tipo_instrumento,ta.NOMBRE as des_ambito,

          FROM instrumento AS i
          INNER JOIN tipo_estrategia AS te ON (i.ID_TIPO_ESTRATEGIA = te.ID_TIPO_ESTRATEGIA AND te.ESTADO = 1)
          INNER jOIN tipo_intervencion AS ti ON (i.ID_TIPO_INTERVENCION = ti.ID_TIPO_INTERVENCION AND ti.ESTADO = 1)
          INNER JOIN tipo_instrumento AS tins ON (i.ID_TIPO_INSTRUMENTO = tins.ID_TIPO_INSTRUMENTO AND tins.ESTADO = 1)
          INNER JOIN tipo_ambito AS ta ON (i.ID_TIPO_AMBITO = ta.ID_TIPO_AMBITO AND ta.ESTADO = 1)
          INNER JOIN tipo_muestra AS tm ON (i.ID_TIPO_MUESTRA = tm.ID_TIPO_MUESTRA AND tm.ESTADO = 1)
          where i.ID_INSTRUMENTO=1;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['i' => 'instrumento'])
                    ->columns(['des_instrumento' => 'NOMBRE', 'FECHA_INICIO', 'FECHA_FIN'])
                    ->join(['te' => 'tipo_estrategia'], 'i.ID_TIPO_ESTRATEGIA = te.ID_TIPO_ESTRATEGIA', ['des_estrategia' => 'NOMBRE'])
                    ->join(['ti' => 'tipo_intervencion'], 'i.ID_TIPO_INTERVENCION = ti.ID_TIPO_INTERVENCION', ['des_intervencion' => 'NOMBRE'])
                    ->join(['tins' => 'tipo_instrumento'], 'i.ID_TIPO_INSTRUMENTO = tins.ID_TIPO_INSTRUMENTO', ['des_tipo_instrumento' => 'NOMBRE'])
                    ->join(['ta' => 'tipo_ambito'], 'i.ID_TIPO_AMBITO = ta.ID_TIPO_AMBITO', ['des_ambito' => 'NOMBRE'])
                    ->join(['tm' => 'tipo_muestra'], 'i.ID_TIPO_MUESTRA = tm.ID_TIPO_MUESTRA', ['des_muestra' => 'NOMBRE'])
                    ->join(['tin' => 'tipo_informante'], 'i.ID_TIPO_INFORMANTE = tin.ID_TIPO_INFORMANTE', ['DESCRIPCION_INFORMANTE']);
            $params['tm.ESTADO'] = 1;
            $params['ta.ESTADO'] = 1;
            $params['ti.ESTADO'] = 1;
            $params['te.ESTADO'] = 1;
            $params['tin.ESTADO'] = 1;

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

    public function getInstrumento($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          select ID_INSTRUMENTO,NOMBRE from instrumento where id_instrumento=1;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);
            $select = $sql->select();
            $select->from(['i' => 'instrumento'])
                    ->columns([
                        'ID_INSTRUMENTO',
                        'NOMBRE',
                        'DESCRIPCION_INSTRUMENTO',
                        'FECHA_INICIO' => new \Zend\Db\Sql\Expression('DATE_FORMAT(FECHA_INICIO, "%d-%m-%Y")'),
                        'FECHA_FIN' => new \Zend\Db\Sql\Expression('DATE_FORMAT(FECHA_FIN, "%d-%m-%Y")'),
                        'ESTADO_NR' => new \Zend\Db\Sql\Expression('IF(curdate() > FECHA_FIN ,1,0)'),
                        'ID_TIPO_ESTRATEGIA',
                        'ID_TIPO_INTERVENCION',
                        'ID_TIPO_INSTRUMENTO',
                        'ID_TIPO_AMBITO',
                        'ID_TIPO_MUESTRA',
                        'ID_TIPO_INFORMANTE',
            ]);

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

    public function getInstrumentoEmpleado($params = [], $like = [], $order = '', $rows = 0, $page = 0) {

        /*
          SELECT  ie.ID_INSTRUMENTO,ie.ID_INSTRUMENTO_EMPLEADO,ie.SINCRONIZADO,ie.INSTRUMENTO_COMPLETADO,inse.NOMBRE as ie,pe.NOMBRES,pe.APELLIDO_PATERNO,pe.APELLIDO_MATERNO
          FROM instrumento_empleado AS ie
          INNER JOIN empleado AS em ON (ie.ID_INFORMANTE = em.ID_EMPLEADO AND ie.ID_APLICADOR IN (SELECT emp.ID_EMPLEADO FROM usuario AS us INNER JOIN empleado AS emp ON (us.ID_PERSONA = emp.ID_PERSONA AND emp.ESTADO = 1 AND us.NOMBRE_USUARIO='invitado2')) AND ie.ID_INSTRUMENTO = 1 AND ie.ESTADO = 1 AND em.ESTADO = 1)
          INNER JOIN persona AS pe  ON (em.ID_PERSONA = pe.ID_PERSONA)
          INNER JOIN tipo_cargo AS tcar  ON (em.ID_TIPO_CARGO = tcar.ID_TIPO_CARGO)
          INNER JOIN dre AS dre  ON (em.ID_DRE = dre.ID_DRE and dre.ID_DRE=16)
          LEFT JOIN ugel AS ugel  ON (em.ID_UGEL = ugel.ID_UGEL)
          LEFT JOIN institucion_educativa AS inse  ON (em.ID_INSTITUCION_EDUCATIVA = inse.ID_INSTITUCION_EDUCATIVA);
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['ie' => 'instrumento_empleado'])
                    ->columns(['SINCRONIZADO', 'INSTRUMENTO_COMPLETADO', 'ID_INSTRUMENTO', 'ID_INSTRUMENTO_EMPLEADO', 'ESTADO'])
                    ->join(['em' => 'empleado'], 'ie.ID_INFORMANTE = em.ID_EMPLEADO', [])
                    ->join(['pe' => 'persona'], 'em.ID_PERSONA = pe.ID_PERSONA', ['NOMBRES', 'APELLIDO_PATERNO', 'APELLIDO_MATERNO', 'DNI'])
                    ->join(['tcar' => 'tipo_cargo'], 'em.ID_TIPO_CARGO = tcar.ID_TIPO_CARGO', [])
                    ->join(['dre' => 'dre'], 'em.ID_DRE = dre.ID_DRE', ['ID_DRE', 'NOM_DRE' => 'NOMBRE'])
                    ->join(['ugel' => 'ugel'], 'em.ID_UGEL = ugel.ID_UGEL', ['ID_UGEL', 'NOM_UGEL' => 'NOMBRE'])
                    ->join(['inse' => 'institucion_educativa'], 'em.ID_INSTITUCION_EDUCATIVA = inse.ID_INSTITUCION_EDUCATIVA', ['NOMBRE', 'CODIGO']);
            $params['ie.ESTADO'] = [1, 2, 3];
            $params['em.ESTADO'] = 1;


            if (count($params) > 0) {
                $select->where($params);
            }

            if ($like['iiee'] !== '') {
                $select->where->like('inse.NOMBRE', '%' . $like['iiee'] . '%');
            }

            if ($like['nombres'] !== '') {
                $select->where->like(new \Zend\Db\Sql\Expression("CONCAT_WS(' ', pe.NOMBRES, pe.APELLIDO_PATERNO,pe.APELLIDO_MATERNO)"), '%' . $like['nombres'] . '%');
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
//            echo  $selectString;
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

    public function getPreguntasRespuestas($where = []) {

        /*
          SELECT COUNT(*) AS TOTAL_PREGUNTAS, (SELECT COUNT(*) FROM respuesta_pregunta WHERE ID_INSTRUMENTO_EMPLEADO = 38 AND ESTADO = 1) AS TOTAL_AVANCE
          FROM instrumento_empleado AS iem
          INNER JOIN instrumento AS ins ON (iem.ID_INSTRUMENTO = ins.ID_INSTRUMENTO AND iem.ID_INSTRUMENTO_EMPLEADO = 38 AND iem.ESTADO != 0 )
          INNER JOIN grupo_pregunta AS gp ON (ins.ID_INSTRUMENTO = gp.ID_INSTRUMENTO AND gp.ESTADO = 1)
          INNER JOIN pregunta AS pre ON (gp.ID_GRUPO_PREGUNTA = pre.ID_GRUPO_PREGUNTA AND pre.ESTADO = 1)
         */
        $sql = 'SELECT COUNT(*) AS TOTAL_PREGUNTAS, (SELECT COUNT(*) FROM respuesta_pregunta WHERE ID_INSTRUMENTO_EMPLEADO = ' . $where['ID_INSTRUMENTO_EMPLEADO'] . ' AND ESTADO = 1) AS TOTAL_AVANCE
          FROM instrumento_empleado AS iem
          INNER JOIN instrumento AS ins ON (iem.ID_INSTRUMENTO = ins.ID_INSTRUMENTO AND iem.ID_INSTRUMENTO_EMPLEADO = ' . $where['ID_INSTRUMENTO_EMPLEADO'] . ' AND iem.ESTADO != 0 ) 
          INNER JOIN grupo_pregunta AS gp ON (ins.ID_INSTRUMENTO = gp.ID_INSTRUMENTO AND gp.ESTADO = 1) 
		  INNER JOIN pregunta AS pre ON (gp.ID_GRUPO_PREGUNTA = pre.ID_GRUPO_PREGUNTA AND pre.ESTADO = 1)';

        $resultset = $this->adapter->query($sql, []);
        $data = $resultset->toArray();
        return $data;
    }

}
