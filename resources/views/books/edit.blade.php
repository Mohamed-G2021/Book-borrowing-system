@extends('layouts.app')

@section('content')
<div class="container">
    <x-card>
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit Book</div>

                    <div class="card-body">
                        <x-alert />

                        <form method="POST" action="{{ route('books.update', $book) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row mb-3">
                                <label for="title" class="col-md-4 col-form-label text-md-right">Title</label>
                                <div class="col-md-6">
                                    <input id="title" type="text" 
                                        class="form-control @error('title') is-invalid @enderror" 
                                        name="title" 
                                        value="{{ old('title', $book->title) }}" 
                                        required 
                                        autocomplete="title" 
                                        autofocus>

                                    @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="author" class="col-md-4 col-form-label text-md-right">Author</label>
                                <div class="col-md-6">
                                    <input id="author" type="text" 
                                        class="form-control @error('author') is-invalid @enderror" 
                                        name="author" 
                                        value="{{ old('author', $book->author) }}" 
                                        required 
                                        autocomplete="author">

                                    @error('author')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="isbn" class="col-md-4 col-form-label text-md-right">ISBN</label>
                                <div class="col-md-6">
                                    <input id="isbn" type="text" 
                                        class="form-control @error('isbn') is-invalid @enderror" 
                                        name="isbn" 
                                        value="{{ old('isbn', $book->isbn) }}" 
                                        required 
                                        autocomplete="isbn">

                                    @error('isbn')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="description" class="col-md-4 col-form-label text-md-right">Description</label>
                                <div class="col-md-6">
                                    <textarea id="description" 
                                        class="form-control @error('description') is-invalid @enderror" 
                                        name="description" 
                                        autocomplete="description">{{ old('description', $book->description) }}</textarea>

                                    @error('description')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-3">
                                <label for="total_copies" class="col-md-4 col-form-label text-md-right">Total Copies</label>
                                <div class="col-md-6">
                                    <input id="total_copies" type="number" 
                                        class="form-control @error('total_copies') is-invalid @enderror" 
                                        name="total_copies" 
                                        value="{{ old('total_copies', $book->total_copies) }}" 
                                        required 
                                        min="1">

                                    @error('total_copies')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        Update Book
                                    </button>
                                    <a href="{{ route('books.index') }}" class="btn btn-secondary ml-2">
                                        Cancel
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </x-card>
</div>
@endsection