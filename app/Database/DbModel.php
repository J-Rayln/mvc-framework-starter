<?php

namespace JonathanRayln\UdemyClone\Database;

use JonathanRayln\UdemyClone\Application;
use JonathanRayln\UdemyClone\Models\BaseModel;

abstract class DbModel extends BaseModel
{
    /**
     * The table name to apply methods to.
     *
     * @return string
     */
    abstract public static function tableName(): string;

    /**
     * Array of column names that should be saved in the current table.
     *
     * @return array
     */
    abstract public function attributes(): array;

    /**
     * The primary key for the current model's database table.
     *
     * @return string
     */
    abstract public static function primaryKey(): string;

    /**
     * Saves a record into the database.
     *
     * @return true
     * @throws \PDOException
     */
    public function save(): bool
    {
        $tableName = $this->tableName();
        $attributes = $this->attributes();
        $params = array_map(fn($attr) => ":$attr", $attributes);
        $statement = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ")
            VALUES(" . implode(',', $params) . ")");
        foreach ($attributes as $attribute) {
            $statement->bindValue(":$attribute", $this->{$attribute});
        }

        $statement->execute();
        return true;
    }

    // public function findAll(): bool|array
    // {
    //     $tableName = $this->tableName();
    //     $statement = self::prepare("SELECT * FROM $tableName");
    //     $statement->execute();
    //     return $statement->fetchAll();
    // }

    public static function findAll(?string $tableName = null, ?array $where = null): false|array
    {
        $tableName = $tableName ?? static::tableName();
        $attributes = $where ? array_keys($where) : [];
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        $whereString = $where ? ' WHERE ' . $sql : '';
        $statement = self::prepare("SELECT * FROM $tableName$whereString");
        if ($where) {
            foreach ($where as $key => $item) {
                $statement->bindValue(":$key", $item);
            }
        }

        $statement->execute();
        return $statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Returns a single object for the given parameters passed.
     *
     * @param array $where
     * @return DbModel|bool|\stdClass|null
     */
    public static function findOne(array $where): DbModel|bool|\stdClass|null
    {
        $tableName = static::tableName();
        $attributes = array_keys($where);
        $sql = implode("AND ", array_map(fn($attr) => "$attr = :$attr", $attributes));
        // SELECT * FROM $tableName WHERE email = :email AND firstname = :firstname
        $statement = self::prepare("SELECT * FROM $tableName WHERE $sql");
        foreach ($where as $key => $item) {
            $statement->bindValue(":$key", $item);
        }

        $statement->execute();
        return $statement->fetchObject(static::class);
    }

    /**
     * Prepares the SQL statement and returns the string.
     *
     * @param string $sql SQL statement to prepare.
     * @return \PDOStatement|false
     */
    public static function prepare(string $sql): bool|\PDOStatement
    {
        return Application::$app->db->pdo->prepare($sql);
    }
}