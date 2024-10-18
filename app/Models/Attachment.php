<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class Attachment extends Model
{
    use HasFactory;
    protected $fillable = [
        'file_name',
        'file_path',
        'mime_type',
    ];

    public function attachable()
    {
        return $this->morphTo();
    }
}
