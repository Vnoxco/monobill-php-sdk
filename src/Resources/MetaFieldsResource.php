<?php

namespace Monobill\MonobillPhpSdk\Resources;

use Monobill\MonobillPhpSdk\MonoBillAPI;

class MetaFieldsResource extends StoreResource
{
    public function __construct(MonoBillAPI $api, string $path)
    {
        parent::__construct($api, $path);
    }

    public function update(int|string|null|array $id = null, array $data = []): mixed
    {
        if(is_array($id)) {
            $data = $id;
        }

        return $this->api->put($this->path, [
            'meta_fields' => $data,
        ]);
    }

    public function destroy(string|int|null $id = null): mixed
    {
        return $this->api->delete($this->path);
    }
}
