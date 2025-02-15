<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        $books = [
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '9780446310789',
                'total_copies' => 5,
                'available_copies' => 5
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '9780451524935',
                'total_copies' => 3,
                'available_copies' => 3
            ],
            [
                'title' => 'Pride and Prejudice',
                'author' => 'Jane Austen',
                'isbn' => '9780141439518',
                'total_copies' => 4,
                'available_copies' => 4
            ]
        ];

        foreach ($books as $bookData) {
            Book::create($bookData);
        }
    }
}
