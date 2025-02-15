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
        <form action="{{ route('books.borrow', $book) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-success">
                Borrow
            </button>
        </form>
        @endif

        @if($currentBorrow)
        <form action="{{ route('books.return', $book) }}" method="POST" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-sm btn-warning">
                Return
            </button>
        </form>
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