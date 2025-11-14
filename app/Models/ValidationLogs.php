<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValidationLogs extends Model
{
    protected $table = 'validation_logs';
      protected $fillable = [
        'import_log_id',
        'row_number',
        'table_name',
        'column_name',
        'column_value',
        'message'
    ];
    public function importLog()
    {
        return $this->belongsTo(ImportLog::class);
    }
}
