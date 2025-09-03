<?php

namespace App\Http\Controllers\REST\V1\Manage\ProductCategories;

use App\Http\Controllers\REST\BaseREST;
use App\Http\Controllers\REST\Errors;
use App\Http\Controllers\REST\V1\Manage\ProductCategories\DBRepo;

class Update extends BaseREST
{
    public function __construct(
        ?array $payload = [],
        ?array $file = [],
        ?array $auth = []
    ) {
        $this->payload = $payload;
        $this->file = $file;
        $this->auth = $auth;
        return $this;
    }

    protected $payloadRules = [
        'id' => 'required|integer|exists:product_categories,id',
        'business_id' => 'integer|exists:business,id',
        'outlet_id' => 'integer|exists:outlets,id',
        'name' => 'string|max:100',
    ];

    protected $privilegeRules = [];
    protected function mainActivity()
    {
        return $this->nextValidation();
    }

    private function nextValidation()
    {
        if (array_key_exists('name', $this->payload)) {
            $outletId = $this->payload['outlet_id'] ?? DBRepo::findOutletId($this->payload['id']);
            if (!DBRepo::isNameUniqueOnUpdate($this->payload['name'], $outletId, $this->payload['id'])) {
                return $this->error((new Errors)->setMessage(409, 'The category name has already been taken for this outlet.'));
            }
        }
        return $this->update();
    }

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
