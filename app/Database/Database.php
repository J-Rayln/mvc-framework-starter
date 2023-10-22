<?php

namespace JonathanRayln\UdemyClone\Database;

class Database
{
    public \PDO $pdo;

    public function __construct()
    {
        $driver = env('DB_DRIVER');
        $host = env('DB_HOST');
        $port = env('DB_PORT');
        $dbName = env('DB_NAME');
        $charset = env('DB_CHARSET');
        $user = env('DB_USER');
        $pass = env('DB_PASS');

        $dsn = "{$driver}:host={$host};port={$port};dbname={$dbName};charset={$charset}";
        $options = [
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_OBJ,
            \PDO::ATTR_EMULATE_PREPARES   => false,
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
        ];
        try {
            $this->pdo = new \PDO($dsn, $user, $pass, $options);
        } catch (\PDOException $e) {
            throw new \PDOException($e->getMessage(), (int)$e->getCode());
        }
    }

    // public function query($query, $data = [], $type = 'object')
    // {
    //     $con = $this->connect();
    //
    //     $stm = $con->prepare($query);
    //     if ($stm) {
    //         $check = $stm->execute($data);
    //         if ($check) {
    //             if ($type != 'object') {
    //                 $type = \PDO::FETCH_ASSOC;
    //             } else {
    //                 $type = \PDO::FETCH_OBJ
    //             }
    //
    //             $result = $stm->fetchAll($type);
    //
    //             if ($result && count($result) > 0) {
    //                 return $result;
    //             }
    //         }
    //     }
    //
    //     return false;
    // }

    public function query($sql, $args = []): bool|array
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($args);

        $result = $stmt->fetchAll();

        if (is_array($result) && count($result) > 0) {
            return $result;
        }

        return false;
    }

    /**
     * Shortcut method to prepare PDO statements.
     *
     * @param string $sql
     * @return \PDOStatement|false
     */
    public function prepare(string $sql): bool|\PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
}