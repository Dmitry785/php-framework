<?php

namespace App\ORM;

use InvalidArgumentException;
use PDO;
use ReflectionClass;

class QueryBuilder
{
    private array $whereList = [];
    private array $parameters = [];
    private ?int $limit;
    private $orderList = [];
    public function __construct(
        private string $modelClass,
        private Database $database,
        private string $tableName
    ) {}
    public function where(string $columndName, string $operator, mixed $value): self{
        $parameterName = $this->registerParameter($value);
        $this->whereList[] = "$columndName $operator $parameterName";
        return $this;
    }
    public function orderBy(string $columnName, ?string $direction = "ASC"): self{
        $column = $this->escapeColumn($columnName);
        if($direction !== "ASC" and $direction !== "DESC")
            throw new InvalidArgumentException("direction must be ASC or DESC");
        $this->orderList[$column] = $direction;
        return $this;
    }
    public function limit(int $limit): self{
        $this->limit = $limit;
        return $this;
    }
    public function toList(): array{
        $sql = "SELECT * FROM $this->tableName";
        if(count($this->whereList) != 0)
            $sql .= "WHERE " . implode(" AND ", $this->whereList) . " ";
        if(count($this->orderList) != 0)
        {
            $directions = [];
            foreach($this->orderList as $field => $direction){
                $directions[] = "$field $direction";
            }
            $sql .= "ORDER BY " . implode(", ", $directions) . " ";
        }
        if(isset($this->limit))
            $sql .= "LIMIT $this->limit";
        $statement = $this->database
            ->getPdo()
            ->prepare($sql);
        $statement->execute();
        $data = $statement->fetchAll(PDO::FETCH_ASSOC);
        $reflection = new ReflectionClass($this->modelClass);
        $models = [];
        foreach($data as $line){
            $model = $reflection->newInstance();
            foreach($line as $key => $value){
                $model->$key = $value;
            }
            $models[] = $model;
        }
        return $models;
    }
    public function first(): Model{
        return $this->limit(1)->toList()[0];
    }
    private function registerParameter($value) : string {
        $number = count($this->parameters)+1;
        $parameterName = ":p".$number;
        $this->parameters[$parameterName] = $value;
        return $parameterName;
    }
    private function escapeColumn($column) : string {
        $column = preg_replace("/[^a-zA-Z0-9_]/", "", $column);

        if (empty($column)) {
            return '`id`';
        }

        return '`' .  str_replace('`' , '``', $column) . '`';
    }
}