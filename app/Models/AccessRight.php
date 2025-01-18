<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AccessRight extends Model
{
    protected $fillable = [
        'owner',
        'user_id',
        'file_id',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function file() {
        return $this->belongsTo(File::class);
    }
}
