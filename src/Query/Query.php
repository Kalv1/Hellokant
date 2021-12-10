<?php

namespace Hellokant\Query;

use Hellokant\Factory\ConnectionFactory;
use PDO;

class Query
{
    private $sqltable;
    private $fields = '*';
    private $where = null;
    private $args = [];
    private $sql = '';

    public static function table(string $nametable): Query
    {
        $query = new Query();
        $query->sqltable = $nametable;
        return $query;
    }

    public function where(string $col, string $op, $value): Query
    {
        if (!is_null($this->where)) {
            $this->where .= ' AND ';
        }
        $this->where .= ' ' . $col . ' ' . $op . ' ? ';
        $this->args[] = $value;
        return $this;
    }

    public function get()
    {
        $this->sql = "SELECT " . $this->fields . "\n" .
            "FROM " . $this->sqltable . "\n";
        if ($this->where !== null) {
            $this->sql .= "WHERE " . $this->where;
        }
        $pdo = ConnectionFactory::getConnection();
        $request = $pdo->prepare($this->sql);
        $request->execute($this->args);
        return $request->fetchAll(PDO::FETCH_ASSOC);
    }

    public function select(array $select)
    {
        $this->fields = implode(",", $select);
        return $this;
    }

    public function delete(): int
    {
        if (!is_null($this->where)) {
            $this->sql = "DELETE FROM " . $this->sqltable . " WHERE " . $this->where;
        }
        $pdo = ConnectionFactory::getConnection();
        $request = $pdo->prepare($this->sql);
        $request->execute($this->args);
        return $request->rowCount();
    }

    public function insert(array $toInsert)
    {
        $tabsize = count($toInsert);
        $compteur = 0;
        $this->sql = "INSERT INTO " . $this->sqltable . " (";
        foreach ($toInsert as $k => $v) {
            $this->sql .= "$k";
            $compteur++;
            if (!($compteur == $tabsize)) {
                $this->sql .= ',';
            }
        }
        $this->sql .= ') VALUES (';
        $compteur = 0;
        foreach ($toInsert as $v) {
            $this->args[] = $v;
            $this->sql .= "?";
            $compteur++;
            if (!($compteur == $tabsize)) {
                $this->sql .= ',';
            }
        }
        $this->sql .= ')';

        $pdo = ConnectionFactory::getConnection();
        $request = $pdo->prepare($this->sql);
        $request->execute($this->args);

        return $pdo->lastInsertId($this->sqltable);
    }
}