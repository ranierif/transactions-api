<?php

namespace App\Repositories\Base;

use App\Repositories\Base\Contracts\BaseRepositoryContract;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepositoryEloquent implements BaseRepositoryContract
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * {@inheritDoc}
     */
    public function findBy(string $attribute, $value): Model
    {
        return $this->model::where($attribute, $value)->firstOrFail();
    }

    /**
     * {@inheritDoc}
     */
    public function delete(Model|int $model): ?bool
    {
        if ($model instanceof Model) {
            return $model->delete();
        }

        return $this->model::findOrFail($model)->delete();
    }

    /**
     * {@inheritDoc}
     */
    public function store(array $data): Model
    {
        return $this->model::create($data);
    }

    /**
     * {@inheritDoc}
     */
    public function update(int|Model $model, array $attributes = [], array $options = []): bool
    {
        if ($model instanceof Model) {
            return $model->update($attributes, $options);
        }

        return $this->model::query()
            ->whereKey($model)
            ->update($attributes, $options);
    }

    /**
     * {@inheritDoc}
     */
    public function exists(string $attribute, $value): bool
    {
        return $this->model::where($attribute, $value)->exists();
    }
}
