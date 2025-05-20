<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecordClass extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'module_id',
        'title',
        'description',
        'video_url',
        'created_at',
    ];

    /**
     * Get the module that owns the record class.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
