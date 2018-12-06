<?php

final class MyMySQLi
{
    private $link;

    public function __construct($hostname, $username, $password, $database)
    {
        if (!$this->link = mysqli_connect($hostname, $username, $password)) {
            trigger_error('Error: Could not make a database link using ' . $username . '@' . $hostname);
        }

        if (!mysqli_select_db($this->link, $database)) {
            trigger_error('Error: Could not connect to database ' . $database);
        }

        mysqli_set_charset($this->link, 'utf8');
    }

    public function query($sql)
    {
        if ($this->link) {
            $result = mysqli_query($this->link, $sql);

            if ($result) {
                $i = 0;

                $data = array();
                if (!is_bool($result)) {
                    while ($row = mysqli_fetch_assoc($result)) {
                        $data[$i] = $row;

                        $i++;
                    }
                    mysqli_free_result($result);
                }

                $query = new stdClass();
                $query->row = isset($data[0]) ? $data[0] : array();
                $query->rows = $data;
                $query->num_rows = $i;

                unset($data);

                return $query;
            } else {
                trigger_error('Error: ' . mysqli_error($this->link) . '<br />Error No: ' . mysqli_errno($this->link) . '<br />' . $sql);
                exit();
            }
        }
    }

    public function escape($value)
    {
        if ($this->link) {
            return mysqli_real_escape_string($this->link, $value);
        }
    }

    public function countAffected()
    {
        if ($this->link) {
            return mysqli_affected_rows($this->link);
        }
    }

    public function getLastId()
    {
        if ($this->link) {
            return mysqli_insert_id($this->link);
        }
    }

    /**
     * 事务之前必须先调用这一句
     * @author huangjin
     */
    public function beginTransaction()
    {
        if ($this->link) {
            mysqli_autocommit($this->link, false);
        }
    }

    /**
     * 事务回滚操作
     * @author huangjin
     */
    public function rollback()
    {
        if ($this->link) {
            mysqli_rollback($this->link);
        }
    }

    /**
     * 事务提交
     * @author huangjin
     */
    public function commit()
    {
        if ($this->link) {
            mysqli_commit($this->link);
        }
    }

    /**
     * 事务完成后必须调用这个函数
     *
     * @author huangjin
     */
    public function endTransaction()
    {
        if ($this->link) {
            mysqli_autocommit($this->link, true);
        }
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
        if ($this->link) {
            return mysqli_errno($this->link);

        }
    }

    public function __destruct()
    {
        if ($this->link) {
            mysqli_close($this->link);
        }
    }
}

?>