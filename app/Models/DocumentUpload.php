<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DocumentUpload extends Model
{
    protected $fillable = [
        'name',
        'role',
        'document',
        'description',
        'created_by',
        'type',
        'notes'
    ];

    public function documentByType()
    {
        return $this->belongsTo(Document::class, 'type', 'id');
    }
}

