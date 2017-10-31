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
 * Description of TemporalTable
 *
 * @author hnker
 */
//class SeleccionTable extends \ZfcItp\Model\Table {
class SeleccionTable extends \BaseX\Model\Table {

    protected $adapter = null;
    protected $tableGateway = null;

    public function __construct(Adapter $adapter) {
        $this->adapter = $adapter;
    }

    public function getRol($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          select  ur.ID_ROL, r.NOMBRE, r.DESCRIPCION, p.ID_PERSONA , p.DNI, p.NOMBRES, u.* from usuario u
          left join persona p on p.ID_PERSONA=u.ID_PERSONA
          left join usuario_rol ur ON ur.ID_USUARIO=u.ID_USUARIO
          LEFT JOIN rol r ON r.ID_ROL=ur.ID_ROL
          where NOMBRE_USUARIO = 'invitado';

         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['u' => 'usuario'])
                    ->columns([
                        'ID_USUARIO',
                        'NOMBRE_USUARIO',
                    ])
                    ->join(['p' => 'persona'], 'p.ID_PERSONA=u.ID_PERSONA', ['ID_PERSONA', 'DNI', 'NOMBRES'], 'left')
                    ->join(['ur' => 'usuario_rol'], 'ur.ID_USUARIO=u.ID_USUARIO', ['ID_ROL'], 'left')
                    ->join(['r' => 'rol'], 'r.ID_ROL=ur.ID_ROL', ['NOMBRE', 'DESCRIPCION'], 'left');

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

    public function getEstrategia($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          select e.ID_TIPO_ESTRATEGIA,e.NOMBRE from tipo_estrategia e where e.ESTADO=1;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['e' => 'tipo_estrategia'])
                    ->columns([
                        'ID_TIPO_ESTRATEGIA',
                        'NOMBRE',
            ]);
            $params = [];
            $params['e.ESTADO'] = 1;
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

    public function getIntevencion($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          select e.ID_TIPO_ESTRATEGIA,e.NOMBRE from tipo_estrategia e where e.ESTADO=1;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['i' => 'tipo_intervencion'])
                    ->columns([
                        'ID_TIPO_INTERVENCION',
                        'NOMBRE',
            ]);
            $params = [];
            $params['i.ESTADO'] = 1;
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

    public function getDre($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          select d.ID_DRE,d.CODIGO,d.NOMBRE from dre d where d.estado=1
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['d' => 'dre'])
                    ->columns([
                        'ID_DRE',
                        'CODIGO',
                        'NOMBRE',
            ]);
            $params = [];
            $params['d.ESTADO'] = 1;
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

