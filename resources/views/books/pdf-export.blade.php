<!DOCTYPE html>
<html>
<head>
    <title>Book Catalog Export</title>
    <style>
        body { font-family: Arial, sans-serif; }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-bottom: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
            font-weight: bold; 
        }
        h1 { 
            text-align: center; 
            color: #333; 
        }
    </style>
</head>
<body>
    <h1>Book Catalog</h1>
    <table>
        <thead>
            <tr>
                <th>Title</th>
                <th>Author</th>
                <th>ISBN</th>
                <th>Total Copies</th>
                <th>Available Copies</th>
            </tr>
        </thead>
        <tbody>
            @foreach($books as $book)
            <tr>
                <td>{{ $book->title }}</td>
                <td>{{ $book->author }}</td>
                <td>{{ $book->isbn }}</td>
                <td>{{ $book->total_copies }}</td>
                <td>{{ $book->available_copies }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="text-align: right; font-size: 0.8em;">
        Generated on: {{ now()->format('Y-m-d H:i:s') }}
    </p>
</body>
</html>