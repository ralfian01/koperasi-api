<?php

namespace App\Http\Controllers\REST\V1\Manage\Business;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Business;
use Exception;
use Illuminate\Support\Facades\DB;

class DBRepo extends BaseDBRepo
{

    public function getData()
    {
        try {
            $query = Business::query()->with('outlets'); // Eager load outlets

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
                $business = Business::create($this->payload);

                if (!$business) {
                    throw new Exception("Failed to create a new business unit.");
                }

                return (object) [
                    'status' => true,
                    'data' => (object) ['id' => $business->id]
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
