<?php

namespace Monobill\MonobillPhpSdk\Objects;

class Webhook
{
    public string $event;
    public string $endpoint;
    public string $format;

    public function __construct(string $event, string $endpoint, string $format = 'json')
    {
        $this->event = $event;
        $this->endpoint = $endpoint;
        $this->format = $format;
    }

    public function toArray(): array
    {
        return [
            'event' => $this->event,
            'endpoint' => $this->endpoint,
            'format' => $this->format,
        ];
    }
}
