<?php

namespace App\Http\Controllers\REST\V1\Manage\Promos;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Category;
use App\Models\Product;
use App\Models\Promo;
use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class DBRepo extends BaseDBRepo
{
    /*
     * =================================================================================
     * METHOD UNTUK MENGAMBIL DATA (GET)
     * =================================================================================
     */

    /**
     * Fungsi utama untuk mengambil data promo berdasarkan filter.
     * @return object
     */
    public function getData()
    {
        try {
            $query = Promo::query()
                // Eager loading adalah KUNCI untuk mendapatkan semua data terkait promo
                ->with([
                    'business',
                    'outlets',      // Outlet tempat promo ini berlaku
                    'schedules',    // Jadwal harian promo
                    'conditions',   // Syarat-syarat pemicu promo
                    'freeItems.product' // Item gratis beserta detail produknya
                ]);

            // Kasus 1: Mengambil satu promo spesifik berdasarkan ID
            if (isset($this->payload['id'])) {
                $data = $query->find($this->payload['id']);
                return (object) [
                    'status' => !is_null($data),
                    'data' => $data ? $data->toArray() : null
                ];
            }

            // Kasus 2: Mengambil daftar promo dengan filter
            $this->applyFilters($query);

            $perPage = $this->payload['per_page'] ?? 15;
            $data = $query->orderBy('id', 'desc')->paginate($perPage);

            return (object) [
                'status' => true,
                'data' => $data->toArray()
            ];
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /**
     * Method pendukung untuk menerapkan filter pada query GET.
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    private function applyFilters(&$query)
    {
        if (isset($this->payload['business_id'])) {
            $query->where('business_id', $this->payload['business_id']);
        }
        if (isset($this->payload['outlet_id'])) {
            // Filter promo yang ter-assign ke outlet spesifik
            $query->whereHas('outlets', function ($q) {
                $q->where('outlets.id', $this->payload['outlet_id']);
            });
        }
        if (isset($this->payload['promo_type'])) {
            $query->where('promo_type', $this->payload['promo_type']);
        }
        if (isset($this->payload['is_active'])) {
            $query->where('is_active', $this->payload['is_active']);
        }
        if (isset($this->payload['keyword'])) {
            $query->where('name', 'LIKE', "%{$this->payload['keyword']}%");
        }
    }

    /*
     * =================================================================================
     * METHOD UNTUK MENAMBAH DATA (INSERT)
     * =================================================================================
     */

    /**
     * Fungsi utama untuk memasukkan data promo baru.
     * @return object
     */
    public function insertData()
    {
        try {
            return DB::transaction(function () {
                $promoPayload = Arr::only($this->payload, [
                    'business_id',
                    'name',
                    'description',
                    'promo_type',
                    'start_date',
                    'end_date',
                    'is_active',
                    'is_cumulative',
                    'discount_type',
                    'discount_value'
                ]);
                $promo = Promo::create($promoPayload);

                if (!empty($this->payload['conditions'])) {
                    $promo->conditions()->createMany($this->payload['conditions']);
                }
                if (!empty($this->payload['schedules'])) {
                    $promo->schedules()->createMany($this->payload['schedules']);
                }
                if (in_array($this->payload['promo_type'], ['FREE_ITEM', 'DISCOUNT_AND_FREE_ITEM'])) {
                    if (!empty($this->payload['free_items'])) {
                        $promo->freeItems()->createMany($this->payload['free_items']);
                    }
                }
                if (!empty($this->payload['outlet_ids'])) {
                    $promo->outlets()->sync($this->payload['outlet_ids']);
                }

                return (object) [
                    'status' => true,
                    'data' => (object) ['id' => $promo->id]
                ];
            });
        } catch (Exception $e) {
            return (object) [
                'status' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    /*
     * =================================================================================
     * METHOD STATIC/TOOLS
     * =================================================================================
     */

    /**
     * Method pendukung (static) untuk memvalidasi 'target_id' di dalam conditions.
     * Digunakan oleh `Insert.php` di `nextValidation()`.
     */
    public static function validateConditions(array $conditions): bool
    {
        foreach ($conditions as $condition) {
            $exists = false;
            if ($condition['condition_type'] === 'PRODUCT') {
                $exists = Product::where('product_id', $condition['target_id'])->exists();
            } elseif ($condition['condition_type'] === 'CATEGORY') {
                $exists = Category::where('id', $condition['target_id'])->exists();
            }
            if (!$exists) return false; // Jika satu saja tidak valid, langsung gagal
        }
        return true;
    }
}
