<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Usuario\Model;

use Zend\Db\Adapter\Adapter;
use Zend\Db\TableGateway\TableGateway;
use Zend\Db\Sql\Sql;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Insert;
use Zend\Db\Sql\Update;
use Zend\Db\Sql\Delete;
use Zend\Crypt\Password\Bcrypt;

class OauthTempTable extends \BaseX\Model\Table {

    protected $_name = 'oauth_users_temp';
    protected $_primary = '';
    protected $_estado = 1;

    public function __construct(Adapter $adapter)
    {
        $this->adapter = $adapter;
    }

    public function validacion_temporal($params = [], $order = '', $rows = 0, $page = 0)
    {

        $password = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, '35e907c7bd0e7619ced8e8a1651ccecb', $params['password'], MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['oau' => $this->_name])
                    ->columns([
                        'user_id',
                        'username',
                        'password',
                        'first_name',
                        'last_name',
                        'apellido_materno',
                        'display_name',
                        'email',
                        'documento_tipo',
            ]);
            $params_w = [];
            $params_w['oau.state'] = $this->_estado;
            $params_w['oau.username'] = $params['username'];
            $params_w['oau.password_1'] = $password;
            if (count($params_w) > 0)
            {
                $select->where($params_w);
            }

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

    public function obtener_datos_user($params = [], $order = '', $rows = 0, $page = 0)
    {
        // Esta forma es para llamar a los Select simples, como tablas o tablas con FK usando Joins
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['oau' => $this->_name])
                    ->columns([
                        'username',
                        'flg_obtencion_datos',
                        'documento_tipo',
                        'first_name',
                        'last_name',
                        'fecha_nacimiento',
                        'apellido_materno',
                        'display_name',
                        'email',
                        'sexo',
                        'user_id'
                    ])
            ;

            if (count($params) > 0)
            {
                $select->where($params);
            }

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

    public function getDatosUsuario($params = [], $order = '', $rows = 0, $page = 0)
    {
        // Esta forma es para llamar a los Select simples, como tablas o tablas con FK usando Joins
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['oau' => $this->_name])
                    ->columns([
                        'user_id',
                        'username',
                        'documento_tipo',
                        'first_name',
                        'last_name',
                        'apellido_materno',
                        'email',
                        'password_1',
                        'token_tmp',
            ]);

            if (count($params) > 0)
            {
                $select->where($params);
            }

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

