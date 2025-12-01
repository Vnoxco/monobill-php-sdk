<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

class OrderItemsResource extends StoreResource
{
    public function __construct(MonoBillAPI $api, string $path)
    {
        parent::__construct($api, $path);
    }

    public function __invoke(string|int|null $itemId = null): static|array
    {
        // Allow $api->orders($orderId)->items($itemId)
        if ($itemId) {
            $this->path = $this->buildPath((string)$itemId);
            return $this;
        }
        return $this->all();
    }

    /**
     * Update an order item
     *
     * @param array $data Data to update (e.g., meta_data)
     * @return array
     */
    public function update(string|int|null $itemId = null, array $data = []): array
    {
        $endpoint = $itemId ? $this->buildPath((string)$itemId) : $this->path;
        return $this->api->put($endpoint, $data);
    }
}

