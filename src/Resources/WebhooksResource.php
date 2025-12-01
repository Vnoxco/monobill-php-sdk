<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;
use Monobill\MonobillPhpSdk\Objects\Webhook;

class WebhooksResource extends StoreResource
{
    public function __construct(MonoBillAPI $api)
    {
        parent::__construct($api, '/store/' . $api->storeId . '/webhooks');
    }

    /**
     * Create one or many webhooks
     *
     * @param array<int, Webhook> $webhooks
     * @return array
     */
    public function create(array $webhooks): array
    {
        // Convert all Webhook objects to arrays
        $payload = array_map(fn(Webhook $w) => $w->toArray(), $webhooks);

        return $this->api->post($this->path, $payload);
    }

    /**
     * Delete a webhook by ID
     */
    public function destroy(int|string $id = null): mixed
    {
        return parent::destroy($id);
    }

    /**
     * List all webhooks
     */
    public function all(): array
    {
        return $this->api->get($this->path);
    }
}
