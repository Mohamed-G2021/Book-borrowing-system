<div class="btn-group" role="group">
    @role('user')
        @php
            // Find the current user's active borrow for this book
            $currentBorrow = auth()->user() 
                ? app\Models\BorrowHistory::where('borrower_id', auth()->id())
                    ->where('borrowable_id', $book->id)
                    ->where('borrowable_type', app\Models\Book::class)
                    ->where('status', '!=', 'returned')
                    ->first()
                : null;
        @endphp

        @if($book->available_copies > 0 && $canBorrow)
        <button type="button" 
                class="btn btn-sm btn-success borrow-book-btn" 
                data-book-id="{{ $book->id }}"
                data-book-title="{{ $book->title }}">
            Borrow
        </button>
        @endif

        @if($currentBorrow)
        <button type="button" 
                class="btn btn-sm btn-warning return-book-btn" 
                data-book-id="{{ $book->id }}"
                data-book-title="{{ $book->title }}">
            Return
        </button>
        @endif
    @endrole

    @role('admin')
    <a href="{{ route('books.edit', $book) }}" class="btn btn-sm btn-warning">
        Edit
    </a>
    <form action="{{ route('books.destroy', $book) }}" method="POST" class="d-inline">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this book?')">
            Delete
        </button>
    </form>
    @endrole
</div>