<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

class NotificationsResource extends StoreResource
{
    public function __construct(MonoBillAPI $api)
    {
        parent::__construct($api, '/store/' . $api->storeId . '/notifications');
    }

    /**
     * Access email notification actions.
     */
    public function email(): NotificationsEmailResource
    {
        return new NotificationsEmailResource($this->api, $this->buildPath('email'));
    }
}





