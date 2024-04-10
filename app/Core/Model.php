<?php

namespace App\Core;

use PDOStatement;

class Model
{
    /**
     * TODO: Move into Database class. Find link with Model.
     */

    protected static array $whereConditions = [];

    public static function where(string $column, $value): static
    {
        static::$whereConditions[$column] = $value;
        return new static();
    }

    public static function getWhereConditions(): array {
        return static::$whereConditions;
    }

    public static function clearWhereConditions(): void {
        static::$whereConditions = [];
    }

    public static function buildWhereClause(): string
    {
        $whereClause = '';
        $conditions = static::getWhereConditions();

        if (!empty($conditions)) {
            $whereClause = " WHERE ";

            foreach ($conditions as $column => $value) {
                $whereClause .= "$column = :$column AND ";
            }

            $whereClause = rtrim($whereClause, 'AND ');
        }

        return $whereClause;
    }

    public static function executeQuery(string $sql, array $params = []): PDOStatement
    {
        $sql .= static::buildWhereClause();
        $statement = Database::getInstance()->prepare($sql);

        $statement->execute([
            ...$params,
            ...static::$whereConditions,
        ]);

        static::clearWhereConditions();

        return $statement;
    }
}
