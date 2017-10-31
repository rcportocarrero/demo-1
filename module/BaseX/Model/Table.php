<?php

/**
 * Description of Table
 *
 * @author Carlos Sing Ramos
 */

namespace BaseX\Model;

use Zend\Db\TableGateway\AbstractTableGateway;
use Zend\Db\Sql\Sql;

class Table extends AbstractTableGateway {

    protected $_name = '';
    protected $schema;
    protected $tableGateway;
    protected $_primary = '';
    protected $_columns = [];
    protected $_active_column_name = '';
    protected $_order_by = [];

    public function set_columns($columns = []) {
        $this->_columns = $columns;
    }

    public function build_values_comodin($values, $glue = '\'') {

        echo count($values);
        $array_tmp = array_fill(0, count($values), '?');
        return $glue . implode($glue . ',' . $glue, $array_tmp) . $glue;
        
    }

    public function build_values($values, $glue = '\'') {
        return $glue . implode($glue . ',' . $glue, $values) . $glue;
    }

    public function bulk_insert_values($values_group, $glue = ',') {
        $str_tmp = [];
        foreach ($values_group as $values) {

            $str_tmp[] = '(' . $this->build_values($values) . ')';
        }
        return implode($glue, $str_tmp);
    }

    public function contar($params = []) {
        // Metodo para contar las filas
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from($this->_name)
                    ->columns([
                        'count' => new \Zend\Db\Sql\Expression('COUNT(1)')
            ]);
            if (count($params) > 0) {
                $select->where($params);
            }

            $selectString = $sql->getSqlStringForSqlObject($select);

            $resultSet = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);

            return intval($resultSet->toArray()[0]['count']);
        } catch (\Exception $ex) {
            return [

                'codigo' => -100,
                'mensaje' => $ex->getMessage(),
            ];
        }
    }

    public function fetchAll($where = null, $order = null, $limit = 0, $offset = 0) {

        $adapter = $this->adapter;
        $sql = new Sql($adapter);

        try {
            $select = $sql->select();
            $select->from($this->_name);
            if (count($this->_columns) > 0) {
                $select->columns($this->_columns);
            }

            if (!is_null($where)) {
                $select->where($where);
            }
            if (!is_null($order)) {
                $select->order($order);
            }

            if ($limit > 0 || $offset > 0) {
                $select->limit($limit);
                $select->offset($offset);
            }
            $selectString = $sql->getSqlStringForSqlObject($select);
            $resultSet = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
        } catch (\Exception $ex) {
            return $ex;
        }
        return $resultSet;
    }

    public function getOne($id, $where = []) {
        $adapter = $this->adapter;
        $sql = new Sql($adapter);

        try {
            $select = $sql->select();
            $select->from($this->_name);
            if (!empty($this->_columns)) {
                $select->columns($this->_columns);
            }
            $params = $where;
            if (count($where) === 0) {
                $params[$this->_primary] = $id;
            } else {
                
            }
            $params[$this->_active_column_name] = 1;
            $select->where($params);

            $selectString = $sql->getSqlStringForSqlObject($select);

            $resultSet = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            $resultSet = $resultSet->toArray();
            if (isset($resultSet[0])) {
                $resultSet = $resultSet[0];
            } else {
                return null;
            }
        } catch (\Exception $ex) {
            return $ex;
        }
        return $resultSet;
    }

    public function eliminar($id) {

        $adapter = $this->adapter;
        $sql = new Sql($adapter);

        try {
            $select = $sql->update();
            $select->from($this->_name);
            $select->set([
                'activo' => 0
            ]);
            $params = [];
            $params[$this->_primary] = $id;
            $select->where($params);

            $selectString = $sql->getSqlStringForSqlObject($select);
            $resultSet = $adapter->query($selectString, $adapter::QUERY_MODE_EXECUTE);
            $resultSet = $resultSet->toArray();
            if (isset($resultSet[0])) {
                $resultSet = $resultSet[0];
            } else {
                return null;
            }
        } catch (\Exception $ex) {
            return $ex;
        }
        return $resultSet;
    }

}
