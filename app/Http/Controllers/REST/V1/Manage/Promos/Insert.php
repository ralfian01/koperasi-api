<?php

namespace App\Http\Controllers\REST\V1\Manage\Promos;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;

class Insert extends BaseREST
{
    public function __construct(?array $payload = [], ?array $file = [], ?array $auth = [])
    {
        $this->payload = $payload;
        $this->file = $file;
        $this->auth = $auth;
        return $this;
    }

    /**
     * @var array Property that contains the payload rules.
     * --- CONTOH PAYLOAD PROMO LENGKAP ---
     * {
     *     "business_id": 1,
     *     "name": "Promo Akhir Pekan Spesial",
     *     "promo_type": "DISCOUNT_AND_FREE_ITEM",
     *     "start_date": "2025-10-01",
     *     "end_date": "2025-10-31",
     *     "is_cumulative": false,
     *     "discount_type": "PERCENTAGE",
     *     "discount_value": 15,
     *     "outlet_ids": [1, 2],
     *     "schedules": [
     *         { "day_of_week": 5, "start_time": "10:00", "end_time": "22:00" },
     *         { "day_of_week": 6, "start_time": "10:00", "end_time": "23:00" }
     *     ],
     *     "conditions": [
     *         { "condition_type": "CATEGORY", "target_id": 1, "min_quantity": 2 },
     *         { "condition_type": "PRODUCT", "target_id": 5, "min_quantity": 1 }
     *     ],
     *     "free_items": [
     *         { "product_id": 10, "quantity": 1 }
     *     ]
     * }
     */
    protected $payloadRules = [
        // Info Utama
        'business_id' => 'required|integer|exists:business,id',
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'promo_type' => 'required|string|in:DISCOUNT,FREE_ITEM,DISCOUNT_AND_FREE_ITEM',
        'start_date' => 'required|date_format:Y-m-d',
        'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        'is_cumulative' => 'nullable|boolean',

        // Info Diskon (Kondisional)
        'discount_type' => 'required_if:promo_type,DISCOUNT,DISCOUNT_AND_FREE_ITEM|string|in:PERCENTAGE,FIXED',
        'discount_value' => 'required_if:promo_type,DISCOUNT,DISCOUNT_AND_FREE_ITEM|numeric|min:0',

        // Info Item Gratis (Kondisional)
        'free_items' => 'required_if:promo_type,FREE_ITEM,DISCOUNT_AND_FREE_ITEM|array|min:1',
        'free_items.*.product_id' => 'required_with:free_items|integer|exists:products,product_id',
        'free_items.*.quantity' => 'required_with:free_items|integer|min:1',

        // Info Syarat (Wajib)
        'conditions' => 'required|array|min:1',
        'conditions.*.condition_type' => 'required_with:conditions|string|in:PRODUCT,CATEGORY',
        'conditions.*.target_id' => 'required_with:conditions|integer',
        'conditions.*.min_quantity' => 'required_with:conditions|integer|min:1',

        // Info Jadwal (Wajib)
        'schedules' => 'required|array|min:1',
        'schedules.*.day_of_week' => 'required_with:schedules|integer|between:0,6',
        'schedules.*.start_time' => 'required_with:schedules|date_format:H:i',
        'schedules.*.end_time' => 'required_with:schedules|date_format:H:i|after:schedules.*.start_time',

        // Info Outlet (Wajib)
        'outlet_ids' => 'required|array|min:1',
        'outlet_ids.*' => 'required|integer|exists:outlets,id',
    ];

    protected $privilegeRules = [];

    protected function mainActivity()
    {
        return $this->nextValidation();
    }

    private function nextValidation()
    {
        // Validasi 'target_id' di conditions yang tidak bisa divalidasi di $payloadRules
        if (!DBRepo::validateConditions($this->payload['conditions'])) {
            return $this->error(
                (new Errors)
                    ->setMessage(422, 'One or more target_id in conditions is invalid.')
                    ->setReportId('MPRI1') // Manage Promo Insert 1
            );
        }
        return $this->insert();
    }

    public function insert()
    {
        $dbRepo = new DBRepo($this->payload, $this->file, $this->auth);
        $insert = $dbRepo->insertData();

        if ($insert->status) {
            return $this->respond(201, ['id' => $insert->data->id]);
        }

        return $this->error(500, ['reason' => $insert->message]);
    }
}
