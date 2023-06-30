<?php

namespace App\Interfaces;

interface BaseRepositoryInterface
{
    /**
     * Get all
     * @return mixed
     */
    public function getAll($attrs);

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function getById(int $id);

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function store(array $attrs);

    /**
     * Update by id
     * @param $id
     * @param array $attributes
     * @return mixed
     */
    public function updateById(int $id, array $attrs);

    /**
     * Delete by id
     * @param $id
     * @return mixed
     */
    public function deleteById(int $id);
}