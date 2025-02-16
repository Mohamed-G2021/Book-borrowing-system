@extends('layouts.app')

@section('content')
<div class="container">
    <x-card>
        <div class="row">
            <div class="col-md-12">
                <h1 class="mb-4">Book Catalog</h1>

                @role('admin')
                <div class="mb-3">
                    <a href="{{ route('books.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add New Book
                    </a>
                </div>
                @endrole

                <div class="mb-3">
                    <a href="{{ route('books.export-pdf') }}" class="btn btn-danger ml-2" target="_blank">
                        <i class="fas fa-file-pdf"></i> Export PDF
                    </a>
                </div>

                <div class="card">
                    <div class="card-body">
                        <x-alert />
                        
                        <div class="table-responsive">
                            <table class="table table-striped" id="books-table">
                                <thead>
                                    <tr>
                                        <th>Title</th>
                                        <th>Author</th>
                                        <th>ISBN</th>
                                        <th>Total Copies</th>
                                        <th>Available Copies</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>

@push('scripts')
<script>
$(function() {
    $('#books-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: '{{ route('books.index') }}',
        columns: [
            { data: 'title', name: 'title' },
            { data: 'author', name: 'author' },
            { data: 'isbn', name: 'isbn' },
            { data: 'total_copies', name: 'total_copies' },
            { data: 'available_copies', name: 'available_copies' },
            { 
                data: 'actions', 
                name: 'actions', 
                orderable: false, 
                searchable: false 
            }
        ]
    });
});

document.addEventListener('DOMContentLoaded', function() {
    const booksTable = document.getElementById('books-table');
    
    booksTable.addEventListener('click', function(event) {
        const borrowButton = event.target.closest('.borrow-book-btn');
        const returnButton = event.target.closest('.return-book-btn');
        
        if (borrowButton) {
            handleBookAction(borrowButton, 'borrow');
        }
        
        if (returnButton) {
            handleBookAction(returnButton, 'return');
        }
    });

    function handleBookAction(button, action) {
        const bookId = button.dataset.bookId;
        const bookTitle = button.dataset.bookTitle;
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        fetch(`/books/${bookId}/${action}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(data => {
                    throw new Error(data.error || 'An error occurred');
                });
            }
            return response.json();
        })
        .then(data => {
            // Refresh the DataTable
            if ($.fn.DataTable.isDataTable('#books-table')) {
                $('#books-table').DataTable().ajax.reload(null, false);
            }
            
            // Show success message (you can replace with a more sophisticated notification)
            const notification = document.createElement('div');
            notification.classList.add('alert', 'alert-success', 'mb-3', 'text-center');
            notification.role = 'alert';
            notification.textContent = `Successfully ${action}ed "${bookTitle}"`;
            document.querySelector('.container').insertBefore(notification, document.querySelector('.container > .card'));
            setTimeout(() => notification.remove(), 2000);
        })
        .catch(error => {
            // Dispatch a custom event for the alert
            window.dispatchEvent(new CustomEvent('show-alert', {
                detail: {
                    type: 'danger',
                    message: error.message
                }
            }));
        });
    }
});
</script>
@endpush
@endsection