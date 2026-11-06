<?php

namespace App\ORM;

use JsonSerializable;
use PDO;
use ReflectionClass;
use RuntimeException;

class Model implements JsonSerializable{
    private static Database $database;
    private $fields = [];
    protected $fillable;

    public function __construct(?array $data = null){
        if(empty($data)){
            return;
        }
        if(!isset(($this->fillable))){
            $this->fields = $data;
            return;
        }
        foreach($data as $key => $value){
            if(!in_array($key, $this->fillable)){
                throw new RuntimeException("Field '$key' is not allowed for mass assigment");
            }
        }
        $this->fields = $data;
    }
    public static function setDatabase(Database $database){
        self::$database = $database;
    }
    public static function getTableName(): string{
        $reflection = new ReflectionClass(static::class);
        return $reflection->getShortName() . "s";
    }
    public static function find(mixed $id): ?static {
        $object = new static();
        $tablename = static::getTableName();
        $sql = "SELECT * FROM $tablename WHERE id = :id";
        $statement = self::$database
            ->getPdo()
            ->prepare($sql);
        $statement->execute(['id' => $id]);
        $data = $statement->fetch(PDO::FETCH_ASSOC);
        if($data == null)
            return null;
        $object->fill($data);
        return $object;
    }
    public static function query(): QueryBuilder{
        
    }
    private function fill(array $data){
        $this->fields = $data;
    }
}