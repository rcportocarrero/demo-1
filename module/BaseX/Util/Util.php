<?php

/**
 * Description of Util
 *
 * @author Carlos Sing
 */

namespace BaseX\Util;

use BaseX\Controller\BaseController;

class Util extends BaseController {

    public final function Session() {

        $config = $this->getConfig('session');
        return new \Zend\Session\Container($config['name']);
    }

    public final function Array2FileJson($_array = [], $_file_name = '') {

        try {
            $content = json_encode($_array);

            $config = $this->getConfig('app');
            $file_name = $config['projects'] . '/' . $_file_name;

            file_put_contents($file_name, $content);
            return TRUE;
        } catch (\Exception $ex) {
            echo $ex->getMessage();
            return FALSE;
        }
    }

    public final function get_list_directories($path = '', $orden = 1) {
        $list_dirs = [];
        $dirs_tmp = scandir($path, $orden);

        foreach ($dirs_tmp as $dir) {
//            if($dir === '.'){
//                continue;
//            }
//            if($dir === '..'){
//                continue;
//            }
            if (is_dir($path . '/' . $dir)) {
                $list_dirs[] = $dir;
            }
        }
    }

    public final function array_to_xml($array, &$xml_user_info) {
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if (!is_numeric($key)) {
                    $subnode = $xml_user_info->addChild("$key");
                    self::array_to_xml($value, $subnode);
                } else {
                    $subnode = $xml_user_info->addChild("item");
                    self::array_to_xml($value, $subnode);
                }
            } else {
                $xml_user_info->addChild("$key", htmlspecialchars("$value"));
            }
        }
    }

    public final function transform_detalle_to_format_xml($array, $error_msg) {
        $detalle_lista_tmp = [];
        foreach ($array as $row) {

            if (!is_numeric($row['id'])) {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            if (!is_numeric($row['value'])) {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            $row = [
                'c1' => intval($row['id']),
                'c2' => intval($row['value']),
            ];
            $detalle_lista_tmp[] = $row;
        }
        return $detalle_lista_tmp;
    }

    public final function transform_detalle_to_format_xml_c3($array, $error_msg) {
        $detalle_lista_tmp = [];
        foreach ($array as $row) {

            if (!is_numeric($row['id'])) {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            if (!is_numeric($row['value'])) {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            if (!is_numeric($row['rpt'])) {
//                break;
                return [
                    'id' => -105,
                    'mensaje' => $error_msg,
                ];
            }
            $row = [
                'c1' => intval($row['id']),
                'c2' => intval($row['value']),
                'c3' => intval($row['rpt']),
            ];
            $detalle_lista_tmp[] = $row;
        }
        return $detalle_lista_tmp;
    }

}
