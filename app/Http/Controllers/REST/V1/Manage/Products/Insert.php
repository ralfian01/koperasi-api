<?php

namespace App\Http\Controllers\REST\V1\Manage\Products;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;

class Insert extends BaseREST
{
    public function __construct(
        ?array $payload = [],
        ?array $file = [],
        ?array $auth = [],
    ) {
        $this->payload = $payload;
        $this->file = $file;
        $this->auth = $auth;
        return $this;
    }

    /**
     * @var array Property that contains the payload rules.
     *
     * --- CONTOH PAYLOAD FINAL ---
     *
     * 1. JASA - TIME_SLOT (Sewa GOR dengan 3 Lapangan)
     * {
     *     "business_id": 1,
     *     "category_id": 1, // Bisa juga null atau tidak dikirim
     *     "outlet_ids": [1, 2],
     *     "name": "Sewa GOR Badminton Jaya",
     *     "booking_mechanism": "TIME_SLOT",
     *     "resources": [
     *         { "name": "Lapangan A" },
     *         { "name": "Lapangan B" }
     *     ],
     *     "availability": [
     *         { "day_of_week": 1, "start_time": "08:00", "end_time": "22:00" }
     *     ],
     *     "prices": [
     *         { "unit_id": 2, "price": 85000, "price_type": "REGULAR" }
     *     ]
     * }
     */
    protected $payloadRules = [
        // 1. Konteks Bisnis
        'business_id' => 'required|integer|exists:business,id',

        // --- PERUBAHAN DI SINI ---
        // 'required' diubah menjadi 'nullable'.
        // Jika dikirim, nilainya harus integer dan ada di tabel categories.
        // Jika tidak dikirim atau nilainya null, validasi akan lolos.
        'category_id' => 'nullable|integer|exists:categories,id',
        // ------------------------

        'outlet_ids' => 'required|array|min:1',
        'outlet_ids.*' => 'required|integer|exists:outlets,id',

        // 2. Informasi Produk Dasar
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'booking_mechanism' => 'required|in:INVENTORY_STOCK,TIME_SLOT,TIME_SLOT_CAPACITY,CONSUMABLE_STOCK',

        // 3. Aturan Kondisional (Tidak ada perubahan di bagian ini)
        'variants' => 'required_if:booking_mechanism,INVENTORY_STOCK|array|min:1',
        'variants.*.name' => 'required_with:variants|string|max:255',
        'variants.*.stock_quantity' => 'required_with:variants|integer|min:0',
        'variants.*.price' => 'required_with:variants|numeric|min:0',
        'variants.*.sku' => 'nullable|string|max:255|distinct',

        'resources' => 'required_if:booking_mechanism,TIME_SLOT,TIME_SLOT_CAPACITY|array|min:1',
        'resources.*.name' => 'required_with:resources|string|max:255',
        'resources.*.capacity' => 'required_if:booking_mechanism,TIME_SLOT_CAPACITY|integer|min:1',

        'availability' => 'required_if:booking_mechanism,TIME_SLOT,TIME_SLOT_CAPACITY|array|min:1',
        'availability.*.day_of_week' => 'required_with:availability|integer|between:0,6|distinct',
        'availability.*.start_time' => 'required_with:availability|date_format:H:i',
        'availability.*.end_time' => 'required_with:availability|date_format:H:i|after:availability.*.start_time',

        'service_stock' => 'required_if:booking_mechanism,CONSUMABLE_STOCK|array|min:1',
        'service_stock.*.unit_id' => 'required_with:service_stock|integer|exists:units,unit_id|distinct',
        'service_stock.*.name' => 'required_with:service_stock|string|max:255',
        'service_stock.*.available_quantity' => 'required_with:service_stock|integer|min:0',

        'prices' => 'required_if:booking_mechanism,TIME_SLOT,TIME_SLOT_CAPACITY,CONSUMABLE_STOCK|array|min:1',
        'prices.*.unit_id' => 'required_with:prices|integer|exists:units,unit_id',
        'prices.*.price' => 'required_with:prices|numeric|min:0',
        'prices.*.price_type' => 'nullable|string|in:REGULAR,MEMBER',
    ];

    // Sisa dari kelas ini tidak perlu diubah sama sekali
    protected $privilegeRules = [];

    protected function mainActivity()
    {
        return $this->nextValidation();
    }

    private function nextValidation()
    {
        $mechanism = $this->payload['booking_mechanism'];
        if (in_array($mechanism, ['TIME_SLOT', 'TIME_SLOT_CAPACITY', 'CONSUMABLE_STOCK'])) {
            $unitIds = array_column($this->payload['prices'], 'unit_id');
            if (!DBRepo::checkUnitsExist($unitIds)) {
                return $this->error(
                    (new Errors)
                        ->setMessage(409, 'One or more unit_id in prices are not valid.')
                        ->setReportId('MPI2')
                );
            }
        }
        return $this->insert();
    }

    public function insert()
    {
        $dbRepo = new DBRepo($this->payload, $this->file, $this->auth);
        $insert = $dbRepo->insertData();

        if ($insert->status) {
            return $this->respond(201, ['product_id' => $insert->data->product_id]);
        }

        return $this->error(500, ['reason' => $insert->message]);
    }
}
