<?php

namespace core;

class DB
{
    protected $PDO;
    public function __construct($server, $userName, $userPassword, $databaseName)
    {
        $this->PDO = new \PDO(
            "mysql:host={$server};dbname={$databaseName}",
            $userName,
            $userPassword
        );
    }
    public function executeQuery(DBQuery $query)
    {
        $result = $query->getSQL();
        $statement = $this->PDO->prepare($result['sql']);
        $statement->execute($result['params']);
        return $statement->fetchAll();
    }
}
