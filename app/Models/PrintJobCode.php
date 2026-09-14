<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintJobCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'print_job_id',
        'code',
        'last_5_chars',
        'printed_at',
    ];

    protected $casts = [
        'printed_at' => 'datetime',
    ];

    public function printJob()
    {
        return $this->belongsTo(PrintJob::class);
    }
}
