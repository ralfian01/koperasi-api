<?php

namespace App\Http\Controllers\REST\V1\Manage\Taxes;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;
use App\Http\Controllers\REST\V1\Manage\Taxes\DBRepo;

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

    protected $payloadRules = [
        'outlet_id' => 'required|integer|exists:outlets,id',
        'name' => 'required|string|max:100',
        'rate' => 'required|numeric|min:0',
        'type' => 'required|string|in:PERCENTAGE,FIXED',
        'description' => 'nullable|string',
        'is_active' => 'nullable|boolean',
    ];

    protected $privilegeRules = [];
    protected function mainActivity()
    {
        return $this->nextValidation();
    }

    private function nextValidation()
    {
        if (!DBRepo::isNameUniqueInOutlet($this->payload['name'], $this->payload['outlet_id'])) {
            return $this->error((new Errors)->setMessage(409, 'The tax name has already been taken for this outlet.'));
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
