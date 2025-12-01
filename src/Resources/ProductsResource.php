<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

class ProductsResource extends StoreResource
{
    public function __construct(MonoBillAPI $api)
    {
        parent::__construct($api, '/store/' . $api->storeId . '/products');
    }

    public function __invoke(string|int|null $productId = null): static|array
    {
        // Allow $api->products($id)
        if ($productId) {
            $this->path = $this->buildPath((string)$productId);
            return $this;
        }
        return $this->all();
    }

    public function metaFields(): MetaFieldsResource
    {
        return new MetaFieldsResource($this->api, $this->buildPath('meta-fields'));
    }
}
