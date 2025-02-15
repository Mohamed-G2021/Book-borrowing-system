<?php

namespace App\Services;

use App\Models\Book;
use App\Models\BorrowHistory;
use Barryvdh\DomPDF\PDF;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookService
{
    public function createBook(array $data)
    {
        return DB::transaction(function () use ($data) {
            return Book::create([
                'title' => $data['title'],
                'author' => $data['author'],
                'isbn' => $data['isbn'],
                'description' => $data['description'] ?? null,
                'total_copies' => $data['total_copies'],
                'available_copies' => $data['total_copies'],
                'status' => 'available'
            ]);
        });
    }
    public function updateBook(Book $book, array $data)
    {
        return DB::transaction(function () use ($book, $data) {
            $newTotalCopies = $data['total_copies'];

            if ($newTotalCopies < $book->borrowHistories()->where('status', '!=', 'returned')->count()) {
                throw new \Exception('Total copies cannot be less than the borrowed copies');
            }

            $book->update([
                'title' => $data['title'],
                'author' => $data['author'],
                'isbn' => $data['isbn'],
                'description' => $data['description'] ?? null,
                'total_copies' => $newTotalCopies,
                'status' => 'available'
            ]);

            $book->available_copies = $newTotalCopies - $book->borrowHistories()->where('status', '!=', 'returned')->count();
            $book->save();

            return $book;
        });
    }

    public function deleteBook(Book $book)
    {
        return DB::transaction(function () use ($book) {
            $book->delete();
        });
    }

    public function borrowBook(Book $book)
    {
        return DB::transaction(function () use ($book) {
            // Check book availability
            if ($book->available_copies <= 0) {
                throw new \Exception('Book is not available for borrowing.');
            }

            // Create borrow history
            $borrowHistory = BorrowHistory::create([
                'borrower_type' => get_class(Auth::user()),
                'borrower_id' => Auth::id(),
                'borrowable_type' => Book::class,
                'borrowable_id' => $book->id,
                'borrowed_at' => now(),
                'due_date' => now()->addDays(14),
                'status' => 'borrowed'
            ]);

            // Update book availability
            $book->decrement('available_copies');
            $book->status = $book->available_copies === 0 ? 'borrowed' : 'available';
            $book->save();

            return $borrowHistory;
        });
    }

    public function returnBook(Book $book, BorrowHistory $borrowHistory)
    {
        DB::transaction(function () use ($book, $borrowHistory) {
            // Update borrow history status
            $borrowHistory->update([
                'status' => 'returned',
                'return_date' => now()
            ]);
    
            // Increment available copies
            $book->increment('available_copies');
        });
    
        return $borrowHistory;
    }
}