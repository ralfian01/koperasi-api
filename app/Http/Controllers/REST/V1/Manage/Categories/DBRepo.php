<?php

namespace App\Http\Controllers\REST\V1\Manage\Categories;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Category;
use Exception;
use Illuminate\Support\Facades\DB;

class DBRepo extends BaseDBRepo
{
    public function getData()
    {
        try {
            // Menggunakan withCount untuk efisiensi, agar tidak me-load semua produk
            $query = Category::query()->withCount('products');

            if (isset($this->payload['id'])) {
                $data = $query->find($this->payload['id']);
                return (object) ['status' => !is_null($data), 'data' => $data ? $data->toArray() : null];
            }

            if (isset($this->payload['keyword'])) {
                $query->where('name', 'LIKE', "%{$this->payload['keyword']}%");
            }

            $perPage = $this->payload['per_page'] ?? 15;
            $data = $query->paginate($perPage);

            return (object) ['status' => true, 'data' => $data->toArray()];
        } catch (Exception $e) {
            return (object) ['status' => false, 'message' => $e->getMessage()];
        }
    }


    public function insertData()
    {
        try {
            return DB::transaction(function () {
                $category = Category::create($this->payload);

                if (!$category) {
                    throw new Exception("Failed to create a new category.");
                }

                return (object) [
                    'status' => true,
                    'data' => (object) ['id' => $category->id]
                ];
            });
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