    public function getUgel($params = [], $order = '', $rows = 0, $page = 0) {

        /*
          select u.ID_UGEL,u.CODIGO,u.NOMBRE,u.ID_DRE from ugel u where u.estado=1;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['u' => 'ugel'])
                    ->columns([
                        'ID_UGEL',
                        'CODIGO',
                        'NOMBRE',
                        'ID_DRE',
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

    public function getAllInstrumentos($where = []) {

        /*
          SELECT i.ID_INSTRUMENTO,i.NOMBRE,i.FECHA_INICIO,i.FECHA_FIN_TMP,i.FECHA_FIN,te.ID_TIPO_ESTRATEGIA,ti.ID_TIPO_INTERVENCION,tins.ID_TIPO_INSTRUMENTO,ta.ID_TIPO_AMBITO ,tm.ID_TIPO_MUESTRA
          FROM instrumento AS i
          INNER JOIN tipo_estrategia AS te ON (i.ID_TIPO_ESTRATEGIA = te.ID_TIPO_ESTRATEGIA AND te.ESTADO = 1)
          INNER jOIN tipo_intervencion AS ti ON (i.ID_TIPO_INTERVENCION = ti.ID_TIPO_INTERVENCION AND ti.ESTADO = 1)
          INNER JOIN tipo_instrumento AS tins ON (i.ID_TIPO_INSTRUMENTO = tins.ID_TIPO_INSTRUMENTO AND tins.ESTADO = 1)
          INNER JOIN tipo_ambito AS ta ON (i.ID_TIPO_AMBITO = ta.ID_TIPO_AMBITO AND ta.ESTADO = 1)
          INNER JOIN tipo_muestra AS tm ON (i.ID_TIPO_MUESTRA = tm.ID_TIPO_MUESTRA AND tm.ESTADO = 1);
        */
        $sql = 'SELECT IF(curdate() > i.FECHA_FIN,1,0) as ESTADO_NR,tin.DESCRIPCION_INFORMANTE, te.NOMBRE AS DES_ESTRATEGIA,ti.NOMBRE AS DES_INTERVENCION,tins.NOMBRE AS DES_TIP_INSTRUMENTO,ta.NOMBRE AS DES_AMBITO,tm.NOMBRE AS DES_MUESTRA,
            i.ID_INSTRUMENTO,i.NOMBRE,i.FECHA_INICIO,i.FECHA_FIN_TMP,i.FECHA_FIN,te.ID_TIPO_ESTRATEGIA,ti.ID_TIPO_INTERVENCION,tins.ID_TIPO_INSTRUMENTO,ta.ID_TIPO_AMBITO ,tm.ID_TIPO_MUESTRA
          FROM instrumento AS i
          INNER JOIN tipo_estrategia AS te ON (i.ID_TIPO_ESTRATEGIA = te.ID_TIPO_ESTRATEGIA AND te.ESTADO = 1 '.$where['estrategia'].')
          INNER jOIN tipo_intervencion AS ti ON (i.ID_TIPO_INTERVENCION = ti.ID_TIPO_INTERVENCION AND ti.ESTADO = 1 '.$where['intervencion'].')
          INNER JOIN tipo_instrumento AS tins ON (i.ID_TIPO_INSTRUMENTO = tins.ID_TIPO_INSTRUMENTO AND tins.ESTADO = 1 '.$where['tipo_instrumento'].')
          INNER JOIN tipo_ambito AS ta ON (i.ID_TIPO_AMBITO = ta.ID_TIPO_AMBITO AND ta.ESTADO = 1 '.$where['ambito'].')
          INNER JOIN tipo_muestra AS tm ON (i.ID_TIPO_MUESTRA = tm.ID_TIPO_MUESTRA AND tm.ESTADO = 1 '.$where['muestra'].')
          inner join tipo_informante tin on (i.ID_TIPO_INFORMANTE= tin.ID_TIPO_INFORMANTE and tin.ESTADO=1)';
        
//        echo $sql;
        $resultset = $this->adapter->query($sql, []);
        $data = $resultset->toArray();
        return $data;
    }
    public function getInstrumentoXaplicador($where = []) {

        /*
          SELECT IF(curdate() > i.FECHA_FIN,1,0) as ESTADO_NR,i.ID_INSTRUMENTO,i.NOMBRE,i.FECHA_INICIO,i.FECHA_FIN_TMP,i.FECHA_FIN,te.ID_TIPO_ESTRATEGIA,ti.ID_TIPO_INTERVENCION,tins.ID_TIPO_INSTRUMENTO,ta.ID_TIPO_AMBITO ,tm.ID_TIPO_MUESTRA
          FROM instrumento AS i
          INNER JOIN ( SELECT DISTINCT(ID_INSTRUMENTO) FROM instrumento_empleado WHERE ID_APLICADOR  IN (SELECT emp.ID_EMPLEADO FROM usuario AS us INNER JOIN empleado AS emp ON (us.ID_PERSONA = emp.ID_PERSONA AND emp.ESTADO = 1 AND us.NOMBRE_USUARIO='invitado2')) AND ESTADO != 0 ) AS ie ON (i.ID_INSTRUMENTO = ie.ID_INSTRUMENTO AND i.ESTADO = 1 AND i.FECHA_INICIO <= curdate() and curdate() <= i.FECHA_FIN)
          INNER JOIN tipo_estrategia AS te ON (i.ID_TIPO_ESTRATEGIA = te.ID_TIPO_ESTRATEGIA AND te.ESTADO = 1)
          INNER jOIN tipo_intervencion AS ti ON (i.ID_TIPO_INTERVENCION = ti.ID_TIPO_INTERVENCION AND ti.ESTADO = 1)
          INNER JOIN tipo_instrumento AS tins ON (i.ID_TIPO_INSTRUMENTO = tins.ID_TIPO_INSTRUMENTO AND tins.ESTADO = 1)
          INNER JOIN tipo_ambito AS ta ON (i.ID_TIPO_AMBITO = ta.ID_TIPO_AMBITO AND ta.ESTADO = 1)
          INNER JOIN tipo_muestra AS tm ON (i.ID_TIPO_MUESTRA = tm.ID_TIPO_MUESTRA AND tm.ESTADO = 1);
          
         * VENCIDO 1 - EN PLAZO 0
         *          */
        $sql = 'SELECT IF(curdate() > i.FECHA_FIN,1,0) as ESTADO_NR,tin.DESCRIPCION_INFORMANTE, te.NOMBRE AS DES_ESTRATEGIA,ti.NOMBRE AS DES_INTERVENCION,tins.NOMBRE AS DES_TIP_INSTRUMENTO,ta.NOMBRE AS DES_AMBITO,tm.NOMBRE AS DES_MUESTRA,
            i.ID_INSTRUMENTO,i.NOMBRE,i.FECHA_INICIO,i.FECHA_FIN_TMP,i.FECHA_FIN,te.ID_TIPO_ESTRATEGIA,ti.ID_TIPO_INTERVENCION,tins.ID_TIPO_INSTRUMENTO,ta.ID_TIPO_AMBITO ,tm.ID_TIPO_MUESTRA
          FROM instrumento AS i
          INNER JOIN ( SELECT DISTINCT(ID_INSTRUMENTO) FROM instrumento_empleado WHERE ID_APLICADOR  IN ('.$where['aplicador'].') AND ESTADO != 0 ) AS ie ON (i.ID_INSTRUMENTO = ie.ID_INSTRUMENTO AND i.ESTADO = 1 AND i.FECHA_INICIO <= curdate())
          INNER JOIN tipo_estrategia AS te ON (i.ID_TIPO_ESTRATEGIA = te.ID_TIPO_ESTRATEGIA AND te.ESTADO = 1 '.$where['estrategia'].')
          INNER jOIN tipo_intervencion AS ti ON (i.ID_TIPO_INTERVENCION = ti.ID_TIPO_INTERVENCION AND ti.ESTADO = 1 '.$where['intervencion'].')
          INNER JOIN tipo_instrumento AS tins ON (i.ID_TIPO_INSTRUMENTO = tins.ID_TIPO_INSTRUMENTO AND tins.ESTADO = 1 '.$where['tipo_instrumento'].')
          INNER JOIN tipo_ambito AS ta ON (i.ID_TIPO_AMBITO = ta.ID_TIPO_AMBITO AND ta.ESTADO = 1 '.$where['ambito'].')
          INNER JOIN tipo_muestra AS tm ON (i.ID_TIPO_MUESTRA = tm.ID_TIPO_MUESTRA AND tm.ESTADO = 1 '.$where['muestra'].')
          inner join tipo_informante tin on (i.ID_TIPO_INFORMANTE= tin.ID_TIPO_INFORMANTE and tin.ESTADO=1)';
        
//        echo $sql;
        $resultset = $this->adapter->query($sql, []);
        $data = $resultset->toArray();
        return $data;
    }
    
