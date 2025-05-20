<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'module_id',
        'title',
        'description',
        'file_path',
    ];

    /**
     * Get the user that owns the document.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the module that owns the document.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    /**
     * Get the URL of the document file.
     */
    public function getFileUrlAttribute()
    {
        return asset('storage/' . $this->file_path);
    }
}
