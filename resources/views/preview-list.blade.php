<!DOCTYPE html>
<html>
<head>
    <title>Daftar File Hasil Generate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Daftar File Hasil Generate</h1>
    
    <div class="alert alert-info mb-4">
        <strong>Total File:</strong> {{ $upload->generated_count }}
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>File HTML</span>
            <a href="{{ asset('storage/' . $upload->zip_path) }}" 
               class="btn btn-sm btn-success" download>
               Download Semua (ZIP)
            </a>
        </div>
        
        <div class="list-group list-group-flush">
            @forelse ($fileList as $file)
                <a href="{{ route('preview.file', ['id' => $upload->id, 'filename' => $file['filename']]) }}" 
                   class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                   {{ $file['filename'] }}
                   <span class="badge bg-primary rounded-pill">Preview</span>
                </a>
            @empty
                <div class="list-group-item text-center text-muted">
                    Tidak ada file yang tersedia
                </div>
            @endforelse
        </div>
    </div>

    <a href="{{ route('history') }}" class="btn btn-secondary">Kembali ke Riwayat</a>
</body>
</html>