    public function getInstrumentoEmpleadoXaplicador($where = []){
//        SELECT ie.SINCRONIZADO,ie.INSTRUMENTO_COMPLETADO
// FROM instrumento_empleado AS ie
//INNER JOIN empleado AS em ON (ie.ID_INFORMANTE = em.ID_EMPLEADO AND ie.ID_APLICADOR IN (SELECT emp.ID_EMPLEADO FROM usuario AS us INNER JOIN empleado AS emp ON (us.ID_PERSONA = emp.ID_PERSONA AND emp.ESTADO = 1 AND us.NOMBRE_USUARIO='invitado2')) AND ie.ID_INSTRUMENTO = 1 AND ie.ESTADO != 0 AND em.ESTADO = 1) 
// INNER JOIN persona AS pe  ON (em.ID_PERSONA = pe.ID_PERSONA)
// INNER JOIN tipo_cargo AS tcar  ON (em.ID_TIPO_CARGO = tcar.ID_TIPO_CARGO)
// INNER JOIN dre AS dre  ON (em.ID_DRE = dre.ID_DRE and dre.ID_DRE=16)
// LEFT JOIN ugel AS ugel  ON (em.ID_UGEL = ugel.ID_UGEL)
// LEFT JOIN institucion_educativa AS inse  ON (em.ID_INSTITUCION_EDUCATIVA = inse.ID_INSTITUCION_EDUCATIVA);
        $sql = ' SELECT ie.SINCRONIZADO,ie.INSTRUMENTO_COMPLETADO
                FROM instrumento_empleado AS ie
                INNER JOIN empleado AS em ON (ie.ID_INFORMANTE = em.ID_EMPLEADO AND ie.ID_APLICADOR IN (SELECT emp.ID_EMPLEADO FROM usuario AS us INNER JOIN empleado AS emp ON (us.ID_PERSONA = emp.ID_PERSONA AND emp.ESTADO = 1 AND us.NOMBRE_USUARIO="'.$where['usuario'].'")) AND ie.ID_INSTRUMENTO ="'.$where['ID_INSTRUMENTO'].'" AND ie.ESTADO != 0 AND em.ESTADO = 1) 
                INNER JOIN persona AS pe  ON (em.ID_PERSONA = pe.ID_PERSONA)
                INNER JOIN tipo_cargo AS tcar  ON (em.ID_TIPO_CARGO = tcar.ID_TIPO_CARGO)
                INNER JOIN dre AS dre  ON (em.ID_DRE = dre.ID_DRE)
                LEFT JOIN ugel AS ugel  ON (em.ID_UGEL = ugel.ID_UGEL)
                LEFT JOIN institucion_educativa AS inse  ON (em.ID_INSTITUCION_EDUCATIVA = inse.ID_INSTITUCION_EDUCATIVA);';
//        echo $sql;
        $resultset = $this->adapter->query($sql, []);
        $data = $resultset->toArray();
        return $data;
    }

