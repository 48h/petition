<?php
/**
 * Created by PhpStorm.
 * User: fonpah
 * Date: 01.05.2015
 * Time: 01:53
 */
$params = array();

if(file_exists(__DIR__.'/params.local.php')){
    $localParams = require __DIR__ . '/params.local.php';
    $params = array_merge_recursive($params, $localParams);
}
return $params;