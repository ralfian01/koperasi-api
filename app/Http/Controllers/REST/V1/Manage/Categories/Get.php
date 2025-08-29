<?php

namespace App\Http\Controllers\REST\V1\Manage\Categories;

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
     */
    protected $payloadRules = [
        'id' => 'nullable|integer|exists:categories,id',
        'keyword' => 'nullable|string|min:3',
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
        return $this->get();
    }

    public function get()
    {
        $dbRepo = new DBRepo($this->payload, $this->file, $this->auth);
        $result = $dbRepo->getData();

        if ($result->status) {
            return $this->respond(200, $result->data);
        }

        if (isset($this->payload['id'])) {
            return $this->error((new Errors)->setMessage(404, 'Category not found.'));
        }

        return $this->respond(200, null);
    }
}
