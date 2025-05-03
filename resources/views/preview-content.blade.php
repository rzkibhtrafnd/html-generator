<!DOCTYPE html>
<html>
<head>
    <title>Preview: {{ $filename }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .preview-container {
            border: 1px solid #ddd;
            padding: 20px;
            margin-top: 20px;
            background: white;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="container mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Preview: {{ $filename }}</h1>
        <a href="{{ route('preview', $upload->id) }}" class="btn btn-secondary">
            Kembali ke Daftar
        </a>
    </div>

    <div class="card mb-3">
        <div class="card-header">
            <strong>File:</strong> {{ $filename }}
        </div>
        <div class="card-body preview-container">
            {!! $content !!}
        </div>
    </div>

    <div class="d-flex gap-2">
        <a href="{{ asset('storage/' . $upload->zip_path) }}" 
           class="btn btn-success" download>
           Download Semua (ZIP)
        </a>
        
        <a href="{{ route('preview', $upload->id) }}" 
           class="btn btn-primary">
           Lihat Semua File
        </a>
    </div>
</body>
</html>