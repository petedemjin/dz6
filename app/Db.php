<?php

namespace App;

class Db
{
    protected $dbh;
    private static $instance;

    public function __construct()
    {
        if (!$this->dbh) {
            $config = new Config();
            //$config = include __DIR__.'/Conf.php';

            $this->dbh = new \PDO (
                'mysql:host=' . $config->data['host'] . ';dbname=' . $config->data['dbname'],
                $config->data['user'], $config->data['password']
            );
        }
    }

    public static function getInstance(): self
    {
        if (!self::$instance) {
            self::$instance = new self();
        }
        return self::$instance;
    }
/*    public function query($sql, $data, $class)
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($data);
        return $sth->fetchAll(\PDO::FETCH_CLASS, $class);
    }*/

    public function exec(string $query, $_method, array $params = []): int
    {
        $t = microtime(1);
        $prepared = $this->dbh->prepare($query);
        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return -1;
        }
        $affectedRows = $prepared->rowCount();

        $this->log[] = [$query, microtime(1) - $t, $_method, $affectedRows];

        return $affectedRows;
    }

    public function fetchOne(string $query, $_method, array $params = [])
    {
        $t = microtime(true);
        $prepared = $this->dbh->prepare($query);

        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }

        $data = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();


        $this->log[] = [$query, microtime(true) - $t, $_method, $affectedRows];
        if (!$data) {
            return false;
        }
        return reset($data);
    }

    public function fetchAll(string $query, $_method, array $params = [])
    {
        $t = microtime(true);
        $prepared = $this->dbh->prepare($query);

        $ret = $prepared->execute($params);

        if (!$ret) {
            $errorInfo = $prepared->errorInfo();
            trigger_error("{$errorInfo[0]}#{$errorInfo[1]}: " . $errorInfo[2]);
            return [];
        }

        $data = $prepared->fetchAll(\PDO::FETCH_ASSOC);
        $affectedRows = $prepared->rowCount();
        $this->log[] = [$query, microtime(true) - $t, $_method, $affectedRows];

        return $data;
    }

    public function getLastId()
    {
        return $this->dbh->lastInsertId();
    }
}