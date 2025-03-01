<?php

namespace App\Repositories;

interface IRepo
{

    /**
     * @return object
     */
    public function model(): object;

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object;

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool;

    /**
     * @param array $clause
     * @return mixed
     */
    public function deleteByClause(array $clause): bool;

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool;


    /**
     * @param array $clause
     * @param array $data
     * @return bool
     */
    public function updateByClause(array $clause, array $data): bool;

    /**
     * @param int $id
     * @return object
     */
    public function edit(int $id): object;

    /**
     * @param array $clause
     * @return mixed
     */
    public function findByClause(array $clause);

    /**
     * @param int $id
     */
    public function find(int $id);

}
