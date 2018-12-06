<?php
/**
 * Created by PhpStorm.
 * User: Xiaojun Tang
 * Date: 14-6-5
 * Time: PM5:44
 */

class EmptyService{
    public function get(){

    }

    public function set(){

    }
}

final class Service {
    private $data = array();
    private $registry = null;

    public function __construct($registry) {
        $this->registry = $registry;
    }

    public function get($key) {

        if(isset($this->data[$key])){
            $obj = $this->data[$key];
            if(empty($obj['instance'])){
                $class = $obj['class'];

                $obj['instance'] =  new $class($this->registry);
                $this->data[$key] = $obj;
            }
            return $obj['instance'];
        }else{
            return new EmptyService();
        }
    }

    public function set($key, $class) {
        $this->data[$key] = array(
            'class' => $class
        );
    }

    public function has($key) {
        return isset($this->data[$key]);
    }
}
?>