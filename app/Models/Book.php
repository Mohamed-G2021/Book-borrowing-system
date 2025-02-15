<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Book extends Model
{
    protected $fillable = [
        'title',
        'author',
        'isbn',
        'description',
        'status',
        'total_copies',
        'available_copies'
    ];

    public function borrowHistories(): MorphMany
    {
        return $this->morphMany(BorrowHistory::class, 'borrowable');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available')->where('available_copies', '>', 0);
    }
}
