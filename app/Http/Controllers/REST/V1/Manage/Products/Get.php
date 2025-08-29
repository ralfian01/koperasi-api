<?php

namespace App\Http\Controllers\REST\V1\Manage\Products;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;

class Get extends BaseREST
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
     * Semua parameter bersifat opsional (nullable).
     */
    protected $payloadRules = [
        'id' => 'nullable|integer|exists:products,product_id',

        // --- KUNCI PERUBAHAN ---
        // 'business_id' sebagai filter level atas (opsional)
        'business_id' => 'nullable|integer|exists:business,id',
        // ------------------------

        'keyword' => 'nullable|string|min:3',
        'booking_mechanism' => 'nullable|string|in:INVENTORY_STOCK,TIME_SLOT,TIME_SLOT_CAPACITY,CONSUMABLE_STOCK',
        'page' => 'nullable|integer|min:1',
        'per_page' => 'nullable|integer|min:1|max:100',
    ];

    protected $privilegeRules = [];

    protected function mainActivity()
    {
        return $this->nextValidation();
    }

    private function nextValidation()
    {
        // Tidak ada validasi lanjutan yang diperlukan,
        // karena `exists` sudah menangani cek ID.
        return $this->get();
    }

    /** 
     * Function to get data 
     * @return object
     */
    public function get()
    {
        $dbRepo = new DBRepo($this->payload, $this->file, $this->auth);
        $result = $dbRepo->getData();

        if ($result->status) {
            return $this->respond(200, $result->data);
        }

        // Jika ID spesifik diminta tapi tidak ditemukan, kembalikan 404
        if (isset($this->payload['id']) && !$result->status) {
            return $this->error(
                (new Errors)
                    ->setMessage(404, 'Product with the specified ID not found.')
                    ->setReportId('MPG1') // Manage Product Get 1
            );
        }

        // Untuk kasus lain (misal: daftar kosong), kembalikan data null
        return $this->respond(200, null);
    }
}
