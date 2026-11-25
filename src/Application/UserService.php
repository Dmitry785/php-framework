<?php

namespace App\Application;

use App\Domain\UserRepositoryInterface;
use App\Domain\User;

class UserService{
    function __construct(
        private UserRepositoryInterface $userRepository
    ){}
    public function getAll(): array{
        return $this->userRepository->getAll();
    }
    public function findById($id): User{
        return $this->userRepository->findById($id);
    }
    public function create(User $user): User{
        return $this->userRepository->create($user);
    }
    public function update(User $user): User{
        return $this->userRepository->update($user);
    }
    public function deleteById($id): void{
        $this->userRepository->deleteById($id);
    }
}