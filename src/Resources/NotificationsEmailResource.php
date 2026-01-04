<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

class NotificationsEmailResource extends StoreResource
{
    public function __construct(MonoBillAPI $api, string $path)
    {
        parent::__construct($api, $path);
    }

    /**
     * Trigger an email notification by name or ID.
     *
     * @param string $notificationNameId Notification identifier or slug.
     * @param string $toEmail Recipient email address.
     * @param array $data Key/value payload injected into the notification template.
     * @param array $extra Optional overrides (e.g. cc, bcc).
     * @return array
     */
    public function send(string $notificationNameId, string $toEmail, array $data = [], array $extra = []): array
    {
        $payload = array_merge([
            'notification_name_id' => $notificationNameId,
            'to' => $toEmail,
            'data' => $data,
        ], $extra);

        return $this->api->post($this->buildPath('send'), $payload);
    }
}





