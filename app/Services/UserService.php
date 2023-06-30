<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Http\Response;
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
        $data = $this->users->getAll($attrs);

        return response()->json($data, Response::HTTP_OK);
    }

    public function getById(int $id)
    {
        $data = $this->users->getById($id);

        return response()->json($data, Response::HTTP_OK);
    }

    public function create(array $attrs)
    {
        if (!empty($attrs['password'])) {
            $attrs['password'] = Hash::make($attrs['password']);
        }
        $data = $this->users->store($attrs);

        return response()->json($data, Response::HTTP_CREATED);
    }

    public function update(int $id, array $attrs)
    {
        if (!empty($attrs['password'])) {
            $attrs['password'] = Hash::make($attrs['password']);
        }
        $data = $this->users->updateById($id, $attrs);

        return response()->json($data, Response::HTTP_OK);
    }

    public function delete(int $id)
    {
        $this->users->deleteById($id);

        return response()->json(null, Response::HTTP_NO_CONTENT);
    }
}
