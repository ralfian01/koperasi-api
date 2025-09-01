<?php

namespace App\Http\Controllers\REST\V1\Manage\Business\Summary;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Business;
use Exception;

class DBRepo extends BaseDBRepo
{
    /**
     * Fungsi utama untuk mengambil data ringkasan bisnis.
     * @return object
     */
    public function getData()
    {
        try {
            $query = Business::query()
                ->withCount('outlets')
                ->with(['outlets' => function ($query) {
                    $query->select('id', 'business_id', 'name');
                }]);

            if (isset($this->payload['keyword'])) {
                $query->where('name', 'LIKE', "%{$this->payload['keyword']}%");
            }

            $perPage = $this->payload['per_page'] ?? 15;
            $paginatedResult = $query->paginate($perPage);

            // --- PERUBAHAN KRUSIAL DI SINI ---
            // Mengubah nama kunci (key) pada hasil transformasi.
            $paginatedResult->getCollection()->transform(function ($business) {
                return [
                    'id' => $business->id,                      // 'id_unit_bisnis' -> 'id'
                    'name' => $business->name,                  // 'nama_unit_bisnis' -> 'name'
                    'logo_url' => $business->logo_url,          // 'logo_unit_bisnis' -> 'logo_url' (sudah baik)
                    'outlets_count' => $business->outlets_count, // 'jumlah_outlet' -> 'outlets_count' (English)
                    'outlets' => $business->outlets,
                ];
            });
            // ------------------------------------

            return (object) [
                'status' => true,
                'data' => $paginatedResult->toArray(),
            ];
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }
}
