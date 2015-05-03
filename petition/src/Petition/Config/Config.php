<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 02.05.2015
 * Time: 01:57
 */

namespace Petition\Config;


class Config {
    private static $instance = null;
    private $cfg  = null;
    public static function getInstance(){
        if(!self::$instance){
            $cfg = require __DIR__ . '/../../../config/application.config.php';
            self::$instance = new Config($cfg);
        }
        return self::$instance;
    }

    public function __construct(array $cfg){
        $this->cfg = $cfg;
    }

    public function setParam($key,$value){
        $this->cfg['params'][$key] = $value;
        return $this;
    }

    public function getParam($key){
        if(!$this->cfg['params']){
            return null;
        }
        if(!$this->cfg['params'][$key]){
            return false;
        }

        return $this->cfg['params'][$key];
    }
}