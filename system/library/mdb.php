<?php

/**
 * Created by PhpStorm.
 * User: hawk
 * Date: 5/20/16
 * Time: 11:21
 */
final class Mdb
{
    /**
     * 用于存连接
     * @var array
     */
    private $mongo_instances = array();
    private $redis_instances = array();
    private $mem_instances = array();
    private $config;

    public function __construct()
    {
        $this->config = $GLOBALS['cfg_mdb'];
    }

    public function getMongo($key = 'default')
    {
        if (empty($key) || !isset($this->config['mongo'][$key])) {
            return null;
        }

        $mongo_server = $this->config['mongo'][$key];

        if (isset($this->mongo_instances[$key])) {
            $mongo_client = $this->mongo_instances[$key];
            return $mongo_client->selectDB($mongo_server['dbname']);
        } else {
            //check if can reuse
            foreach ($this->config['mongo'] as $app_key => $app_server) {
                if ($app_key != $key && $app_server['dbhost'] == $mongo_server['dbhost']) {
                    if (isset($this->mongo_instances[$app_key])) {
                        $mongo_client = $this->mongo_instances[$app_key];
                        $this->mongo_instances[$key] = $mongo_client;
//                        echo 'reuse mongo:' . $app_key;
                        return $mongo_client->selectDB($mongo_server['dbname']);
                    }
                }
            }
        }

        $mongo_client = new MongoClient($mongo_server['dbhost']);
        $this->mongo_instances[$key] = $mongo_client;
        return $mongo_client->selectDB($mongo_server['dbname']);
    }

    public function getRedis($key = 'default')
    {
        if (empty($key) || !isset($this->config['redis'][$key])) {
            return null;
        }

        if (isset($this->redis_instances[$key])) {
            return $this->redis_instances[$key];
        }

        $server = $this->config['redis'][$key];

        //check if can reuse
        foreach ($this->config['redis'] as $app_key => $app_server) {
            if ($app_key != $key && $app_server['hostname'] == $server['hostname'] && $app_server['port'] == $server['port']) {
                if (isset($this->redis_instances[$app_key])) {
                    $this->redis_instances[$key] = $this->redis_instances[$app_key];
//                    echo 'reuse redis:' . $app_key;
                    return $this->redis_instances[$key];
                }
            }
        }

        $redis = new Redis();
        //默认500毫秒的超时连接时间
        $redis->connect($server['hostname'], $server['port'], 500);

        if (!empty($server['auth'])) {
            $redis->auth($server['auth']);
        }

        $redis->setOption(Redis::OPT_SERIALIZER, Redis::SERIALIZER_PHP);

        $this->redis_instances[$key] = $redis;
        return $this->redis_instances[$key];
    }

    public function getMem($key = 'default')
    {
        if (empty($key) || !isset($this->config['mem'][$key])) {
            return null;
        }

        if (isset($this->mem_instances[$key])) {
            return $this->mem_instances[$key];
        }

        $mem_server = $this->config['mem'][$key];

        //check if can reuse
        foreach ($this->config['mem'] as $app_key => $app_server) {
            if ($app_key != $key && $app_server['hostname'] == $mem_server['hostname'] && $app_server['port'] == $mem_server['port']) {
                if (isset($this->mem_instances[$app_key])) {
                    $this->mem_instances[$key] = $this->mem_instances[$app_key];
//                    echo 'reuse mem:' . $app_key;
                    return $this->mem_instances[$key];
                }
            }
        }

        $memc = new Memcached('ocs'); //这里的ocs，就是persistent_id,无法用连接池,线上有诡异的问题,有时连接失败
        if (count($memc->getServerList()) == 0) /*建立连接前，先判断*/
        {
            //New connection
            /*所有option都要放在判断里面，因为有的option会导致重连，让长连接变短连接！*/
            $memc->setOption(Memcached::OPT_COMPRESSION, false);
            $memc->setOption(Memcached::OPT_BINARY_PROTOCOL, true);

            /* addServer 代码必须在判断里面，否则相当于重复建立’ocs’这个连接池，可能会导致客户端php程序异常*/
            $memc->addServer($mem_server['hostname'], $mem_server['port']);

            if (!empty($mem_server['username'])) {
                $mem_server['password'] = !empty($mem_server['password']) ? $mem_server['password'] : '';
                $memc->setSaslAuthData($mem_server['username'], $mem_server['password']);
            }
        }

        $this->mem_instances[$key] = $memc;
        return $this->mem_instances[$key];
    }
}

?>