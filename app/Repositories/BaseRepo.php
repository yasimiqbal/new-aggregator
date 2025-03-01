<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

abstract class BaseRepo implements IRepo
{
    /**
     * @var object
     */
    protected $model;

    /**
     * BaseRepo constructor.
     * @param $model
     */
    public function __construct($model)
    {
        $this->model = $model;
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * @param array $where
     * @return mixed
     */
    public function latest(array $where = [])
    {
        return $this->model->where($where)->latest()->first();
    }

    /**
     * Count All Records
     *
     * @return mixed
     */
    public function count()
    {
        return $this->model->count();
    }

    /**
     * @param int $paginate
     *
     * @return mixed
     */
    public function paginate(int $paginate)
    {
        return $this->model->all();//paginate($paginate);
    }

    /**
     * @param array $data
     * @return object
     */
    public function create(array $data): object
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     *
     * @return mixed
     */
    public function insert(array $data)
    {
        return $this->model->insert($data);
    }

    /**
     * @param int $id
     * @return object
     */
    public function edit(int $id): object
    {
        return $this->model->where('id', $id);
    }

    /**
     * @param int $id
     * @return object
     */
    public function findOrFail(int $id): object
    {
        return $this->model->findOrFail($id);
    }

    /**
     * @param array $clause
     * @return mixed
     */
    public function findByClause(array $clause)
    {
        return $this->model->where($clause);
    }

    /**
     * @param int $id
     * @param array $data
     * @return bool
     */
    public function update(int $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    /**
     * @param array $clause
     * @param array $data
     * @return bool
     */
    public function updateByClause(array $clause, array $data): bool
    {
        return $this->model->where($clause)->update($data);
    }

    /**
     * @param array $ids
     * @param array $data
     * @return bool
     */
    public function updateMultipleRows(array $ids, array $data): bool
    {
        return $this->model->whereIn('id', $ids)->update($data);
    }

    /**
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    /**
     * @param array $clause
     * @return bool
     */
    public function deleteByClause(array $clause): bool
    {
        return $this->model->where($clause)->delete();
    }

    /**
     * @return object
     */
    public function model(): object
    {
        return $this->model;
    }

    /**
     * @return object
     */
    public function query(): object
    {
        return $this->model->query();
    }

    /**
     * @param $id
     * @return mixed
     */
    public function editTrashed($id)
    {
        return $this->model->onlyTrashed()->where('id', $id);
    }

    /**
     * WhereIn
     *
     * @param $clause
     * @return mixed
     */
    public function findbyClauseMultiple($clause)
    {
        return $this->model->whereIn('id', $clause);
    }


    /**
     * WhereIn
     *
     * @param $clause
     * @return mixed
     */
    public function findByClauseWith(array $with, array $clause)
    {
        return $this->model->with($with)->whereIn('id', $clause);
    }


    /**
     * @param $clause
     * @param array $where
     * @return mixed
     */
    public function findbyClauseMultipleWhere($clause, array $where)
    {
        return $this->model->whereIn('id', $clause)
            ->where($where);
    }

    /**
     * @param int $id
     * @return object
     */
    public function find(int $id)
    {
        return $this->model->find($id);
    }

    /**
     * @param $where
     * @param string $column
     * @param string $order
     * @return mixed
     */
    public function getAllWhere($where, string $column = 'created_at', string $order = 'desc')
    {
        return $this->model::where($where)->orderBy($column, $order)->get();
    }

    /**
     * @param array $with
     * @param array $where
     * @param string $column
     * @param string $order
     * @param int|null $take
     * @return mixed
     */
    public function getAllWithWhere(array $with, array $where, string $column = 'created_at', string $order = 'desc', int $take = null)
    {
        $query = $this->model::with($with)->where($where)->orderBy($column, $order);
        if (isset($take))
            $query->take($take);
        return $query->get();
    }

    /**
     * @param array $with
     * @param array $where
     * @param string $column
     * @param string $order
     * @return mixed
     */
    public function getAllWithWhereFirst(array $with, array $where, string $column = 'created_at', string $order = 'desc')
    {
        return $this->model::with($with)->where($where)->orderBy($column, $order)->first();
    }

    /**
     * @param array $with
     * @param $column_name
     * @param array $whereVal
     * @param array $where
     * @param string $column
     * @param string $order
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getAllWithWhereIn(array $with, $column_name, array $whereVal, array $where = [], string $column = 'created_at', string $order = 'desc')
    {
        return $this->model::with($with)->where($where)->whereIn($column_name, $whereVal)->orderBy($column, $order)->get();
    }

    /**
     * @param array $with
     * @param $column_name
     * @param $where
     * @return mixed
     */
    public function withWhere(array $with, $column_name, $where)
    {
        return $this->model::with($with)->where($column_name, $where);

    }

    /**
     * WhereIn
     *
     * @param $clause
     * @return mixed
     */
    public function findWhereIn($clause)
    {
        return $this->model->whereIn('id',$clause);
    }

    /**
     * @return mixed
     */
    public function truncate()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        $result = $this->model->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
        return $result;
    }

    /**
     * @param $condition
     * @param $where
     * @return mixed
     */
    public function findAllExcept(array $condition, $where)
    {
        return $this->model->whereNotIn($where, $condition)->get();
    }


    /**
     * WhereIn
     *
     * @param $clause
     * @return mixed
     */
    public function findByWith(array $with, array $clause)
    {
        return $this->model->with($with)->where($clause);
    }

    /**
     * @param array $clause
     * @return mixed
     */
    public function whereIn(array $ids)
    {
        return $this->model->whereIn('id', $ids);
    }

    /**
     * @param array $clause
     * @return mixed
     */
    public function whereCount(array $where)
    {
        return $this->model->where($where)->count();
    }

    /**
     * @param $with
     * @param $clause
     * @param $startIndex
     * @param $noOfPages
     * @return array
     */
    public function paginatedData($with, $clause, $startIndex, $noOfPages)
    {
        $data['count'] = $this->model->with($with)->where($clause)->count();
        $data['result'] = $this->model->with($with)->where($clause)->skip($startIndex)->take($noOfPages)->get();
        return $data;
    }

    /**
     * @param $column
     * @param array $clause
     * @return mixed
     */
    public function whereInColumn($column, array $clause)
    {
        return $this->model->whereIn($column, $clause);
    }


    /**
     * @param array $with
     * @param $condition
     * @param array $clause
     * @return mixed
     */
    public function findByMultipleClauseWith(array $with,$condition,array $clause)
    {
        return $this->model->with($with)->whereIn($condition,$clause);
    }

    /**
     * @param array $with
     * @param $column
     * @param array $clause
     * @return mixed
     */
    public function withNotWhere(array $with,$column,array $clause)
    {
        return $this->model->with($with)->whereNotIn($column,$clause);
    }

    /**
     * @param array $condition
     * @return mixed
     */
    public function findByMultipleWhereInClause(array $condition)
    {
        return $this->model->whereIn($condition);
    }

    /**
     * @param $data
     * @return mixed
     */
    public function insertGetId($data)
    {
        return $this->model->insertGetId($data);
    }

}
