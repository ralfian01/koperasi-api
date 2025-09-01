<?php

namespace App\Http\Controllers\REST\V1\Manage\Business;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;

class Insert extends BaseREST
{
    public function __construct(
        ?array $payload = [],
        ?array $file = [], // File yang di-upload akan masuk ke sini
        ?array $auth = [],
    ) {
        $this->payload = $payload;
        $this->file = $file;
        $this->auth = $auth;
        return $this;
    }

    /**
     * @var array Property that contains the payload rules
     */
    protected $payloadRules = [
        'name' => 'required|string|max:100',
        'email' => 'nullable|email|unique:business,email',
        'contact' => 'nullable|string|max:255',
        'description' => 'nullable|string',

        // --- PERUBAHAN DI SINI ---
        // 'logo' sekarang divalidasi sebagai file gambar
        // Batasan: harus gambar, tipe jpeg/png/jpg/gif, ukuran maks 2MB (2048 KB)
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        // ------------------------

        'website' => 'nullable|string',
        'instagram' => 'nullable|string',
        'tiktok' => 'nullable|string',
        'is_active' => 'nullable|boolean',
    ];

    protected $privilegeRules = [];

    protected function mainActivity()
    {
        return $this->nextValidation();
    }

    private function nextValidation()
    {
        return $this->insert();
    }

    public function insert()
    {
        // Perhatikan bahwa $this->file sekarang di-pass ke DBRepo
        $dbRepo = new DBRepo($this->payload, $this->file, $this->auth);
        $insert = $dbRepo->insertData();

        if ($insert->status) {
            return $this->respond(201, ['id' => $insert->data->id]);
        }

        return $this->error(500, ['reason' => $insert->message]);
    }
}
