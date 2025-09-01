<?php

namespace App\Http\Controllers\REST\V1\Manage\Business;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;

class Delete extends BaseREST
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
     * Kita hanya perlu memvalidasi parameter 'id' dari URI.
     */
    protected $payloadRules = [
        'id' => 'required|integer|exists:business,id',
    ];

    protected $privilegeRules = [];

    protected function mainActivity()
    {
        return $this->nextValidation();
    }

    private function nextValidation()
    {
        // Aturan 'exists' sudah memastikan ID valid, jadi kita bisa langsung lanjut.
        return $this->delete();
    }

    /** 
     * Function to delete data 
     * @return object
     */
    public function delete()
    {
        $dbRepo = new DBRepo($this->payload, $this->file, $this->auth);
        $delete = $dbRepo->deleteData();

        if ($delete->status) {
            // HTTP 200 OK atau 204 No Content adalah respons yang baik untuk delete.
            // Kita gunakan 200 untuk konsistensi.
            return $this->respond(200);
        }

        return $this->error(500, ['reason' => $delete->message]);
    }
}
