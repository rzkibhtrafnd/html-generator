<!DOCTYPE html>
<html>
<head>
    <title>Riwayat Generate</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-5">
    <h1 class="mb-4">Riwayat Generate</h1>
    
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Tanggal</th>
                <th>Jumlah File</th>
                <th>Unduh ZIP</th>
                <th>Preview</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($uploads as $u)
                <tr>
                    <td>{{ $u->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $u->generated_count }}</td>
                    <td>
                        <a href="{{ asset('storage/' . $u->zip_path) }}" 
                           class="btn btn-sm btn-success">
                           Unduh
                        </a>
                    </td>
                    <td>
                        <a href="{{ route('preview', $u->id) }}" 
                           class="btn btn-sm btn-info">
                           Preview
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">Tidak ada riwayat</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-3">
        {{ $uploads->links() }}
    </div>

    <a href="/" class="btn btn-secondary">Kembali</a>
</body>
</html>