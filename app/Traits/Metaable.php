<?php
namespace App\Traits;

trait Metaable {

    public function getMeta($key)
    {
        return $this->meta->where('key', $key)->first()->value ?? null;
    }

    public function getAllMeta() {
        return $this->meta->pluck('value', 'key')->toArray();
    }

    public function setMeta($key, $value)
    {
        $meta = $this->meta->where('key', $key)->first();
        if ($meta) {
            $meta->value = $value ?? '';
            $meta->save();
        } else {
            $this->meta()->create(['key' => $key, 'value' => $value ?? '']);
        }
    }

    public function setMetaSync($data)
    {
        foreach ($data as $key => $value) {
            $this->setMeta($key, $value);
        }
    }

}