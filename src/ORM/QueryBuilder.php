<?php

namespace App\ORM;

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
        
    }
    private function registerParameter($value) : string {
        $number = count($this->parameters)+1;
        $parameterName = ":p".$number;
        $this->parameters[$parameterName] = $value;
        return $parameterName;
    }
}