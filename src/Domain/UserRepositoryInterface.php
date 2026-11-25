<?php

namespace App\Domain;

interface UserRepositoryInterface{
    public function getAll(): array;
    public function findById($id): User;
    public function create(User $user): User;
    public function update(User $user): User;
    public function deleteById($id): void;
}