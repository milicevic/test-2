<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ImportLog extends Model
{
    public const STATUS_SUCCESSFUL = 'SUCCESSFUL';
    public const STATUS_UNSUCCESSFUL = 'UNSUCCESSFUL';

    protected $table = 'import_logs';
    protected $fillable = [
        'user_id',
        'import_type',
        'file_name',
        'original_file',
        'status',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function validationLogs()
    {
        return $this->hasMany(ValidationLogs::class);
    }
}
