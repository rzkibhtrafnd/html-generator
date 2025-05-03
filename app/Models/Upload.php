<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Upload extends Model
{
    use HasFactory;

    protected $fillable = [
        'excel_file',
        'template_file',
        'generated_count',
        'zip_path',
        'preview_path'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function getZipUrlAttribute()
    {
        return asset('storage/' . $this->zip_path);
    }
}