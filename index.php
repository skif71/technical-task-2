<?php

/*
- Імплементувати класс для роботи з базою даних.
- В класі повинен бути функціонал для виконання CRUD операцій.
- Створений клас в першому пункті повинен мати можливість для роботи з різними стореджами. Для поточного тестового це (MySQL та SQLite)
- В методах контроллера що нижче повинні міститись наступний код:
- Створення класу з першого пункту
- Приклад CRUD операцій для роботи з MySQL
- Приклад CRUD операцій для роботи з SQLite
- Використання інтерфейсу буде плюсом.
*/


interface DataBaseInterface
{
    public function connect(string $type);

    public function disconnect();

    public function query(string $query);

    public function Insert(string $tableName, array $data);

    public function Select(string $tableName, string $rows, string $where);

    public function Update(string $tableName, string $where, array $data);

    public function Delete(string $tableName, string $where);

}

class Database implements DataBaseInterface
{
    private string $dbHost = "localhost";
    private string $dbUser = "username";
    private string $dbPass = "password";
    private string $dbName = "database";
    private \PDO $connection;

    public function __construct($dbHost, $dbUser, $dbPass, $dbName)
    {
        $this->dbHost = $dbHost;
        $this->dbUser = $dbUser;
        $this->dbPass = $dbPass;
        $this->dbName = $dbName;
    }

    public function connect(string $type)
    {
        $this->connection = new \PDO($type . ":host=" . $this->dbHost . ";", $this->dbUser, $this->dbPass,);
    }

    public function disconnect()
    {
        $this->connection->close();
    }

    public function query(string $query)
    {
        return $this->connection->query($query);
    }

    public function Insert(string $tableName, array $data)
    {
        $query = 'INSERT INTO `' . $tableName . '` (`' . implode('`, `', array_keys($data)) . '`) VALUES ("' . implode('", "', $data) . '")';
        return $this->query($query);
    }

    public function Select(string $tableName, string $rows, string $where = '')
    {
        $query = 'SELECT ' . $rows . ' FROM ' . $tableName;
        if ($where != '') {
            $query .= ' WHERE ' . $where;
        }
        return $this->query($query);
    }

    public function Update(string $tableName, string $where, array $data)
    {
        $args = array();

        foreach ($data as $field => $value) {
            $args[] = $field . '="' . $value . '"';
        }

        $query = 'UPDATE '.$tableName.' SET '.implode(',',$args).' WHERE '.$where;
        if ($where != '') {
            $query .= ' WHERE ' . $where;
        }
        return $this->query($query);
    }

    public function Delete(string $tableName, string $where)
    {
        $query = 'DELETE FROM '.$tableName.' WHERE '.$where;
    }


}

class TestController
{
    private string $dbHost = "localhost";
    private string $dbUser = "username";
    private string $dbPass = "password";
    private string $dbName = "database";
    private Database $connection;

    public function crudMysql()
    {
        $this->connection = (new Database(
            $this->dbHost,
            $this->dbUser,
            $this->dbPass,
            $this->dbName
        ));
        $this->connection->connect('mysql');

    }

    public function crudSQLite()
    {
        $this->connection = (new Database(
            $this->dbHost,
            $this->dbUser,
            $this->dbPass,
            $this->dbName
        ));
        $this->connection->connect('sqlite');
    }

    public function test()
    {
        $this->crudMysql();
        $this->connection->Select('newDBTable', 'id,name','id=2');
    }
}


