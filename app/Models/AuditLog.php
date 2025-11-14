<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $fillable = [
        'import_type',
        'row_number',
        'model',
        'file_name',
        'column_name',
        'old_value',
        'new_value'
    ];
}
