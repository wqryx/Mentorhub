<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
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
        'issue_date',
        'certificate_number',
        'pdf_path',
    ];

    /**
     * Get the user that owns the certificate.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the module that owns the certificate.
     */
    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
