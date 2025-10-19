<?php

namespace Monobill\MonobillPhpSdk\Validation;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Monobill\MonobillPhpSdk\MonoBillAPI;

class Exists implements ValidationRule
{

    public function __construct(
        public string $validationObject,
        private MonoBillAPI $api,
        private string|null $message = null
    )
    {}

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $response = $this->api->post('/store/' . $this->api->storeId . '/validate/' . $this->validationObject . '/exists', ['value' => $value]);

        if(!isset($response['exists']) || !$response['exists']) {
            $fail($this->message ?? 'This doesn\'t exist.');
        }

    }
}