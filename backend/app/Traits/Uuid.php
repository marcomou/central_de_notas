<?php

namespace App\Traits;

use Ramsey\Uuid\Uuid as Guid;

trait Uuid
{
    /**
     * Get the value indicating whether the IDs are incrementing.
     *
     * @return bool
     */
    public function getIncrementing()
    {
        return false;
    }

    /**
     * Get the auto-incrementing key type.
     *
     * @return string
     */
    public function getKeyType()
    {
        return 'string';
    }

    public static function bootUuid()
    {
        static::creating(function ($model) {
            $model->id = Guid::uuid4()->toString();
        });
    }
}
