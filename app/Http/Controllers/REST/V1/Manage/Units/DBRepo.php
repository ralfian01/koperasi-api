<?php

namespace App\Http\Controllers\REST\V1\Manage\Units;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Unit; // Pastikan model Unit di-import
use Exception;
use Illuminate\Support\Facades\DB;

class DBRepo extends BaseDBRepo
{
    /*
     * ---------------------------------------------
     * TOOLS
     * ---------------------------------------------
     */

    // Tidak ada tool method yang dibutuhkan untuk operasi insert sederhana ini.


    /*
     * ---------------------------------------------
     * DATABASE TRANSACTION
     * ---------------------------------------------
     */

    /**
     * Function to get unit data from the database.
     * @return object
     */
    public function getData()
    {
        try {
            // Kita bisa menggunakan withCount untuk melihat berapa banyak
            // skema harga yang menggunakan unit ini, ini bisa jadi info yang berguna.
            $query = Unit::query();

            // Kasus 1: Mengambil satu unit berdasarkan ID
            if (isset($this->payload['id'])) {
                $data = $query->find($this->payload['id']);
                return (object) [
                    'status' => !is_null($data),
                    'data' => $data ? $data->toArray() : null,
                ];
            }

            // Kasus 2: Mengambil daftar unit dengan filter
            if (isset($this->payload['keyword'])) {
                $query->where('name', 'LIKE', "%{$this->payload['keyword']}%");
            }

            // Paginasi
            $perPage = $this->payload['per_page'] ?? 15;
            $data = $query->paginate($perPage);

            return (object) [
                'status' => true,
                'data' => $data->toArray(),
            ];
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Function to insert a new unit into the database.
     * @return object
     */
    public function insertData()
    {
        try {
            // Meskipun hanya satu operasi, membungkusnya dalam transaction adalah praktik yang baik.
            $result = DB::transaction(function () {
                $dbPayload = [
                    'name' => $this->payload['name'],
                    'description' => $this->payload['description'] ?? null,
                ];

                $unit = Unit::create($dbPayload);

                if (!$unit) {
                    throw new Exception("Failed to create a new unit.");
                }

                // Mengembalikan status sukses beserta ID dari data yang baru dibuat
                return (object) [
                    'status' => true,
                    'data' => (object) [
                        'unit_id' => $unit->unit_id
                    ]
                ];
            });

            return $result;
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
