<?php

namespace Framework\ORM;

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
        return new QueryBuilder(static::class, self::$database, self::getTableName());
    }
    public function __get(string $name): mixed{
        return $this->fields[$name];
    }
    public function __set(string $name, mixed $value): void{
        $this->fields[$name] = $value;
    }
    public function jsonSerialize(): mixed{
        return $this->fields;
    }
    public function __tostring(){
        return json_encode($this->fields);
    }
    public function delete(): void{
        if(count($this->fields) == 0)
            throw new RuntimeException("Cannot delete the entity without any fields");
        if(!isset($this->fields["id"]))
            throw new RuntimeException("Cannot delete the entity without an id");
        $sql = "DELETE FROM " . static::getTableName(). " WHERE id=:id";
        $statement = self::$database
            ->getPdo()
            ->prepare($sql);
        $statement->execute(["id"=> $this->fields["id"]]);
    }
    public function save(): void{
        if(count($this->fields) == 0)
            throw new RuntimeException("Cannot save the entity without any fields");
        if(isset($this->fields["id"]))
        {
            $this->update();
            return;
        }
        $this->insert();
    }
    private function insert(){
        if(count($this->fields) == 0)
            throw new RuntimeException("Cannot save the entity without any fields");
        $bindingParameters = [];
        foreach($this->fields as $key => $value)
            $bindingParameters[":".$key] = $value;
        $sql = "INSERT INTO " . static::getTableName() . " " 
        . implode(", ", array_keys($this->fields)) . " VALUES " 
        . implode(", ", array_keys($bindingParameters));
        $statement = self::$database
        ->getPdo()
        ->prepare($sql);
        $statement->execute($bindingParameters);
        $this->fields['id'] = intval(self::$database
            ->getPdo()->lastInsertId());
    }
    private function update(){
        if(count($this->fields) == 0)
            throw new RuntimeException("Cannot insert the entity without any fields");
        if(!isset($this->fields["id"]))
            throw new RuntimeException("Cannot update the entity without an id");

        $bindingParameters = [];
        foreach($this->fields as $key => $value)
            $bindingParameters[":".$key] = $value;

        $id = $this->fields["id"];
        unset($this->fields["id"]);
        
        $sql = "UPDATE "
        . static::getTableName() 
        ." SET ".implode(", ", array_map(fn($key) => 
            "$key=:$key", array_keys($this->fields)))
        ." WHERE id=:id";

        $statement = self::$database
            ->getPdo()
            ->prepare($sql);

        $statement->execute($bindingParameters);
        $this->fields['id'] = $id;
    }
    private function fill(array $data){
        $this->fields = $data;
    }

}