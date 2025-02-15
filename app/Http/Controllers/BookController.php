<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\BorrowHistory;
use App\Services\BookService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller implements HasMiddleware
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    /**
     * Get the middleware that should be assigned to the controller.
    */
    public static function middleware(): array
    {
        return [
            'auth', // Ensures user is authenticated

            // Admin can access everything EXCEPT borrow and returnBook
            new Middleware('role', ['admin'], except: ['borrow', 'returnBook']),

            // User can access everything EXCEPT create, store, edit, update, destroy
            new Middleware('role', ['user'], except: ['create', 'store', 'edit', 'update', 'destroy']),
        ];
    }

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $query = Book::query();
            
            return DataTables::of($query)
                ->addColumn('actions', function ($book) {
                    return view('books.actions', [
                        'book' => $book,
                        'canBorrow' => $this->canUserBorrowBook($book)
                    ]);
                })
                ->rawColumns(['actions'])
                ->make(true);
        }

        return view('books.index');
    }

    public function create()
    {
        return view('books.create');
    }

    public function store(StoreBookRequest $request)
    {
        $validatedData = $request->validated();

        $book = $this->bookService->createBook($validatedData);

        return redirect()->route('books.index')
            ->with('success', 'Book added successfully');
    }

    public function edit(Book $book)
    {
        return view('books.edit', compact('book'));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $validatedData = $request->validated();

        try {
            $updatedBook = $this->bookService->updateBook($book, $validatedData);

            return redirect()->route('books.index')
                ->with('success', 'Book updated successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);

        return redirect()->route('books.index')
            ->with('success', 'Book deleted successfully');
    }

    public function borrow(Book $book)
    {
        // Check if user has already borrowed this book
        if (Auth::user()->hasBorrowedBook($book)) {
            return redirect()->back()->with('error', 'You have already borrowed this book.');
        }

        try {
            $this->bookService->borrowBook($book);
            return redirect()->route('books.index')->with('success', 'Book borrowed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function return(Book $book)
    {
        try {
            // Find the active borrow for this book by the current user
            $borrowHistory = BorrowHistory::where('borrower_id', Auth::id())
                ->where('borrowable_id', $book->id)
                ->where('borrowable_type', Book::class)
                ->where('status', '!=', 'returned')
                ->first();
    
            if (!$borrowHistory) {
                return redirect()->back()->with('error', 'You have not borrowed this book.');
            }
    
            $this->bookService->returnBook($book, $borrowHistory);
            
            return redirect()->route('books.index')->with('success', 'Book returned successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    protected function canUserBorrowBook(Book $book)
    {
        if (!auth()->check()) {
            return false;
        }

        // Check if the user has an active borrow history for this book
        return !auth()->user()->hasBorrowedBook($book);

    }
}

