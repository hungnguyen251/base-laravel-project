<?php

namespace App\Repositories;

use App\Interfaces\BaseRepositoryInterface;
use App\Models\User;
use App\Transformers\UserTransformer;
use Exception;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Log;

class UserRepository implements BaseRepositoryInterface
{
    protected $users;

    public function __construct(User $users) 
    {
        $this->users = $users;
    }

    public function getAll($attrs)
    {
        $data = $this->users->filter($attrs)->paginate(Config::get('app.limit_results'));

        return fractal($data, new UserTransformer())->toArray();
    }

    public function getById(int $id)
    {
        $data = $this->users->findOrFail($id);

        return fractal($data, new UserTransformer())->toArray();
    }

    public function store(array $attrs)
    {
        try {
            $data = $this->users->create($attrs);

            return fractal($data, new UserTransformer())->toArray();
        } catch (Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    public function updateById(int $id, $attrs)
    {
        try {
            $data = $this->users->findOrFail($id);
            $data->fill($attrs);

            if (!$data) {
                throw new Exception('User does not exist');
            }
    
            $data->save();

            return fractal($data, new UserTransformer())->toArray();
        } catch (Exception $e) {
            Log::error($e);
            return $e->getMessage();
        }
    }

    public function deleteById(int $id)
    {
        $data = $this->users->findOrFail($id);

        return (bool) $data->delete();
    }
}