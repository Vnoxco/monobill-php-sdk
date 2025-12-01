<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

class AdministratorsResource extends StoreResource
{
    public function __construct(MonoBillAPI $api)
    {
        parent::__construct($api, '/store/' . $api->storeId . '/administrators');
    }

    /**
     * Retrieve the store owner (special endpoint)
     */
    public function owner(): array
    {
        return $this->api->get($this->buildPath('owner'));
    }
}
