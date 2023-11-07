<?php

namespace Tests\Stubs\Models;

use App\Models\Model as AppModel;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Model extends AppModel
{
    protected $table = "model";

    protected $fillable = [
        'name',
    ];

    protected $auditInclude = [
        'name',
    ];

    public static function makeTable()
    {
        $createSoftDeletes = self::usingDeletedAtTrait();

        Schema::create('model', function (Blueprint $table) use ($createSoftDeletes) {
            $table->uuid('id');
            $table->string('name');
            $table->timestamps();

            if ($createSoftDeletes)
                $table->softDeletes();
        });
    }

    public static function dropTable()
    {
        Schema::dropIfExists('model');
    }

    public static function usingDeletedAtTrait()
    {
        $classUses = class_uses_recursive(self::class);

        return in_array(
            needle: \Illuminate\Database\Eloquent\SoftDeletes::class,
            haystack: $classUses,
        );
    }
}
