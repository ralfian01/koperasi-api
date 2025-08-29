<?php

namespace App\Http\Controllers\REST\V1\Manage\Outlets;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Outlet;
use Exception;
use Illuminate\Support\Facades\DB;

class DBRepo extends BaseDBRepo
{
    public function getData()
    {
        try {
            $query = Outlet::query()->with('business'); // Eager load business

            if (isset($this->payload['id'])) {
                $data = $query->find($this->payload['id']);
                return (object) ['status' => !is_null($data), 'data' => $data ? $data->toArray() : null];
            }

            // Terapkan filter
            if (isset($this->payload['business_id'])) {
                $query->where('business_id', $this->payload['business_id']);
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
                $outlet = Outlet::create($this->payload);

                if (!$outlet) {
                    throw new Exception("Failed to create a new outlet.");
                }

                return (object) [
                    'status' => true,
                    'data' => (object) ['id' => $outlet->id]
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
