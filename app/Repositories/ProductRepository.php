<?php

namespace App\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Models\Product; // Assumption: You have a Model with the same name

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(int $perPage = 10): LengthAwarePaginator
    {
        return Product::paginate($perPage);
    }

    public function getBySlug($slug)
    {
        return Product::query()->where('slug', $slug)->first();

    }

    public function getById($id)
    {
        return Product::find($id);
    }

    public function create(array $attributes)
    {
        return Product::create($attributes);
    }

    public function update($id, array $attributes)
    {
        $record = Product::find($id);
        if ($record) {
            $record->update($attributes);
            return $record;
        }
        return null;
    }

    public function delete($id)
    {
        $record = Product::find($id);
        if ($record) {
            return $record->delete();
        }
        return false;
    }
}
