<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class File extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'extension',
        'size',
        'path',
    ];

    public function user() {
        return $this->belongsTo(Role::class);
    }

    public function accessRights() {
        return $this->hasMany(AccessRight::class);
    }
}
