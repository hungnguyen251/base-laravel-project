<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;

class UserService
{
    protected $users;

    public function __construct(UserRepository $userRepository)
    {
        $this->users = $userRepository;
    }

    public function getAll($attrs)
    {
        return $this->users->getAll($attrs);
    }

    public function getById(int $id)
    {
        return $this->users->getById($id);
    }

    public function create(array $attrs)
    {
        if (!empty($attrs['password'])) {
            $attrs['password'] = Hash::make($attrs['password']);
        }

        return $this->users->store($attrs);
    }

    public function update(int $id, array $attrs)
    {
        return $this->users->updateById($id, $attrs);
    }

    public function delete(int $id)
    {
        return $this->users->deleteById($id);
    }
}
