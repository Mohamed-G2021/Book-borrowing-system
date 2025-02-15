<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class BorrowHistory extends Model
{
    protected $fillable = [
        'borrower_type',
        'borrower_id',
        'borrowable_type',
        'borrowable_id',
        'borrowed_at',
        'due_date',
        'returned_at',
        'status'
    ];

    // cast the attributes to datetime
    protected function casts(): array
    {
        return [
            'borrowed_at' => 'datetime',
            'due_date' => 'datetime',
            'returned_at' => 'datetime',
        ];
    }

    public function borrower(): MorphTo
    {
        return $this->morphTo();
    }

    public function borrowable(): MorphTo
    {
        return $this->morphTo();
    }
}
