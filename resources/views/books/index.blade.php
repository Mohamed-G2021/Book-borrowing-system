@extends('layouts.app')

@section('content')
<div class="container">
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

            <div class="card">
                <div class="card-body">
                    <x-alert />
                    
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
</script>
@endpush
@endsection