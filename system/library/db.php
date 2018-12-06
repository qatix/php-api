<?php

class DB
{
    private $driver;
    private $database;

    public function __construct($driver, $hostname, $username, $password, $database)
    {
        $this->database = $database;

        if (file_exists(DIR_DATABASE . $driver . '.php')) {
            require_once(DIR_DATABASE . $driver . '.php');
        } else {
            exit('Error: Could not load database file ' . $driver . '!');
        }

        $this->driver = new $driver($hostname, $username, $password, $database);
    }

    public function query($sql)
    {
        return $this->driver->query($sql);
    }

    public function escape($value)
    {
        return $this->driver->escape($value);
    }

    public function countAffected()
    {
        return $this->driver->countAffected();
    }

    public function getLastId()
    {
        return $this->driver->getLastId();
    }

    /**
     * 事务之前必须先调用这一句
     * @author huangjin
     */
    public function beginTransaction()
    {
        $this->driver->beginTransaction();
    }

    /**
     * 事务回滚操作
     * @author huangjin
     */
    public function rollback()
    {
        $this->driver->rollback();
    }

    /**
     * 事务提交
     * @author huangjin
     */
    public function commit()
    {
        $this->driver->commit();
    }

    /**
     * 事务完成后必须调用这个函数
     *
     * @author huangjin
     */
    public function endTransaction()
    {
        $this->driver->endTransaction();
    }

    /**
     * 返回最近执行语句的错误
     * @return integer errno ,0 代表没有出错
     *
     * @author huangjin
     *
     */
    public function getErrno()
    {
        return $this->driver->getErrno();
    }

    public function getDatabase()
    {
        return $this->database;
    }
}

?>