    public function update_clave($username, $correo)
    {
        $diccionario = "ABCDEFGHJKMNPRTUVWXYZ123456789";
        $tam_diccionario = strlen($diccionario);
        $tam_clave = 6;
        $a_clave = [];
        for ($i = 0; $i < $tam_clave; $i++)
        {
            $a_clave[] = $diccionario[rand(0, $tam_diccionario - 1)];
        }
        $clave = implode('', $a_clave);
        $password_rijndael = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, '35e907c7bd0e7619ced8e8a1651ccecb', strtoupper($clave), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));
        $bcrypt = new Bcrypt();
        $password_bcrypt = $bcrypt->create(strtoupper($clave));

        try {

            $sql_v = 'update oauth_users_temp set password = ?, password_1 =? where username =? and email =?';
            $paramsUpdate = array(
                $password_bcrypt,
                $password_rijndael,
                $username,
                $correo
            );
            $resultset = $this->adapter->query($sql_v, $paramsUpdate);
        } catch (\Exception $ex) {
            throw $ex;
        }
    }

    public function getTokenTemporal($params = [], $order = '', $rows = 0, $page = 0)
    {
        // Esta forma es para llamar a los Select simples, como tablas o tablas con FK usando Joins
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['oau' => $this->_name])
                    ->columns([
                        'token_tmp',
                    ])
            ;

            if (count($params) > 0)
            {
                $select->where($params);
            }

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

    public function nuevoUsuario($objUsuario)
    {

        $diccionario = "ABCDEFGHJKMNPRTUVWXYZ123456789";
        $tam_diccionario = strlen($diccionario);
        $tam_clave = 6;
        $a_clave = [];
        for ($i = 0; $i < $tam_clave; $i++)
        {
            $a_clave[] = $diccionario[rand(0, $tam_diccionario - 1)];
        }
        $clave = implode('', $a_clave);

        $password2 = trim(base64_encode(mcrypt_encrypt(MCRYPT_RIJNDAEL_256, '35e907c7bd0e7619ced8e8a1651ccecb', strtoupper($clave), MCRYPT_MODE_ECB, mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_RIJNDAEL_256, MCRYPT_MODE_ECB), MCRYPT_RAND))));

        $bcrypt = new Bcrypt();
        $password = $bcrypt->create(strtoupper($clave));
        $state = 0;
        $acl_acl_id = 0;
        $token_temp = $objUsuario['username'] . $objUsuario['email'] . $objUsuario['username'];
        try {

            $sql = 'insert into oauth_users_temp(id_concurso,username,password,password_1,first_name,last_name,apellido_materno,email,fecha_nacimiento,flg_obtencion_datos,sexo,display_name,state,acl_acl_id, documento_tipo,token_tmp,usu_creacion,fec_creacion) '
                    . 'values(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,now()) ';

            $params = array(
                $objUsuario['id_concurso'],
                $objUsuario['username'],
                $password,
                $password2,
                $objUsuario['first_name'],
                $objUsuario['last_name'],
                $objUsuario['apellido_materno'],
                $objUsuario['email'],
                $objUsuario['fecha_nacimiento'],
                $objUsuario['flg_obtencion_datos'],
                $objUsuario['sexo'],
                $objUsuario['display_name'],
                $state,
                $acl_acl_id,
                $objUsuario['documento_tipo'],
                sha1($token_temp),
                $objUsuario['username'],
            );

            $insert = $this->adapter->query($sql, $params);
            $lastId = $this->adapter->getDriver()->getLastGeneratedValue();

            return array(
                'id' => $lastId,
                'mensaje' => 'Por favor verifique en su correo electrónico el link enviado para validar su correo electrónico.',
                'username' => $objUsuario['username'],
                'email' => $objUsuario['email'],
            );
        } catch (\Exception $e) {
            return array('id' => -101, 'mensaje' => $e->getMessage());
        }
    }

    public function validarUsuario($obj)
    {
        try {
            $sql = 'update oauth_users_temp set state=1 where user_id=?';
            $params = array($obj['user_id']);
            $this->adapter->query($sql, $params);
            return array(
                'id' => 100,
                'mensaje' => 'Usuario creado satisfactoriamente. Por favor verifique en su correo electrónico su usuario y contraseña para continuar con el proceso de inscripción',
            );
        } catch (\Exception $e) {
            return array('id' => -101, 'mensaje' => $e->getMessage());
        }
    }

    public function obtener_datos_formatos_generados($datos)
    {
        $adapter = $this->getAdapter();
        try {
            $sql = 'call INS_UFN_GET_CODIGO_VALIDO(:username,:email,:concurso)';

            $driver = $this->getAdapter()->getDriver();
            $stmt = $driver->createStatement($sql);
            $stmt->prepare();
            $result = $stmt->execute($datos);
            $current = $result->current();
            $result->getResource()->closeCursor();
            return $current;
        } catch (\Exception $e) {
            return array('codigo' => -1, 'msg' => $e->getMessage());
        }
    }

    public function sp_log_reniec($params_sp)
    {

        try {
            //ejecucion del store
            $sql = 'call INS_USP_INS_CONSULTA_LOG(:p_xml,:node_cab,:extra)';
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

    public function sp_updte_reniec($params_sp)
    {

        try {
            //ejecucion del store
            $sql = 'call INS_USP_UPD_DATOS_PERSONA(:p_xml,:node_cab,:extra)';
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

    public function getDatosMaePersona($params = [], $order = '', $rows = 0, $page = 0)
    {
        // Esta forma es para llamar a los Select simples, como tablas o tablas con FK usando Joins
        try {
            $adapter = $this->getAdapter();
            $sql = new Sql($adapter);

            $select = $sql->select();
            $select->from(['per' => 'mae_persona'])
                    ->columns([
                        'cod_documento',
                        'flg_obtencion_datos',
                        'des_nombres',
                        'des_ape_paterno',
                        'des_ape_materno',
                        'fec_nacimiento',
                        'sexo',
                    ])
            ;

            if (count($params) > 0)
            {
                $select->where($params);
            }

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

}
