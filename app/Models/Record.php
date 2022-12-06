<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Record extends Model
{
    protected $table = 'records';

    public $timestamps = true;

    protected $guarded = [
        'id',
    ];

    protected $fillable = [
        'name',
        'type',
        'description'
    ];

    public function file(): HasOne
    {
        return $this->hasOne(File::class, 'record_id');
    }
}
