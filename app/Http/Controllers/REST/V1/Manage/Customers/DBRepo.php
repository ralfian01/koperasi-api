<?php

namespace App\Http\Controllers\REST\V1\Manage\Customers;

use App\Http\Libraries\BaseDBRepo;
use App\Models\Customer;
use Exception;
use Illuminate\Support\Facades\DB;

class DBRepo extends BaseDBRepo
{
    /*
     * =================================================================================
     * METHOD UNTUK MENGAMBIL DATA (GET)
     * =================================================================================
     */
    public function getData()
    {
        try {
            // --- PERUBAHAN KRUSIAL DI SINI ---
            $query = Customer::query()
                ->with([
                    // Hanya pilih kolom 'id' dan 'name' dari relasi 'business'
                    'business' => function ($query) {
                        $query->select('id', 'name');
                    },
                    // Hanya pilih kolom 'id' dan 'name' dari relasi 'category'
                    'category' => function ($query) {
                        $query->select('id', 'name');
                    }
                ]);
            // ------------------------------------

            if (isset($this->payload['id'])) {
                $data = $query->find($this->payload['id']);
                return (object) ['status' => !is_null($data), 'data' => $data ? $data->toArray() : null];
            }

            // Terapkan filter
            if (isset($this->payload['business_id'])) {
                $query->where('business_id', $this->payload['business_id']);
            }
            if (isset($this->payload['customer_category_id'])) {
                $query->where('customer_category_id', $this->payload['customer_category_id']);
            }
            if (isset($this->payload['keyword'])) {
                $keyword = $this->payload['keyword'];
                $query->where(function ($subQuery) use ($keyword) {
                    $subQuery->where('name', 'LIKE', "%{$keyword}%")
                        ->orWhere('phone_number', 'LIKE', "%{$keyword}%")
                        ->orWhere('email', 'LIKE', "%{$keyword}%");
                });
            }

            $perPage = $this->payload['per_page'] ?? 15;
            $data = $query->paginate($perPage);

            return (object) ['status' => true, 'data' => $data->toArray()];
        } catch (Exception $e) {
            return (object) ['status' => false, 'message' => $e->getMessage()];
        }
    }


    /*
     * =================================================================================
     * METHOD UNTUK MENAMBAH DATA (INSERT)
     * =================================================================================
     */
    public function insertData()
    {
        try {
            return DB::transaction(function () {
                $customer = Customer::create($this->payload);

                if (!$customer) {
                    throw new Exception("Failed to create a new customer.");
                }

                return (object) ['status' => true, 'data' => (object) ['id' => $customer->id]];
            });
        } catch (Exception $e) {
            return (object) ['status' => false, 'message' => $e->getMessage()];
        }
    }

    /*
     * =================================================================================
     * METHOD STATIC/TOOLS
     * =================================================================================
     */
    public static function isPhoneNumberUniqueInBusiness(string $phoneNumber, int $businessId): bool
    {
        return !Customer::where('phone_number', $phoneNumber)
            ->where('business_id', $businessId)
            ->exists();
    }

    public static function isEmailUniqueInBusiness(?string $email, int $businessId): bool
    {
        if (is_null($email)) return true;
        return !Customer::where('email', $email)
            ->where('business_id', $businessId)
            ->exists();
    }
}
