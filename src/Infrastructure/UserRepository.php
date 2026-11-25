<?php

namespace App\Infrastructure;

use App\Domain\UserRepositoryInterface;
use App\Domain\User;
use Override;

class UserRepository implements UserRepositoryInterface{
    #[Override()]
    public function getAll(): array{
        return User::query()->toList();
    }
    #[Override()]
    public function findById($id): User{
        return User::find($id);
    }
    #[Override()]
    public function create(User $user): User{
        $user->save();
        return $user;
    }
    #[Override()]
    public function update(User $user): User{
        $user->save();
        return $user;
    }
    #[Override()]
    public function deleteById($id): void{
        User::find($id)->delete();
    }
}