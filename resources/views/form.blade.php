<!DOCTYPE html>
<html>
<head>
    <title>HTML Generator</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Generate HTML dari Excel</h1>
    
    <form method="POST" action="{{ route('generate') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="mb-3">
            <label class="form-label">File Excel:</label>
            <input type="file" name="data_file" class="form-control" required>
            @error('data_file')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label class="form-label">Template HTML:</label>
            <textarea name="template_text" class="form-control" rows="10" required>{{ old('template_text') }}</textarea>
            @error('template_text')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Generate</button>
        <a href="{{ route('history') }}" class="btn btn-link">Lihat Riwayat</a>
    </form>

    @if(session('error'))
        <div class="alert alert-danger mt-3">
            {{ session('error') }}
        </div>
    @endif
</body>
</html>