    public function getInstrumentos($params = [], $like = '', $order = '', $rows = 0, $page = 0) {

        /*
          select ins.ID_INSTRUMENTO,ins.NOMBRE,ins.ID_TIPO_ESTRATEGIA,ins.ID_TIPO_INTERVENCION from instrumento ins
          inner join instrumento_empleado ine on ins.ID_INSTRUMENTO=ine.ID_INSTRUMENTO
          inner join empleado emp on ine.ID_APLICADOR=emp.ID_EMPLEADO
          inner join institucion_educativa ie on emp.ID_INSTITUCION_EDUCATIVA=ie.ID_INSTITUCION_EDUCATIVA
          where
          ins.ESTADO=1
          and ine.ESTADO=1
          and ins.ID_TIPO_ESTRATEGIA=1
          and ins.ID_TIPO_INTERVENCION=1
          and emp.ESTADO=1
          and emp.ID_DRE=3
          and emp.ID_UGEL=38
          and ie.NOMBRE like '%SANTA%'
          group by ins.ID_INSTRUMENTO;
         */
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['ins' => 'instrumento'])
                    ->columns([
                        'ID_INSTRUMENTO',
                        'NOMBRE',
                        'ID_TIPO_ESTRATEGIA',
                        'ID_TIPO_INTERVENCION',
                    ])
                    ->join(['ine' => 'instrumento_empleado'], 'ins.ID_INSTRUMENTO=ine.ID_INSTRUMENTO', [])
                    ->join(['emp' => 'empleado'], 'ine.ID_APLICADOR=emp.ID_EMPLEADO', [])
                    ->join(['ie' => 'institucion_educativa'], 'emp.ID_INSTITUCION_EDUCATIVA=ie.ID_INSTITUCION_EDUCATIVA', []);

            if (count($params) > 0) {
                $select->where($params);
            }
            if ($like !== '') {
                $select->where->like('ie.NOMBRE', '%' . $like . '%');
            }

            $select->group('ins.ID_INSTRUMENTO');

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
