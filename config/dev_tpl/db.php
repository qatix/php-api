<?php
/**
 * Created by PhpStorm.
 * User: hawk
 * Date: 5/20/16
 * Time: 16:36
 */


global $cfg_mdb;
$cfg_mdb = array(
    'mongo' => array(
        'default' => array(
            'dbhost' => 'mongodb://127.0.0.1:27017',
            'dbname' => 'default'
        ),
        'notification' => array(
            'dbhost' => 'mongodb://127.0.0.1:27017',
            'dbname' => 'notification'
        ),
        'weixin' => array(
            'dbhost' => 'mongodb://127.0.0.1:27017',
            'dbname' => 'weixin'
        ),
        'errorlog' => array(
            'dbhost' => 'mongodb://127.0.0.1:27017',
            'dbname' => 'errorlog'
        ),
    ),
    'redis' => array(
        'default' => array(
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'auth' => ''
        ),
        'klog' => array(
            'hostname' => '127.0.0.1',
            'port' => 6379,
            'auth' => ''
        ),
    ),
    'mem' => array(
        'default' => array(
            'hostname' => '127.0.0.1',
            'port' => 11211,
            'username' => '',
            'password' => ''
        ),
    )
);