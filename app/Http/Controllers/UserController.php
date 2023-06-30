<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $users;

    /**
     * Controller constructor.
     *
     * @param  \App\Services\UserService  $userService
     * @param  \App\  $users
     */
    public function __construct(UserService $userService)
    {
        $this->users = $userService;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return $this->users->getAll($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     */
    public function store(CreateUserRequest $request)
    {
        return $this->users->create($request->all());
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showById(int $id)
    {
        return $this->users->getById($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(int $id, UpdateUserRequest $request)
    {
        return $this->users->update($id, $request->all());
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->users->delete($id);
    }
}