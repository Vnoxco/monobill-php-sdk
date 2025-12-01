<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

abstract class StoreResource
{
    protected MonoBillAPI $api;
    protected string $path;

    public function __construct(MonoBillAPI $api, string $path = '')
    {
        $this->api = $api;
        $this->path = trim($path, '/');
    }

    protected function buildPath(string $segment): string
    {
        return trim($this->path . '/' . $segment, '/');
    }

    public function all(): array
    {
        return $this->api->get($this->path);
    }

    public function get(string|int $id): array
    {
        return $this->api->get($this->buildPath((string)$id));
    }

    public function create(array $data): array
    {
        return $this->api->post($this->path, $data);
    }

    public function update(string|int|null $id, array $data): mixed
    {
        $endpoint = $id ? $this->buildPath((string)$id) : $this->path;
        return $this->api->put($endpoint, $data);
    }

    public function destroy(string|int|null $id = null): mixed
    {
        $endpoint = $id ? $this->buildPath((string)$id) : $this->path;
        return $this->api->delete($endpoint);
    }

    public function page(int $page, int $perPage = 20): array
    {
        return $this->api->get($this->path, ['page' => $page, 'per_page' => $perPage]);
    }
}
