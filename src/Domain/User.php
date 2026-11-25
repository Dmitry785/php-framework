<?php

namespace App\Domain;

use Framework\ORM\Model;

class User extends Model{
    protected $fillable = ["id", "age", "name"];
}