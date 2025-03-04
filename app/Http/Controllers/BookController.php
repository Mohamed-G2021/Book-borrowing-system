<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use App\Models\Book;
use App\Models\BorrowHistory;
use App\Services\BookService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
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

    public function borrow(Request $request, Book $book)
    {
        // Check if user has already borrowed this book
        if (Auth::user()->hasBorrowedBook($book)) {
            return $request->ajax() 
                ? response()->json(['error' => 'You have already borrowed this book.'], 400)
                : redirect()->back()->with('error', 'You have already borrowed this book.');
        }

        try {
            $this->bookService->borrowBook($book);
            
            return $request->ajax() 
                ? response()->json([
                    'success' => 'Book borrowed successfully', 
                    'book_id' => $book->id, 
                    'available_copies' => $book->fresh()->available_copies
                ])
                : redirect()->route('books.index')->with('success', 'Book borrowed successfully');
        } catch (\Exception $e) {
            return $request->ajax() 
                ? response()->json(['error' => $e->getMessage()], 400)
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function return(Request $request, Book $book)
    {
        try {
            // Find the active borrow for this book by the current user
            $borrowHistory = BorrowHistory::where('borrower_id', Auth::id())
                ->where('borrowable_id', $book->id)
                ->where('borrowable_type', Book::class)
                ->where('status', '!=', 'returned')
                ->first();
    
            if (!$borrowHistory) {
                return $request->ajax() 
                    ? response()->json(['error' => 'You have not borrowed this book.'], 400)
                    : redirect()->back()->with('error', 'You have not borrowed this book.');
            }
    
            $this->bookService->returnBook($book, $borrowHistory);
            
            return $request->ajax() 
                ? response()->json([
                    'success' => 'Book returned successfully', 
                    'book_id' => $book->id, 
                    'available_copies' => $book->fresh()->available_copies
                ])
                : redirect()->route('books.index')->with('success', 'Book returned successfully');
        } catch (\Exception $e) {
            return $request->ajax() 
                ? response()->json(['error' => $e->getMessage()], 400)
                : redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function exportPdf()
    {
        // Fetch all books
        $books = Book::all();

        // Generate PDF
        $pdf = PDF::loadView('books.pdf-export', compact('books'));
        
        // Download the PDF
        return $pdf->download('book_catalog_' . now()->format('Y-m-d') . '.pdf');
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
