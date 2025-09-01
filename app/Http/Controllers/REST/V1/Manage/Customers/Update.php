<?php

namespace App\Http\Controllers\REST\V1\Manage\Business;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;

class Update extends BaseREST
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
     * Aturan 'required' dihapus dari field body agar klien bisa mengirim update parsial.
     */
    protected $payloadRules = [
        // Validasi untuk parameter dari URI (tetap required)
        'id' => 'required|integer|exists:business,id',

        // Validasi untuk data dari body request (opsional)
        'name' => 'string|max:100', // 'required' dihapus
        'email' => 'nullable|email',
        'contact' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
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

    /**
     * Handle the next step of payload validation.
     * @return void
     */
    private function nextValidation()
    {
        // --- PERUBAHAN KRUSIAL DI SINI ---
        // Hanya lakukan validasi unik jika field 'email' dikirim oleh klien.
        if (array_key_exists('email', $this->payload)) {
            // Panggil DBRepo untuk melakukan pengecekan unik secara manual
            if (!DBRepo::isEmailUniqueOnUpdate($this->payload['email'], $this->payload['id'])) {
                return $this->error(
                    (new Errors)
                        ->setMessage(409, 'The email has already been taken.')
                        ->setReportId('MBU1')
                );
            }
        }
        // ------------------------------------

        // Jika tidak ada masalah, lanjutkan ke proses update
        return $this->update();
    }

    /** 
     * Function to update data 
     * @return object
     */
    public function update()
    {
        $dbRepo = new DBRepo($this->payload, $this->file, $this->auth);
        $update = $dbRepo->updateData();

        if ($update->status) {
            return $this->respond(200);
        }

        return $this->error(500, ['reason' => $update->message]);
    }
}
