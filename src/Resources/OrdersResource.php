<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

class OrdersResource extends StoreResource
{
    public function __construct(MonoBillAPI $api)
    {
        parent::__construct($api, '/store/' . $api->storeId . '/orders');
    }

    public function __invoke(string|int|null $orderId = null): static|array
    {
        // Allow $api->orders($id)
        if ($orderId) {
            $this->path = $this->buildPath((string)$orderId);
            return $this;
        }
        return $this->all();
    }

    /**
     * Cancel an order
     *
     * @param array $data Optional cancellation data (reason, etc.)
     * @return array
     */
    public function cancel(array $data = []): array
    {
        return $this->api->post($this->buildPath('cancel'), $data);
    }

    /**
     * Update order status
     *
     * @param string $status
     * @param array $data Optional additional data
     * @return array
     */
    public function updateStatus(string $status, array $data = []): array
    {
        $payload = array_merge(['status' => $status], $data);
        return $this->api->put($this->buildPath('status'), $payload);
    }

    /**
     * Update customer for an order
     *
     * @param string|int $customerId
     * @param array $data Optional additional data
     * @return array
     */
    public function updateCustomer(string|int $customerId, array $data = []): array
    {
        $payload = array_merge(['customer_id' => $customerId], $data);
        return $this->api->put($this->buildPath('customer'), $payload);
    }

    /**
     * Get order items resource
     *
     * @return OrderItemsResource
     */
    public function items(): OrderItemsResource
    {
        return new OrderItemsResource($this->api, $this->buildPath('items'));
    }
}

